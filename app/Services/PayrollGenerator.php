<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Str;

class PayrollGenerator
{
    public function __construct(
        protected PayrollCalculator $payrollCalculator,
    ) {}

    public function getPositionOptions(): array
    {
        return Employee::query()
            ->select('position')
            ->selectRaw('COUNT(*) as employee_count')
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->groupBy('position')
            ->orderBy('position')
            ->get()
            ->mapWithKeys(fn (Employee $employee): array => [
                $employee->position => sprintf(
                    '%s (%d %s)',
                    $employee->position,
                    $employee->employee_count,
                    Str::plural('employee', (int) $employee->employee_count),
                ),
            ])
            ->toArray();
    }

    public function generateForPositions(
        int | string $month,
        int | string $year,
        array $positions,
    ): array {
        $normalizedMonth = (int) $month;
        $normalizedYear = (int) $year;
        $normalizedPositions = array_values(array_filter(array_map(
            static fn (mixed $position): string => trim((string) $position),
            $positions,
        )));

        $employees = Employee::query()
            ->with([
                'allowances:id,name,amount',
                'deductions:id,name,amount',
            ])
            ->whereIn('position', $normalizedPositions)
            ->orderBy('full_name')
            ->get();

        $generatedAt = now();
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($employees as $employee) {
            $alreadyExists = Payroll::query()
                ->where('employee_id', $employee->id)
                ->where('payroll_month', $normalizedMonth)
                ->where('payroll_year', $normalizedYear)
                ->exists();

            if ($alreadyExists) {
                $skippedCount++;

                continue;
            }

            $allowanceIds = $employee->allowances->modelKeys();
            $deductionIds = $employee->deductions->modelKeys();
            $basicSalary = (float) $employee->basic_salary;
            $totals = $this->payrollCalculator->calculate($basicSalary, $allowanceIds, $deductionIds);

            $payroll = Payroll::query()->create([
                'employee_id' => $employee->id,
                'payroll_month' => $normalizedMonth,
                'payroll_year' => $normalizedYear,
                'basic_salary' => $basicSalary,
                'total_allowance' => $totals['total_allowance'],
                'total_deduction' => $totals['total_deduction'],
                'take_home_pay' => $totals['take_home_pay'],
                'generated_at' => $generatedAt,
            ]);

            $this->payrollCalculator->syncPayrollItems($payroll, $allowanceIds, $deductionIds);

            $createdCount++;
        }

        return [
            'employee_count' => $employees->count(),
            'created_count' => $createdCount,
            'skipped_count' => $skippedCount,
        ];
    }
}
