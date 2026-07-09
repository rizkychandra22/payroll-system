<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use App\Services\PayrollGenerator;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GeneratePayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Periode Penggajian')
                    ->schema([
                        Select::make('payroll_month')->label('Bulan')
                            ->options(PayrollForm::getMonthOptions())
                            ->default((int) now()->format('n'))
                            ->required(),

                        Select::make('payroll_year')->label('Tahun')
                            ->options(PayrollForm::getYearOptions())
                            ->default((int) now()->format('Y'))
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Jabatan')
                    ->description('Pilih jabatan yang penggajian-nya ingin dibuat untuk periode yang dipilih.')
                    ->schema([
                        CheckboxList::make('positions')->label('Jabatan karyawan')
                            ->options(app(PayrollGenerator::class)->getPositionOptions())
                            ->columns(2)
                            ->bulkToggleable()
                            ->required(),
                    ]),
            ]);
    }
}
