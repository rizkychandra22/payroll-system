<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Models\Employee;
use App\Services\PayrollCalculator;
use App\Services\CurrencyFormatter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')->label('Karyawan')
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

                        $set('basic_salary', CurrencyFormatter::rupiah($defaults['basic_salary']));
                        $set('position', $defaults['position']);
                        $set('allowance_ids', $defaults['allowance_ids']);
                        $set('deduction_ids', $defaults['deduction_ids']);
                        $set('total_allowance', CurrencyFormatter::rupiah($defaults['total_allowance']));
                        $set('total_deduction', CurrencyFormatter::rupiah($defaults['total_deduction']));
                        $set('take_home_pay', CurrencyFormatter::rupiah($defaults['take_home_pay']));
                    })
                    ->required(),

                TextInput::make('position')->label('Jabatan')
                    ->readOnly()
                    ->default('')
                    ->dehydrated(false)
                    ->required(),

                Select::make('payroll_month')->label('Bulan')
                    ->options(static::getMonthOptions())
                    ->default((int) now()->format('n'))
                    ->required(),

                Select::make('payroll_year')->label('Tahun')
                    ->options(static::getYearOptions())
                    ->default((int) now()->format('Y'))
                    ->required(),

                Section::make('Rincian Tunjangan Karyawan')
                    ->schema([
                        CheckboxList::make('allowance_ids')->label('Tunjangan')
                            ->options(app(PayrollCalculator::class)->getAllowanceOptions())
                            ->columns(1)
                            ->disabled()
                            ->dehydrated(false)
                            ->bulkToggleable(false),

                        TextInput::make('total_allowance')->label('Total Tunjangan')
                            ->default(0)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->readOnly()
                            ->required(),
                    ]),

                Section::make('Rincian Potongan Karyawan')
                    ->schema([
                        CheckboxList::make('deduction_ids')->label('Potongan')
                            ->options(app(PayrollCalculator::class)->getDeductionOptions())
                            ->columns(1)
                            ->disabled()
                            ->dehydrated(false)
                            ->bulkToggleable(false),

                        TextInput::make('total_deduction')->label('Total Potongan')
                            ->default(0)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->readOnly()
                            ->required(),
                    ]),

                Section::make('Gaji Pokok Karyawan')
                    ->schema([
                        TextInput::make('basic_salary')->label('Gaji Pokok')
                            ->readOnly()
                            ->default(0)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->required(),
                    ]),

                Section::make('Gaji Bersih Karyawan')
                    ->schema([
                        TextInput::make('take_home_pay')->label('Gaji Bersih')
                            ->default(0)
                            ->formatStateUsing(fn (float | int | string | null $state): string => CurrencyFormatter::rupiah($state))
                            ->readOnly()
                            ->required(),
                    ]),

                DateTimePicker::make('generated_at')->label('Dibuat Pada')
                    ->default(now())
                    ->required()
                    ->hidden(),
            ]);
    }

    public static function getMonthOptions(): array
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

    public static function getYearOptions(): array
    {
        $currentYear = (int) now()->format('Y');
        $years = range($currentYear - 2, $currentYear + 5);

        return array_combine($years, $years);
    }
}
