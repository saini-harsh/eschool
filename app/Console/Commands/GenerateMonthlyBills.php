<?php

namespace App\Console\Commands;

use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:generate-monthly {--institution= : Specific institution ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly bills for all students based on fee structures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly bill generation...');

        $institutionId = $this->option('institution');
        
        // Get fee structures for monthly billing
        $query = FeeStructure::where('status', true)
            ->where('payment_frequency', 'monthly');

        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }

        $feeStructures = $query->get();

        if ($feeStructures->isEmpty()) {
            $this->warn('No monthly fee structures found.');
            return;
        }

        $totalBillsGenerated = 0;
        $currentMonth = now()->month;
        $currentYear = now()->year;

        foreach ($feeStructures as $feeStructure) {
            $this->info("Processing fee structure: {$feeStructure->fee_name}");

            // Get students for this fee structure
            $studentsQuery = Student::where('institution_id', $feeStructure->institution_id)
                ->where('class_id', $feeStructure->class_id)
                ->where('status', true);

            if ($feeStructure->section_id) {
                $studentsQuery->where('section_id', $feeStructure->section_id);
            }

            $students = $studentsQuery->get();

            foreach ($students as $student) {
                // Check if bill already exists for this month
                $existingBill = StudentFee::where('student_id', $student->id)
                    ->where('fee_structure_id', $feeStructure->id)
                    ->whereMonth('billing_date', $currentMonth)
                    ->whereYear('billing_date', $currentYear)
                    ->first();

                if (!$existingBill) {
                    try {
                        DB::beginTransaction();

                        $dueDate = $feeStructure->due_date 
                            ? $feeStructure->due_date->copy()->setMonth($currentMonth)->setYear($currentYear)
                            : now()->addDays(30);

                        StudentFee::create([
                            'student_id' => $student->id,
                            'fee_structure_id' => $feeStructure->id,
                            'institution_id' => $feeStructure->institution_id,
                            'amount' => $feeStructure->amount,
                            'paid_amount' => 0,
                            'balance_amount' => $feeStructure->amount,
                            'due_date' => $dueDate,
                            'billing_date' => now(),
                            'status' => 'pending',
                        ]);

                        DB::commit();
                        $totalBillsGenerated++;

                        $this->line("Generated bill for student: {$student->first_name} {$student->last_name}");

                    } catch (\Exception $e) {
                        DB::rollback();
                        $this->error("Failed to generate bill for student {$student->id}: " . $e->getMessage());
                    }
                } else {
                    $this->line("Bill already exists for student: {$student->first_name} {$student->last_name}");
                }
            }
        }

        $this->info("Monthly bill generation completed!");
        $this->info("Total bills generated: {$totalBillsGenerated}");

        // Update overdue status for existing bills
        $this->updateOverdueStatus($institutionId);
    }

    /**
     * Update overdue status for existing bills
     */
    private function updateOverdueStatus($institutionId = null)
    {
        $this->info('Updating overdue status...');

        $query = StudentFee::whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', now());

        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }

        $overdueBills = $query->get();
        $updatedCount = 0;

        foreach ($overdueBills as $bill) {
            $bill->status = 'overdue';
            $bill->save();
            $updatedCount++;
        }

        $this->info("Updated {$updatedCount} bills to overdue status.");
    }
}
