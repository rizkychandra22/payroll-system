<?php

namespace App\Filament\Resources\EmployeeDeductions\Schemas;

use App\Models\Deduction;
use App\Models\Employee;
use App\Services\CurrencyFormatter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EmployeeDeductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Position')
                    ->schema([
                        TextInput::make('position')
                            ->label('Position')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                    ]),

                Section::make('Employee Basic Salary')
                    ->schema([
                        TextInput::make('basic_salary')
                            ->label('Basic Salary')
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->required(),
                    ]),
                    
                Section::make('Employee Information')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->options(
                                Employee::query()
                                    ->orderBy('full_name')
                                    ->get()
                                    ->mapWithKeys(fn (Employee $employee): array => [
                                        $employee->id => "{$employee->full_name} - {$employee->nik}",
                                    ])
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, int | string | null $state): void {
                                $employee = Employee::query()->find($state);

                                $set('full_name', $employee?->full_name ?? '');
                                $set('nik', $employee?->nik ?? '');
                                $set('position', $employee?->position ?? '');
                                $set('basic_salary', CurrencyFormatter::rupiah($employee?->basic_salary ?? 0));
                            })
                            ->required()
                            ->visibleOn('create'),
                        TextInput::make('full_name')
                            ->label('Employee')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                    ]),
                Section::make('Deductions')
                    ->schema([
                        CheckboxList::make('deductions')
                            ->relationship('deductions')
                            ->getOptionLabelFromRecordUsing(function (Deduction $record): string {
                                return "{$record->name} - " . CurrencyFormatter::rupiah($record->amount);
                            })
                            ->columns(1)
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
