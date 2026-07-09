<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\Employee;
use App\Services\PayrollCalculator;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                        $defaults = app(PayrollCalculator::class)->getEmployeeDefaults($state);

                        $set('basic_salary', $defaults['basic_salary']);
                        $set('allowance_ids', $defaults['allowance_ids']);
                        $set('deduction_ids', $defaults['deduction_ids']);
                        $set('total_allowance', $defaults['total_allowance']);
                        $set('total_deduction', $defaults['total_deduction']);
                        $set('take_home_pay', $defaults['take_home_pay']);
                    })
                    ->required(),

                Select::make('payroll_month')
                    ->label('Payroll Month')
                    ->options(static::getMonthOptions())
                    ->default((int) now()->format('n'))
                    ->required(),

                Select::make('payroll_year')
                    ->label('Payroll Year')
                    ->options(static::getYearOptions())
                    ->default((int) now()->format('Y'))
                    ->required(),

                TextInput::make('basic_salary')
                    ->label('Basic Salary')
                    ->prefix('Rp')
                    ->readOnly()
                    ->default(0)
                    ->required(),

                Section::make('Allowances')
                    ->schema([
                        CheckboxList::make('allowance_ids')
                            ->label('Allowance')
                            ->options(app(PayrollCalculator::class)->getAllowanceOptions())
                            ->columns(1)
                            ->disabled(fn (Get $get): bool => blank($get('employee_id')))
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::syncCalculatedFields($get, $set);
                            }),

                        TextInput::make('total_allowance')
                            ->label('Total Allowance')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->required(),
                    ]),

                Section::make('Deductions')
                    ->schema([
                        CheckboxList::make('deduction_ids')
                            ->label('Deduction')
                            ->options(app(PayrollCalculator::class)->getDeductionOptions())
                            ->columns(1)
                            ->disabled(fn (Get $get): bool => blank($get('employee_id')))
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::syncCalculatedFields($get, $set);
                            }),

                        TextInput::make('total_deduction')
                            ->label('Total Deduction')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->required(),
                    ]),

                Section::make('Take Home Pay')
                    ->schema([
                        TextInput::make('take_home_pay')
                            ->label('Take Home Pay')
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly()
                            ->required(),
                    ]),

                DateTimePicker::make('generated_at')
                    ->label('Generated At')
                    ->default(now())
                    ->required(),
            ]);
    }

    protected static function syncCalculatedFields(Get $get, Set $set): void
    {
        $totals = app(PayrollCalculator::class)->calculate(
            basicSalary: $get('basic_salary'),
            allowanceIds: $get('allowance_ids') ?? [],
            deductionIds: $get('deduction_ids') ?? [],
        );

        $set('total_allowance', $totals['total_allowance']);
        $set('total_deduction', $totals['total_deduction']);
        $set('take_home_pay', $totals['take_home_pay']);
    }

    protected static function getMonthOptions(): array
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }

    protected static function getYearOptions(): array
    {
        $currentYear = (int) now()->format('Y');
        $years = range($currentYear - 2, $currentYear + 5);

        return array_combine($years, $years);
    }
}
