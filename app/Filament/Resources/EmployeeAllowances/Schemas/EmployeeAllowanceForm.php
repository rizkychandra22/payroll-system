<?php

namespace App\Filament\Resources\EmployeeAllowances\Schemas;

use App\Models\Allowance;
use App\Models\Employee;
use App\Services\CurrencyFormatter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EmployeeAllowanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jabatan Karyawan')
                    ->schema([
                        TextInput::make('position')->label('Jabatan')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                    ]),

                Section::make('Gaji Pokok Karyawan')
                    ->schema([
                        TextInput::make('basic_salary')->label('Gaji Pokok')
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->required(),
                    ]),
                    
                Section::make('Informasi Karyawan')
                    ->schema([
                        Select::make('employee_id')->label('Pilih Karyawan')
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
                        TextInput::make('full_name')->label('Nama Lengkap')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                        TextInput::make('nik')->label('NIK')
                            ->readOnly()
                            ->dehydrated(false)
                            ->required(),
                    ]),

                Section::make('Tunjangan')
                    ->schema([
                        CheckboxList::make('allowances')
                            ->relationship('allowances')
                            ->getOptionLabelFromRecordUsing(function (Allowance $record): string {
                                return "{$record->name} - " . CurrencyFormatter::rupiah($record->amount);
                            })
                            ->columns(1)
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
