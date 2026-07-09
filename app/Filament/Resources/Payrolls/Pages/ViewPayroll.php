<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Resources\Payrolls\PayrollResource;
use App\Models\Payroll;
use App\Services\CurrencyFormatter;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    protected string $view = 'filament.resources.payrolls.pages.view-payroll';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        $employeeName = $this->getRecord()->employee?->full_name;

        return filled($employeeName) ? "Slip Gaji {$employeeName}" : 'Slip Gaji';
    }

    public function getBreadcrumb(): string
    {
        return 'Detail Slip Gaji';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Detail payroll historis karyawan dengan rincian tunjangan, potongan, dan take home pay.';
    }

    public function getEmployeeInformation(): array
    {
        $record = $this->getPayrollRecord();

        return [
            'Nama Karyawan' => $record->employee?->full_name ?? '-',
            'NIK' => $record->employee?->nik ?? '-',
            'Jabatan' => $record->employee?->position ?? '-',
            'Bulan Payroll' => $this->getPayrollMonthLabel(),
            'Tahun Payroll' => (string) (int) $record->payroll_year,
        ];
    }

    public function getPayrollHighlights(): array
    {
        $record = $this->getPayrollRecord();

        return [
            'Gaji Pokok' => CurrencyFormatter::rupiah($record->basic_salary),
            'Total Tunjangan' => CurrencyFormatter::rupiah($record->total_allowance),
            'Total Potongan' => CurrencyFormatter::rupiah($record->total_deduction),
            'Take Home Pay' => CurrencyFormatter::rupiah($record->take_home_pay),
        ];
    }

    public function getAllowanceItems(): array
    {
        return $this->getPayrollRecord()->items
            ->where('type', 'allowance')
            ->map(fn ($item): array => [
                'name' => $item->name,
                'amount' => CurrencyFormatter::rupiah($item->amount),
            ])
            ->values()
            ->all();
    }

    public function getDeductionItems(): array
    {
        return $this->getPayrollRecord()->items
            ->where('type', 'deduction')
            ->map(fn ($item): array => [
                'name' => $item->name,
                'amount' => CurrencyFormatter::rupiah($item->amount),
            ])
            ->values()
            ->all();
    }

    public function getSummaryRows(): array
    {
        $record = $this->getPayrollRecord();
        $basicSalary = (float) $record->basic_salary;
        $totalAllowance = (float) $record->total_allowance;
        $totalDeduction = (float) $record->total_deduction;

        return [
            [
                'label' => 'Gaji Pokok',
                'amount' => CurrencyFormatter::rupiah($basicSalary),
            ],
            [
                'label' => 'Gaji + Tunjangan',
                'amount' => CurrencyFormatter::rupiah($basicSalary + $totalAllowance),
            ],
            [
                'label' => 'Gaji Setelah Potongan',
                'amount' => CurrencyFormatter::rupiah($basicSalary - $totalDeduction),
            ],
            [
                'label' => 'Take Home Pay',
                'amount' => CurrencyFormatter::rupiah($record->take_home_pay),
                'highlight' => true,
            ],
        ];
    }

    public function getPayrollMonthLabel(): string
    {
        $record = $this->getPayrollRecord();

        return PayrollForm::getMonthOptions()[(int) $record->payroll_month] ?? (string) $record->payroll_month;
    }

    public function getGeneratedAtLabel(): string
    {
        return $this->getPayrollRecord()->generated_at?->format('d/m/Y H:i:s') ?? '-';
    }

    protected function getPayrollRecord(): Payroll
    {
        /** @var Payroll $record */
        $record = $this->getRecord()->loadMissing(['employee', 'items']);

        return $record;
    }
}
