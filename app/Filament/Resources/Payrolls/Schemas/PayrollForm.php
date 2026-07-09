<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'full_name')
                    ->required(),
                TextInput::make('payroll_month')
                    ->required()
                    ->numeric(),
                TextInput::make('payroll_year')
                    ->required()
                    ->numeric(),
                TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),
                TextInput::make('total_allowance')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_deduction')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('take_home_pay')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('generated_at')
                    ->required(),
            ]);
    }
}
