<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Services\PayrollSlipData;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class PayrollPrintController extends Controller
{
    public function __invoke(Payroll $payroll, PayrollSlipData $payrollSlipData): Response
    {
        $slip = $payrollSlipData->build($payroll);
        $fileName = ($slip['title'] ?? 'slip-gaji') . '.pdf';

        return Pdf::loadView('payrolls.pdf', [
            'payroll' => $payroll,
            'slip' => $slip,
        ])
            ->setPaper('a4')
            ->stream($fileName);
    }
}
