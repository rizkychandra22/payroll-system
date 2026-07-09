<?php

namespace App\Services;

use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;

class PayrollCalculator
{
    public function getAllowanceOptions(): array
    {
        return Allowance::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Allowance $allowance): array => [
                $allowance->id => "{$allowance->name} - " . CurrencyFormatter::rupiah($allowance->amount),
            ])
            ->toArray();
    }

    public function getDeductionOptions(): array
    {
        return Deduction::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Deduction $deduction): array => [
                $deduction->id => "{$deduction->name} - " . CurrencyFormatter::rupiah($deduction->amount),
            ])
            ->toArray();
    }

    public function getEmployeeDefaults(int | string | null $employeeId): array
    {
        $employee = Employee::query()
            ->with([
                'allowances:id,name,amount',
                'deductions:id,name,amount',
            ])
            ->find($employeeId);

        $basicSalary = (float) ($employee?->basic_salary ?? 0);
        $allowanceIds = $employee?->allowances->modelKeys() ?? [];
        $deductionIds = $employee?->deductions->modelKeys() ?? [];

        return [
            'position' => $employee?->position ?? '',
            'basic_salary' => $basicSalary,
            'allowance_summary' => $this->formatBenefitSummary($employee?->allowances?->all() ?? []),
            'deduction_summary' => $this->formatBenefitSummary($employee?->deductions?->all() ?? []),
            'allowance_ids' => $allowanceIds,
            'deduction_ids' => $deductionIds,
            ...$this->calculate($basicSalary, $allowanceIds, $deductionIds),
        ];
    }

    public function calculate(
        float | int | string | null $basicSalary,
        array $allowanceIds = [],
        array $deductionIds = [],
    ): array {
        $normalizedBasicSalary = $this->normalizeAmount($basicSalary);
        $normalizedAllowanceIds = $this->normalizeIds($allowanceIds);
        $normalizedDeductionIds = $this->normalizeIds($deductionIds);

        $totalAllowance = (float) Allowance::query()
            ->whereKey($normalizedAllowanceIds)
            ->sum('amount');

        $totalDeduction = (float) Deduction::query()
            ->whereKey($normalizedDeductionIds)
            ->sum('amount');

        return [
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'take_home_pay' => $normalizedBasicSalary + $totalAllowance - $totalDeduction,
        ];
    }

    public function buildSnapshotData(
        int | string $employeeId,
        array $allowanceIds = [],
        array $deductionIds = [],
    ): array {
        $employee = Employee::query()->findOrFail($employeeId);
        $basicSalary = (float) $employee->basic_salary;

        return [
            'basic_salary' => $basicSalary,
            ...$this->calculate($basicSalary, $allowanceIds, $deductionIds),
        ];
    }

    public function getEmployeeBenefitIds(int | string $employeeId): array
    {
        $employee = Employee::query()
            ->with(['allowances:id', 'deductions:id'])
            ->findOrFail($employeeId);

        return [
            'allowance_ids' => $employee->allowances->modelKeys(),
            'deduction_ids' => $employee->deductions->modelKeys(),
        ];
    }

    public function syncPayrollItems(Payroll $payroll, array $allowanceIds = [], array $deductionIds = []): void
    {
        $allowances = Allowance::query()
            ->whereKey($this->normalizeIds($allowanceIds))
            ->orderBy('name')
            ->get(['name', 'amount']);

        $deductions = Deduction::query()
            ->whereKey($this->normalizeIds($deductionIds))
            ->orderBy('name')
            ->get(['name', 'amount']);

        $payroll->items()->delete();

        $items = $allowances
            ->map(fn (Allowance $allowance): array => [
                'type' => 'allowance',
                'name' => $allowance->name,
                'amount' => $allowance->amount,
            ])
            ->merge(
                $deductions->map(fn (Deduction $deduction): array => [
                    'type' => 'deduction',
                    'name' => $deduction->name,
                    'amount' => $deduction->amount,
                ])
            )
            ->values()
            ->all();

        if ($items !== []) {
            $payroll->items()->createMany($items);
        }
    }

    public function getSelectedBenefitIdsFromPayroll(Payroll $payroll): array
    {
        $payroll->loadMissing('items');

        $allowanceNames = $payroll->items
            ->where('type', 'allowance')
            ->pluck('name')
            ->all();

        $deductionNames = $payroll->items
            ->where('type', 'deduction')
            ->pluck('name')
            ->all();

        return [
            'allowance_ids' => Allowance::query()
                ->whereIn('name', $allowanceNames)
                ->pluck('id')
                ->all(),
            'deduction_ids' => Deduction::query()
                ->whereIn('name', $deductionNames)
                ->pluck('id')
                ->all(),
        ];
    }

    public function getPayrollBenefitSummary(Payroll $payroll): array
    {
        $payroll->loadMissing('items');

        $allowanceItems = $payroll->items
            ->where('type', 'allowance')
            ->values();

        $deductionItems = $payroll->items
            ->where('type', 'deduction')
            ->values();

        return [
            'allowance_summary' => $this->formatBenefitSummary($allowanceItems->all()),
            'deduction_summary' => $this->formatBenefitSummary($deductionItems->all()),
        ];
    }

    public function normalizeIds(array $ids): array
    {
        return array_values(array_filter(array_map(
            static fn (mixed $id): int => (int) $id,
            $ids,
        )));
    }

    public function normalizeAmount(float | int | string | null $amount): float
    {
        if (is_numeric($amount)) {
            return (float) $amount;
        }

        if (! is_string($amount) || $amount === '') {
            return 0.0;
        }

        return (float) preg_replace('/[^\d.-]/', '', $amount);
    }

    protected function formatBenefitSummary(array $benefits): string
    {
        if ($benefits === []) {
            return '-';
        }

        return collect($benefits)
            ->map(function (object $benefit): string {
                return "{$benefit->name} (" . CurrencyFormatter::rupiah($benefit->amount) . ')';
            })
            ->implode("\n");
    }
}
