<?php

namespace App\Filament\Resources\EmployeeDeductions\Pages;

use App\Filament\Resources\EmployeeDeductions\EmployeeDeductionResource;
use App\Models\Employee;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmployeeDeduction extends CreateRecord
{
    protected static string $resource = EmployeeDeductionResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return Employee::query()->findOrFail($data['employee_id']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('edit', ['record' => $this->getRecord()]);
    }
}
