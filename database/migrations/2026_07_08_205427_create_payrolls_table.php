<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('payroll_month');
            $table->year('payroll_year');

            // Snapshot data
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('total_allowance', 15, 2)->default(0);
            $table->decimal('total_deduction', 15, 2)->default(0);
            $table->decimal('take_home_pay', 15, 2);

            $table->timestamp('generated_at')->useCurrent();

            
            $table->unique([
                'employee_id',
                'payroll_month',
                'payroll_year',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
