<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_month',
        'payroll_year',
        'basic_salary',
        'total_allowance',
        'total_deduction',
        'take_home_pay',
        'generated_at',
    ];

    protected $casts = [
        'payroll_month' => 'integer',
        'payroll_year' => 'integer',
        'basic_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'take_home_pay' => 'decimal:2',
        'generated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }
}