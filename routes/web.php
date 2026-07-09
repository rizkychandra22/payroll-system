<?php

use App\Http\Controllers\PayrollHistoryPrintController;
use App\Http\Controllers\PayrollPrintController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::middleware('auth')->group(function (): void {
    Route::get('/admin/payrolls/print/pdf', PayrollHistoryPrintController::class)
        ->name('payrolls.history.print.pdf');

    Route::get('/admin/payrolls/{payroll}/print', PayrollPrintController::class)
        ->name('payrolls.print');
});
