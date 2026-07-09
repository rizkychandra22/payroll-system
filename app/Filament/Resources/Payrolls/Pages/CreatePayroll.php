<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\Concerns\InteractsWithPayrollSelections;
use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    use InteractsWithPayrollSelections;

    protected static string $resource = PayrollResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->preparePayrollDataForPersistence($data);
    }

    protected function afterCreate(): void
    {
        $this->syncPayrollItems();
    }
}
