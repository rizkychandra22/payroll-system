<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Services\PayrollSlipData;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    protected string $view = 'filament.resources.payrolls.pages.view-payroll';

    protected ?array $slipData = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')->label('Cetak Slip Gaji')
                ->icon(Heroicon::OutlinedPrinter)
                ->url(fn (): string => route('payrolls.print', $this->getRecord()), shouldOpenInNewTab: true),
            EditAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return $this->getSlipData()['title'];
    }

    public function getBreadcrumb(): string
    {
        return 'Detail Slip Gaji';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Detail riwayat penggajian karyawan dengan rincian tunjangan, potongan, dan gaji bersih.';
    }

    public function getEmployeeInformation(): array
    {
        return $this->getSlipData()['employee_information'];
    }

    public function getPayrollHighlights(): array
    {
        return $this->getSlipData()['highlights'];
    }

    public function getAllowanceItems(): array
    {
        return $this->getSlipData()['allowance_items'];
    }

    public function getDeductionItems(): array
    {
        return $this->getSlipData()['deduction_items'];
    }

    public function getSummaryRows(): array
    {
        return $this->getSlipData()['summary_rows'];
    }

    public function getPayrollMonthLabel(): string
    {
        return $this->getSlipData()['payroll_month_label'];
    }

    public function getGeneratedAtLabel(): string
    {
        return $this->getSlipData()['generated_at_label'];
    }

    protected function getSlipData(): array
    {
        return $this->slipData ??= app(PayrollSlipData::class)->build($this->getRecord());
    }
}
