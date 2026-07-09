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
        $allowances = Allowance::all();
        $deductions = Deduction::all();

        if ($allowances->isEmpty() || $deductions->isEmpty()) {
            return;
        }

        $allowanceIds = $allowances->pluck('id')->all();
        $deductionIds = $deductions->pluck('id')->all();

        $employees = Employee::all();

        foreach ($employees as $employee) {
            $selectedAllowances = $this->pickRandomItems($allowanceIds, rand(1, min(3, count($allowanceIds))));
            $selectedDeductions = $this->pickRandomItems($deductionIds, rand(1, min(2, count($deductionIds))));

            $employee->allowances()->syncWithoutDetaching($selectedAllowances);
            $employee->deductions()->syncWithoutDetaching($selectedDeductions);
        }
    }

    private function pickRandomItems(array $items, int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        $shuffled = $items;
        shuffle($shuffled);

        return array_slice($shuffled, 0, $count);
    }
}