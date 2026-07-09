<?php

namespace App\Filament\Resources\Payrolls\Concerns;

use App\Services\PayrollCalculator;

trait InteractsWithPayrollSelections
{
    protected array $selectedAllowanceIds = [];

    protected array $selectedDeductionIds = [];

    protected function preparePayrollDataForPersistence(array $data): array
    {
        $allowanceIds = $this->getPayrollCalculator()->normalizeIds($data['allowance_ids'] ?? []);
        $deductionIds = $this->getPayrollCalculator()->normalizeIds($data['deduction_ids'] ?? []);
        $snapshot = $this->getPayrollCalculator()->buildSnapshotData(
            employeeId: $data['employee_id'],
            allowanceIds: $allowanceIds,
            deductionIds: $deductionIds,
        );

        $this->selectedAllowanceIds = $allowanceIds;
        $this->selectedDeductionIds = $deductionIds;

        unset($data['allowance_ids'], $data['deduction_ids']);

        return [
            ...$data,
            ...$snapshot,
        ];
    }

    protected function syncPayrollItems(): void
    {
        $this->getPayrollCalculator()->syncPayrollItems(
            payroll: $this->getRecord(),
            allowanceIds: $this->selectedAllowanceIds,
            deductionIds: $this->selectedDeductionIds,
        );
    }

    protected function getPayrollCalculator(): PayrollCalculator
    {
        return app(PayrollCalculator::class);
    }
}
