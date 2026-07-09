<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'full_name',
        'nik',
        'position',
        'basic_salary',
        'join_date',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'join_date' => 'date',
    ];

    public function allowances(): BelongsToMany
    {
        return $this->belongsToMany(
            Allowance::class,
            'employee_allowances'
        )
            ->using(EmployeeAllowance::class)
            ->withTimestamps();
    }

    public function deductions(): BelongsToMany
    {
        return $this->belongsToMany(
            Deduction::class,
            'employee_deductions'
        )
            ->using(EmployeeDeduction::class)
            ->withTimestamps();
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}