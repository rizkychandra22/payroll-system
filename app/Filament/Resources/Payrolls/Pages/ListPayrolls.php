<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('generatePayroll')
                ->label('Generate Slip Gaji')
                ->url(PayrollResource::getUrl('generate')),
            Action::make('printPayroll')
                ->label('Cetak Slip Gaji')
                ->url(PayrollResource::getUrl('print')),
        ];
    }
}
