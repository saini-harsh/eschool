<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\SalaryPayment;
use App\Models\Teacher;
use App\Models\NonWorkingStaff;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RazorpayXService
{
    protected $institution;
    protected $keyId;
    protected $keySecret;
    protected $baseUrl = 'https://api.razorpay.com/v1';

    /**
     * Create a new service instance
     */
    public function __construct(Institution $institution)
    {
        $this->institution = $institution;
        $this->keyId = $institution->razorpay_key_id;
        $this->keySecret = $institution->razorpay_key_secret;
    }

    /**
     * Check if Razorpay is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->keyId) && !empty($this->keySecret);
    }

    /**
     * Make an API request to Razorpay
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception('RazorpayX is not configured for this institution');
        }

        $url = $this->baseUrl . $endpoint;

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->timeout(30);

            if ($method === 'GET') {
                $response = $response->get($url, $data);
            } elseif ($method === 'POST') {
                $response = $response->post($url, $data);
            } elseif ($method === 'PATCH') {
                $response = $response->patch($url, $data);
            }

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('RazorpayX API Error', [
                'institution_id' => $this->institution->id,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new Exception($response->json()['error']['description'] ?? 'RazorpayX API Error');
        } catch (Exception $e) {
            Log::error('RazorpayX Request Exception', [
                'institution_id' => $this->institution->id,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a contact for a payee
     */
    public function createContact(string $name, string $email, string $phone, string $type = 'employee', array $notes = []): array
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'contact' => $phone,
            'type' => $type,
            'reference_id' => uniqid('contact_'),
            'notes' => $notes,
        ];

        return $this->request('POST', '/contacts', $data);
    }

    /**
     * Create a fund account (bank account) for a contact
     */
    public function createFundAccount(string $contactId, string $accountNumber, string $ifscCode, string $beneficiaryName): array
    {
        $data = [
            'contact_id' => $contactId,
            'account_type' => 'bank_account',
            'bank_account' => [
                'name' => $beneficiaryName,
                'ifsc' => $ifscCode,
                'account_number' => $accountNumber,
            ],
        ];

        return $this->request('POST', '/fund_accounts', $data);
    }

    /**
     * Create a payout
     */
    public function createPayout(
        string $fundAccountId,
        float $amount,
        string $currency = 'INR',
        string $mode = 'IMPS',
        string $purpose = 'salary',
        array $notes = [],
        string $referenceId = null
    ): array {
        $data = [
            'account_number' => $this->getAccountNumber(),
            'fund_account_id' => $fundAccountId,
            'amount' => (int) ($amount * 100), // Convert to paise
            'currency' => $currency,
            'mode' => $mode,
            'purpose' => $purpose,
            'queue_if_low_balance' => true,
            'reference_id' => $referenceId ?? uniqid('payout_'),
            'narration' => 'Salary Payment',
            'notes' => $notes,
        ];

        return $this->request('POST', '/payouts', $data);
    }

    /**
     * Get payout status
     */
    public function getPayoutStatus(string $payoutId): array
    {
        return $this->request('GET', "/payouts/{$payoutId}");
    }

    /**
     * Get the RazorpayX account number
     * This needs to be fetched from the institution's Razorpay dashboard
     * For now, we'll store it as a config or in the institution record
     */
    protected function getAccountNumber(): string
    {
        // This should ideally come from the institution's configuration
        // For RazorpayX, you need to use your business account number
        // which can be found in your RazorpayX dashboard
        return config('services.razorpay.account_number', '');
    }

    /**
     * Setup a payee (create contact and fund account if not exists)
     */
    public function setupPayee($payee): array
    {
        $name = $payee->first_name . ' ' . $payee->last_name;
        $type = $payee instanceof Teacher ? 'teacher' : 'staff';

        // Check if contact already exists
        if (empty($payee->razorpay_contact_id)) {
            $contact = $this->createContact(
                $name,
                $payee->email,
                $payee->phone,
                'employee',
                [
                    'type' => $type,
                    'employee_id' => $payee->employee_id ?? $payee->id,
                    'institution_id' => $this->institution->id,
                ]
            );

            $payee->razorpay_contact_id = $contact['id'];
            $payee->save();
        }

        // Check if fund account already exists
        if (empty($payee->razorpay_fund_account_id) && $payee->hasBankDetails()) {
            $fundAccount = $this->createFundAccount(
                $payee->razorpay_contact_id,
                $payee->bank_account_number,
                $payee->bank_ifsc_code,
                $name
            );

            $payee->razorpay_fund_account_id = $fundAccount['id'];
            $payee->save();
        }

        return [
            'contact_id' => $payee->razorpay_contact_id,
            'fund_account_id' => $payee->razorpay_fund_account_id,
        ];
    }

    /**
     * Process a salary payment
     */
    public function processSalaryPayment(SalaryPayment $payment): array
    {
        // Get the payee
        $payee = $payment->payee;

        if (!$payee) {
            throw new Exception('Payee not found');
        }

        if (!$payee->hasBankDetails()) {
            throw new Exception('Bank details not configured for this payee');
        }

        // Setup payee in Razorpay if not already done
        $razorpayConfig = $this->setupPayee($payee);

        if (empty($razorpayConfig['fund_account_id'])) {
            throw new Exception('Failed to setup fund account in Razorpay');
        }

        // Update payment status
        $payment->status = 'processing';
        $payment->razorpay_fund_account_id = $razorpayConfig['fund_account_id'];
        $payment->save();

        // Create payout
        try {
            $payout = $this->createPayout(
                $razorpayConfig['fund_account_id'],
                $payment->net_salary,
                'INR',
                'IMPS',
                'salary',
                [
                    'payment_id' => $payment->id,
                    'month' => $payment->month,
                    'year' => $payment->year,
                    'payee_type' => $payment->payee_type,
                    'payee_id' => $payment->payee_id,
                ],
                $payment->transaction_id
            );

            $payment->razorpay_payout_id = $payout['id'];
            $payment->save();

            return $payout;
        } catch (Exception $e) {
            $payment->status = 'failed';
            $payment->failure_reason = $e->getMessage();
            $payment->save();
            throw $e;
        }
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhook(string $payload, string $signature): bool
    {
        if (empty($this->institution->razorpay_webhook_secret)) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->institution->razorpay_webhook_secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle webhook event
     */
    public function handleWebhook(array $payload): bool
    {
        $event = $payload['event'] ?? '';
        $payoutData = $payload['payload']['payout']['entity'] ?? null;

        if (!$payoutData) {
            return false;
        }

        $payoutId = $payoutData['id'];
        $status = $payoutData['status'];

        // Find the payment by razorpay_payout_id
        $payment = SalaryPayment::where('razorpay_payout_id', $payoutId)
            ->where('institution_id', $this->institution->id)
            ->first();

        if (!$payment) {
            Log::warning('Webhook: Payment not found for payout', ['payout_id' => $payoutId]);
            return false;
        }

        switch ($status) {
            case 'processed':
                $payment->status = 'paid';
                $payment->payment_date = now();
                break;
            case 'reversed':
            case 'failed':
            case 'rejected':
                $payment->status = 'failed';
                $payment->failure_reason = $payoutData['failure_reason'] ?? 'Payment failed';
                break;
            case 'processing':
            case 'queued':
                $payment->status = 'processing';
                break;
        }

        $payment->save();

        Log::info('Webhook: Payment status updated', [
            'payment_id' => $payment->id,
            'payout_id' => $payoutId,
            'status' => $payment->status,
        ]);

        return true;
    }

    /**
     * Get all payouts for the institution
     */
    public function getPayouts(array $filters = []): array
    {
        return $this->request('GET', '/payouts', $filters);
    }

    /**
     * Get payout modes available
     */
    public static function getPayoutModes(): array
    {
        return [
            'NEFT' => 'NEFT (National Electronic Funds Transfer)',
            'RTGS' => 'RTGS (Real Time Gross Settlement)',
            'IMPS' => 'IMPS (Immediate Payment Service)',
            'UPI' => 'UPI (Unified Payments Interface)',
        ];
    }
}
