<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\Payroll;
use App\Services\CurrencyFormatter;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayrollInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Information')
                    ->schema([
                        TextEntry::make('employee.full_name')
                            ->label('Employee'),
                        TextEntry::make('employee.nik')
                            ->label('NIK'),
                        TextEntry::make('employee.position')
                            ->label('Position'),
                        TextEntry::make('payroll_period')
                            ->label('Payroll Period')
                            ->state(fn (Payroll $record): string => static::formatPayrollPeriod($record)),
                        TextEntry::make('generated_at')
                            ->label('Generated At')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Earnings')
                    ->schema([
                        TextEntry::make('basic_salary')
                            ->label('Basic Salary')
                            ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->basic_salary)),
                        TextEntry::make('allowance_items')
                            ->label('Allowances')
                            ->state(fn (Payroll $record): array => static::formatItemsByType($record, 'allowance'))
                            ->listWithLineBreaks(),
                        TextEntry::make('total_allowance')
                            ->label('Total Allowance')
                            ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->total_allowance)),
                    ])
                    ->columns(2),

                Section::make('Deductions')
                    ->schema([
                        TextEntry::make('deduction_items')
                            ->label('Deductions')
                            ->state(fn (Payroll $record): array => static::formatItemsByType($record, 'deduction'))
                            ->listWithLineBreaks(),
                        TextEntry::make('total_deduction')
                            ->label('Total Deduction')
                            ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->total_deduction)),
                    ])
                    ->columns(2),

                Section::make('Summary')
                    ->schema([
                        TextEntry::make('take_home_pay')
                            ->label('Take Home Pay')
                            ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->take_home_pay))
                            ->weight('bold')
                            ->size('lg'),
                    ]),
            ]);
    }

    protected static function formatPayrollPeriod(Payroll $record): string
    {
        $month = PayrollForm::getMonthOptions()[(int) $record->payroll_month] ?? (string) $record->payroll_month;
        $year = (string) (int) $record->payroll_year;

        return "{$month} {$year}";
    }

    protected static function formatItemsByType(Payroll $record, string $type): array
    {
        $items = $record->items
            ->where('type', $type)
            ->map(fn ($item): string => "{$item->name}: " . CurrencyFormatter::rupiah($item->amount))
            ->values()
            ->all();

        return $items !== [] ? $items : ['-'];
    }
}
