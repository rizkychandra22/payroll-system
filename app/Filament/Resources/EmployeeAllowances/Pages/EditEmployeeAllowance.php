<?php

namespace App\Filament\Resources\EmployeeAllowances\Pages;

use App\Filament\Resources\EmployeeAllowances\EmployeeAllowanceResource;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAllowance extends EditRecord
{
    protected static string $resource = EmployeeAllowanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
