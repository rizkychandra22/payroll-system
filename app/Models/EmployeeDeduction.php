<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeDeduction extends Pivot
{
    protected $table = 'employee_deductions';

    protected $fillable = [
        'employee_id',
        'deduction_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo(Deduction::class);
    }
}