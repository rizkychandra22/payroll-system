<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['position' => 'HR Staff', 'prefix' => 'HR'],
            ['position' => 'IT Staff', 'prefix' => 'IT'],
            ['position' => 'Finance Staff', 'prefix' => 'FN'],
            ['position' => 'Supervisor', 'prefix' => 'SP'],
            ['position' => 'Manager', 'prefix' => 'MG'],
        ];

        $firstNames = ['Andi', 'Budi', 'Citra', 'Dedi', 'Eka', 'Fajar', 'Gita', 'Hana', 'Indra', 'Joko', 'Kiki', 'Lina', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rina', 'Sari', 'Tono', 'Vina'];
        $lastNames = ['Pratama', 'Santoso', 'Lestari', 'Firmansyah', 'Putri', 'Wibowo', 'Nugroho', 'Sari', 'Wijaya', 'Hidayat', 'Puspita', 'Ramadhan', 'Azzahra', 'Kurniawan', 'Saputra', 'Ningsih', 'Aditya', 'Maharani', 'Gunawan', 'Dewi'];

        $counter = 1;

        foreach ($positions as $positionData) {
            for ($i = 1; $i <= 10; $i++) {
                $nik = sprintf('%s%03d', $positionData['prefix'], $counter);

                Employee::updateOrCreate(
                    ['nik' => $nik],
                    [
                        'nik' => $nik,
                        'full_name' => $this->makeName($firstNames, $lastNames),
                        'position' => $positionData['position'],
                        'basic_salary' => $this->basicSalaryFor($positionData['position']),
                        'join_date' => now()->subDays(rand(365, 2000))->toDateString(),
                    ]
                );

                $counter++;
            }
        }
    }

    private function makeName(array $firstNames, array $lastNames): string
    {
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function basicSalaryFor(string $position): int
    {
        return match ($position) {
            'HR Staff' => 5500000,
            'IT Staff' => 5000000,
            'Finance Staff' => 4500000,
            'Supervisor' => 4000000,
            'Manager' => 6000000,
            default => 5000000,
        };
    }
}