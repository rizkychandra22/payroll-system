<?php

namespace Database\Seeders;

use App\Models\Allowance;
use Illuminate\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    public function run(): void
    {
        $allowances = [
            [
                'name' => 'Tunjangan Makan',
                'amount' => 500000,
                'description' => 'Tunjangan makan bulanan karyawan.',
            ],
            [
                'name' => 'Tunjangan Transport',
                'amount' => 300000,
                'description' => 'Tunjangan transportasi bulanan karyawan.',
            ],
            [
                'name' => 'Tunjangan Komunikasi',
                'amount' => 200000,
                'description' => 'Tunjangan komunikasi dan pulsa.',
            ],
        ];

        foreach ($allowances as $allowance) {
            Allowance::updateOrCreate(
                ['name' => $allowance['name']],
                $allowance
            );
        }
    }
}