<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'nik' => 'EMP001',
                'full_name' => 'Andi Pratama',
                'position' => 'HR Staff',
                'basic_salary' => 4500000,
                'join_date' => '2023-01-10',
            ],
            [
                'nik' => 'EMP002',
                'full_name' => 'Budi Santoso',
                'position' => 'IT Staff',
                'basic_salary' => 5000000,
                'join_date' => '2023-03-15',
            ],
            [
                'nik' => 'EMP003',
                'full_name' => 'Citra Lestari',
                'position' => 'Finance Staff',
                'basic_salary' => 5200000,
                'join_date' => '2022-11-20',
            ],
            [
                'nik' => 'EMP004',
                'full_name' => 'Dedi Firmansyah',
                'position' => 'Supervisor',
                'basic_salary' => 7500000,
                'join_date' => '2021-08-01',
            ],
            [
                'nik' => 'EMP005',
                'full_name' => 'Eka Putri',
                'position' => 'Manager',
                'basic_salary' => 10000000,
                'join_date' => '2020-05-12',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['nik' => $employee['nik']],
                $employee
            );
        }
    }
}