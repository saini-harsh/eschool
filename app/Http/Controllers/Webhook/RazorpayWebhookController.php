<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SalaryPayment;
use App\Services\RazorpayXService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    /**
     * Handle incoming Razorpay webhook.
     */
    public function handle(Request $request, $institutionId)
    {
        Log::info('Razorpay Webhook Received', [
            'institution_id' => $institutionId,
            'event' => $request->input('event'),
        ]);

        // Find the institution
        $institution = Institution::find($institutionId);
        if (!$institution) {
            Log::error('Webhook: Institution not found', ['institution_id' => $institutionId]);
            return response()->json(['error' => 'Institution not found'], 404);
        }

        // Verify webhook signature if webhook secret is configured
        if ($institution->razorpay_webhook_secret) {
            $signature = $request->header('X-Razorpay-Signature');
            $payload = $request->getContent();
            
            $razorpay = new RazorpayXService($institution);
            if (!$razorpay->validateWebhook($payload, $signature)) {
                Log::warning('Webhook: Invalid signature', ['institution_id' => $institutionId]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        // Handle different events
        switch ($event) {
            case 'payout.processed':
            case 'payout.reversed':
            case 'payout.failed':
            case 'payout.rejected':
            case 'payout.queued':
            case 'payout.pending':
                return $this->handlePayoutEvent($institution, $payload);
            
            default:
                Log::info('Webhook: Unhandled event', ['event' => $event]);
                return response()->json(['message' => 'Event ignored']);
        }
    }

    /**
     * Handle payout related events.
     */
    private function handlePayoutEvent(Institution $institution, array $payload)
    {
        $payoutData = $payload['payload']['payout']['entity'] ?? null;

        if (!$payoutData) {
            Log::warning('Webhook: No payout data in payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $payoutId = $payoutData['id'];
        $status = $payoutData['status'];

        // Find the payment by razorpay_payout_id
        $payment = SalaryPayment::where('razorpay_payout_id', $payoutId)
            ->where('institution_id', $institution->id)
            ->first();

        if (!$payment) {
            // Try finding by reference_id which we set to transaction_id
            $referenceId = $payoutData['reference_id'] ?? null;
            if ($referenceId) {
                $payment = SalaryPayment::where('transaction_id', $referenceId)
                    ->where('institution_id', $institution->id)
                    ->first();
            }
        }

        if (!$payment) {
            Log::warning('Webhook: Payment not found', [
                'payout_id' => $payoutId,
                'institution_id' => $institution->id
            ]);
            return response()->json(['message' => 'Payment not found, webhook ignored']);
        }

        // Update payment status based on payout status
        $previousStatus = $payment->status;
        
        switch ($status) {
            case 'processed':
                $payment->status = 'paid';
                $payment->payment_date = now();
                $payment->failure_reason = null;
                break;
            
            case 'reversed':
                $payment->status = 'failed';
                $payment->failure_reason = $payoutData['failure_reason'] ?? 'Payment was reversed';
                break;
            
            case 'failed':
            case 'rejected':
            case 'cancelled':
                $payment->status = 'failed';
                $payment->failure_reason = $payoutData['failure_reason'] ?? 'Payment failed or was rejected';
                break;
            
            case 'processing':
            case 'queued':
            case 'pending':
                $payment->status = 'processing';
                break;
        }

        // Update the razorpay_payout_id if not already set
        if (empty($payment->razorpay_payout_id)) {
            $payment->razorpay_payout_id = $payoutId;
        }

        $payment->save();

        Log::info('Webhook: Payment status updated', [
            'payment_id' => $payment->id,
            'payout_id' => $payoutId,
            'previous_status' => $previousStatus,
            'new_status' => $payment->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully'
        ]);
    }

    /**
     * Test webhook endpoint (for verification).
     */
    public function test(Request $request, $institutionId)
    {
        Log::info('Razorpay Webhook Test', ['institution_id' => $institutionId]);
        
        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint is working',
            'institution_id' => $institutionId,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
