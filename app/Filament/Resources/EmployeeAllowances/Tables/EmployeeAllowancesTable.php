<?php

namespace App\Filament\Resources\EmployeeAllowances\Tables;

use App\Models\Employee;
use App\Services\CurrencyFormatter;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeAllowancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Nama Lengkap')
                    ->description(fn (Employee $record): ?string => $record->nik)
                    ->searchable(['full_name', 'nik'])
                    ->sortable(),
                TextColumn::make('position')->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('basic_salary')->label('Gaji Pokok')
                    ->state(fn (Employee $record): string => CurrencyFormatter::rupiah($record->basic_salary))
                    ->sortable(),
                TextColumn::make('allowance_details')->label('Rincian Tunjangan')
                    ->state(fn (Employee $record): array => static::formatAllowanceLines($record))
                    ->listWithLineBreaks(),
                TextColumn::make('allowance_total')->label('Total Tunjangan')
                    ->state(fn (Employee $record): string => CurrencyFormatter::rupiah($record->allowances->sum('amount'))),
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

    protected static function formatAllowanceLines(Employee $record): array
    {
        if ($record->allowances->isEmpty()) {
            return ['-'];
        }

        return $record->allowances
            ->map(fn ($allowance): string => "{$allowance->name}: " . CurrencyFormatter::rupiah($allowance->amount))
            ->all();
    }
}
