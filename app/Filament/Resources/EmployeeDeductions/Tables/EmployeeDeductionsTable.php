<?php

namespace App\Filament\Resources\EmployeeDeductions\Tables;

use App\Models\Employee;
use App\Services\CurrencyFormatter;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeDeductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->description(fn (Employee $record): ?string => $record->nik)
                    ->searchable(['full_name', 'nik'])
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->state(fn (Employee $record): string => CurrencyFormatter::rupiah($record->basic_salary))
                    ->sortable(),
                TextColumn::make('deduction_details')
                    ->label('Deductions')
                    ->state(fn (Employee $record): array => static::formatDeductionLines($record))
                    ->listWithLineBreaks(),
                TextColumn::make('deduction_total')
                    ->label('Total Amount')
                    ->state(fn (Employee $record): string => CurrencyFormatter::rupiah($record->deductions->sum('amount'))),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    protected static function formatDeductionLines(Employee $record): array
    {
        if ($record->deductions->isEmpty()) {
            return ['-'];
        }

        return $record->deductions
            ->map(fn ($deduction): string => "{$deduction->name}: " . CurrencyFormatter::rupiah($deduction->amount))
            ->all();
    }
}
