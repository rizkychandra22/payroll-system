<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\CurrencyFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayrollHistoryPrintController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $month = (int) $request->query('payroll_month', now()->month);
        $year = (int) $request->query('payroll_year', now()->year);
        $positions = array_values(array_filter(array_map(
            static fn (mixed $position): string => trim((string) $position),
            (array) $request->query('positions', []),
        )));

        $query = Payroll::query()
            ->with('employee')
            ->where('payroll_month', $month)
            ->where('payroll_year', $year);

        if ($positions !== []) {
            $query->whereHas('employee', function (Builder $employeeQuery) use ($positions): Builder {
                return $employeeQuery->whereIn('position', $positions);
            });
        }

        $payrolls = $query
            ->orderBy(
                Employee::query()
                    ->select('position')
                    ->whereColumn('employees.id', 'payrolls.employee_id')
                    ->limit(1)
            )
            ->orderBy(
                Employee::query()
                    ->select('full_name')
                    ->whereColumn('employees.id', 'payrolls.employee_id')
                    ->limit(1)
            )
            ->get();

        $summary = [
            'record_count' => $payrolls->count(),
            'basic_salary' => CurrencyFormatter::rupiah($payrolls->sum('basic_salary')),
            'total_allowance' => CurrencyFormatter::rupiah($payrolls->sum('total_allowance')),
            'total_deduction' => CurrencyFormatter::rupiah($payrolls->sum('total_deduction')),
            'take_home_pay' => CurrencyFormatter::rupiah($payrolls->sum('take_home_pay')),
        ];

        $fileName = sprintf(
            'payroll-history-%02d-%04d-%s.pdf',
            $month,
            $year,
            now()->format('His'),
        );

        return Pdf::loadView('payrolls.history-pdf', [
            'payrolls' => $payrolls,
            'month' => $month,
            'year' => $year,
            'monthLabel' => PayrollForm::getMonthOptions()[$month] ?? (string) $month,
            'positions' => $positions,
            'printedAt' => now()->format('d/m/Y H:i:s'),
            'summary' => $summary,
        ])
            ->setPaper('a4', 'landscape')
            ->download($fileName);
    }
}
