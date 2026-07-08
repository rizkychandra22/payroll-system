<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeAllowance extends Pivot
{
    protected $table = 'employee_allowances';

    protected $fillable = [
        'employee_id',
        'allowance_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function allowance(): BelongsTo
    {
        return $this->belongsTo(Allowance::class);
    }
}