<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('nik')
                    ->required(),
                TextInput::make('position')
                    ->required(),
                TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),
                DatePicker::make('join_date')
                    ->required(),
            ]);
    }
}
