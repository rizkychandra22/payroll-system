<?php

namespace App\Services;

use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Models\Payroll;

class PayrollSlipData
{
    public function build(Payroll $payroll): array
    {
        $payroll->loadMissing(['employee', 'items']);

        $basicSalary = (float) $payroll->basic_salary;
        $totalAllowance = (float) $payroll->total_allowance;
        $totalDeduction = (float) $payroll->total_deduction;

        return [
            'title' => filled($payroll->employee?->full_name)
                ? "Slip Gaji {$payroll->employee->full_name}"
                : 'Slip Gaji',
            'payroll_number' => sprintf(
                'PRL-%s-%04d',
                str_pad((string) $payroll->payroll_month, 2, '0', STR_PAD_LEFT) . $payroll->payroll_year,
                $payroll->getKey(),
            ),
            'employee_information' => [
                'Nama Karyawan' => $payroll->employee?->full_name ?? '-',
                'NIK' => $payroll->employee?->nik ?? '-',
                'Jabatan' => $payroll->employee?->position ?? '-',
                'Bulan Payroll' => $this->getPayrollMonthLabel($payroll),
                'Tahun Payroll' => (string) (int) $payroll->payroll_year,
            ],
            'highlights' => [
                'Gaji Pokok' => CurrencyFormatter::rupiah($payroll->basic_salary),
                'Total Tunjangan' => CurrencyFormatter::rupiah($payroll->total_allowance),
                'Total Potongan' => CurrencyFormatter::rupiah($payroll->total_deduction),
                'Take Home Pay' => CurrencyFormatter::rupiah($payroll->take_home_pay),
            ],
            'allowance_items' => $payroll->items
                ->where('type', 'allowance')
                ->map(fn ($item): array => [
                    'name' => $item->name,
                    'amount' => CurrencyFormatter::rupiah($item->amount),
                ])
                ->values()
                ->all(),
            'deduction_items' => $payroll->items
                ->where('type', 'deduction')
                ->map(fn ($item): array => [
                    'name' => $item->name,
                    'amount' => CurrencyFormatter::rupiah($item->amount),
                ])
                ->values()
                ->all(),
            'summary_rows' => [
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
                    'amount' => CurrencyFormatter::rupiah($payroll->take_home_pay),
                    'highlight' => true,
                ],
            ],
            'payroll_month_label' => $this->getPayrollMonthLabel($payroll),
            'generated_at_label' => $payroll->generated_at?->format('d/m/Y H:i:s') ?? '-',
        ];
    }

    protected function getPayrollMonthLabel(Payroll $payroll): string
    {
        return PayrollForm::getMonthOptions()[(int) $payroll->payroll_month] ?? (string) $payroll->payroll_month;
    }
}
