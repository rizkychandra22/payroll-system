<?php

namespace App\Filament\Resources\EmployeeAllowances\Pages;

use App\Filament\Resources\EmployeeAllowances\EmployeeAllowanceResource;
use App\Models\Employee;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmployeeAllowance extends CreateRecord
{
    protected static string $resource = EmployeeAllowanceResource::class;

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
