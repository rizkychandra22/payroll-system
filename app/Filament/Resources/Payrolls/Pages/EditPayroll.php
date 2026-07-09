<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\Concerns\InteractsWithPayrollSelections;
use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPayroll extends EditRecord
{
    use InteractsWithPayrollSelections;

    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $selectedBenefitIds = $this->getPayrollCalculator()->getSelectedBenefitIdsFromPayroll($this->getRecord());

        return [
            ...$data,
            'position' => $this->getRecord()->employee?->position ?? '',
            'allowance_ids' => $selectedBenefitIds['allowance_ids'],
            'deduction_ids' => $selectedBenefitIds['deduction_ids'],
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->preparePayrollDataForPersistence($data);
    }

    protected function afterSave(): void
    {
        $this->syncPayrollItems();
    }
}
