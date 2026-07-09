<?php

namespace App\Filament\Resources\EmployeeAllowances\Pages;

use App\Filament\Resources\EmployeeAllowances\EmployeeAllowanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAllowances extends ListRecords
{
    protected static string $resource = EmployeeAllowanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
