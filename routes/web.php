<?php

use App\Http\Controllers\PayrollPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/admin/payrolls/{payroll}/print', PayrollPrintController::class)
        ->name('payrolls.print');
});
