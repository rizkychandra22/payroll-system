<?php

namespace Database\Seeders;

use App\Models\Deduction;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    public function run(): void
    {
        $deductions = [
            [
                'name' => 'BPJS',
                'amount' => 150000,
                'description' => 'Potongan BPJS bulanan.',
            ],
            [
                'name' => 'Pajak',
                'amount' => 250000,
                'description' => 'Potongan pajak penghasilan.',
            ],
            [
                'name' => 'Potongan Absen',
                'amount' => 100000,
                'description' => 'Potongan karena ketidakhadiran.',
            ],
        ];

        foreach ($deductions as $deduction) {
            Deduction::updateOrCreate(
                ['name' => $deduction['name']],
                $deduction
            );
        }
    }
}