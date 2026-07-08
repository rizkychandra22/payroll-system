<?php

namespace Database\Seeders;

use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeBenefitSeeder extends Seeder
{
    public function run(): void
    {
        $meal = Allowance::where('name', 'Tunjangan Makan')->first();
        $transport = Allowance::where('name', 'Tunjangan Transport')->first();
        $communication = Allowance::where('name', 'Tunjangan Komunikasi')->first();

        $bpjs = Deduction::where('name', 'BPJS')->first();
        $tax = Deduction::where('name', 'Pajak')->first();
        $absence = Deduction::where('name', 'Potongan Absen')->first();

        $employees = Employee::all();

        foreach ($employees as $employee) {
            $employee->allowances()->syncWithoutDetaching([
                $meal?->id,
                $transport?->id,
            ]);

            $employee->deductions()->syncWithoutDetaching([
                $bpjs?->id,
            ]);
        }

        Employee::where('nik', 'EMP003')->first()?->allowances()->syncWithoutDetaching([
            $communication?->id,
        ]);

        Employee::where('nik', 'EMP004')->first()?->deductions()->syncWithoutDetaching([
            $tax?->id,
        ]);

        Employee::where('nik', 'EMP005')->first()?->allowances()->syncWithoutDetaching([
            $communication?->id,
        ]);

        Employee::where('nik', 'EMP005')->first()?->deductions()->syncWithoutDetaching([
            $tax?->id,
            $absence?->id,
        ]);
    }
}