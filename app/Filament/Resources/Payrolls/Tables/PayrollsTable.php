<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Models\Payroll;
use App\Services\CurrencyFormatter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->description(fn (Payroll $record): ?string => $record->employee?->nik)
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('employee', function (Builder $employeeQuery) use ($search): Builder {
                                return $employeeQuery
                                    ->where('full_name', 'like', "%{$search}%")
                                    ->orWhere('nik', 'like', "%{$search}%")
                                    ->orWhere('position', 'like', "%{$search}%");
                            });
                        },
                    )
                    ->sortable(),
                TextColumn::make('employee.position')
                    ->label('Position')
                    ->sortable(),
                TextColumn::make('payroll_month')
                    ->label('Payroll Month')
                    ->formatStateUsing(fn (int | string | null $state): ?string => static::getMonthLabel($state))
                    ->sortable(),
                TextColumn::make('payroll_year')
                    ->label('Payroll Year')
                    ->formatStateUsing(fn (int | string | null $state): ?string => filled($state) ? (string) (int) $state : null)
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->basic_salary))
                    ->sortable(),
                TextColumn::make('total_allowance')
                    ->label('Total Allowance')
                    ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->total_allowance))
                    ->sortable(),
                TextColumn::make('total_deduction')
                    ->label('Total Deduction')
                    ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->total_deduction))
                    ->sortable(),
                TextColumn::make('take_home_pay')
                    ->label('Take Home Pay')
                    ->state(fn (Payroll $record): string => CurrencyFormatter::rupiah($record->take_home_pay))
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->label('Generated At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getMonthLabel(int | string | null $month): ?string
    {
        if (blank($month)) {
            return null;
        }

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
        ][(int) $month] ?? null;
    }
}
