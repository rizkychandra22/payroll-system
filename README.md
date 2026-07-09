# Payroll System

Sistem informasi penggajian sederhana berbasis Laravel 12 dan Filament 5 untuk kebutuhan home test Fullstack Developer.

Project ini berfokus pada pengelolaan data master payroll, pengaturan tunjangan dan potongan per karyawan, pembuatan payroll manual, generate payroll massal berdasarkan posisi, riwayat payroll, detail slip gaji, dan cetak slip gaji dalam format PDF.

## Ringkasan Fitur

- CRUD `Employee`
- CRUD `Allowance`
- CRUD `Deduction`
- CRUD `Employee Allowances`
- CRUD `Employee Deductions`
- Pembuatan payroll manual per karyawan
- Generate payroll massal berdasarkan `position`, `month`, dan `year`
- Perhitungan otomatis:
  - `Basic Salary`
  - `Total Allowance`
  - `Total Deduction`
  - `Take Home Pay`
- Riwayat payroll dengan pencarian berdasarkan nama, NIK, dan jabatan
- Detail slip gaji per payroll
- Cetak slip gaji ke PDF langsung dari browser
- Tema admin Filament yang mendukung dark mode dan light mode

## Formula Payroll

```text
Take Home Pay = Basic Salary + Total Allowance - Total Deduction
```

## Stack Teknologi

- PHP 8.2+
- Laravel 12
- Filament 5
- SQLite sebagai database default
- Vite
- Tailwind CSS 4
- barryvdh/laravel-dompdf untuk cetak PDF

## Akun Login Default

Seeder user admin sudah tersedia dan akan dibuat saat menjalankan seeder:

- URL login: `http://127.0.0.1:8000/admin/login`
- Email: `test@example.com`
- Password: `password`

## Data Seed Bawaan

Project ini sudah memiliki sample data untuk mempermudah review:

- 5 data karyawan
- 3 master tunjangan
- 3 master potongan
- relasi tunjangan dan potongan per karyawan

Contoh karyawan seed:

- `EMP001` - Andi Pratama - HR Staff
- `EMP002` - Budi Santoso - IT Staff
- `EMP003` - Citra Lestari - Finance Staff
- `EMP004` - Dedi Firmansyah - Supervisor
- `EMP005` - Eka Putri - Manager

Contoh master tunjangan:

- Tunjangan Makan - `Rp500.000`
- Tunjangan Transport - `Rp300.000`
- Tunjangan Komunikasi - `Rp200.000`

Contoh master potongan:

- BPJS - `Rp150.000`
- Pajak - `Rp250.000`
- Potongan Absen - `Rp100.000`

## Alur Sistem

### 1. Kelola master data

Admin mengelola data berikut dari panel Filament:

- `Employees`
- `Allowances`
- `Deductions`
- `Employee Allowances`
- `Employee Deductions`

### 2. Atur benefit per karyawan

Setiap karyawan memiliki daftar tunjangan dan potongan masing-masing melalui:

- `Employee Allowances`
- `Employee Deductions`

Data inilah yang menjadi sumber saat membuat payroll manual maupun generate payroll massal.

### 3. Buat payroll manual

Pada form `New Payroll`:

- admin memilih karyawan
- `Position` terisi otomatis dan readonly
- `Basic Salary` terisi otomatis dan readonly
- checklist tunjangan dan potongan tampil readonly mengikuti data karyawan
- `Total Allowance` dihitung otomatis
- `Total Deduction` dihitung otomatis
- `Take Home Pay` dihitung otomatis

### 4. Generate payroll massal

Pada halaman `Generate Payroll`:

- admin memilih bulan payroll
- admin memilih tahun payroll
- admin memilih satu atau lebih `position`
- sistem mengambil seluruh karyawan yang memiliki posisi tersebut
- sistem menggunakan tunjangan dan potongan yang sudah terdaftar pada masing-masing karyawan
- sistem mencegah duplikasi payroll untuk karyawan yang sama pada bulan dan tahun yang sama

### 5. Lihat riwayat payroll

Halaman `Payroll History` menampilkan:

- nama karyawan
- NIK
- jabatan
- bulan payroll
- tahun payroll
- basic salary
- total allowance
- total deduction
- take home pay
- generated at

### 6. Detail dan cetak slip gaji

Setiap payroll memiliki halaman detail yang menampilkan:

- nama karyawan
- NIK
- jabatan
- periode payroll
- basic salary
- total tunjangan
- total potongan
- take home pay
- rincian item tunjangan
- rincian item potongan
- ringkasan payroll

Slip gaji juga dapat dibuka sebagai PDF langsung melalui action `Print`.

## Struktur Data Utama

Entitas utama pada project ini:

- `employees`
- `allowances`
- `deductions`
- `employee_allowances`
- `employee_deductions`
- `payrolls`
- `payroll_items`

Keterangan singkat:

- `employees` menyimpan data karyawan
- `allowances` menyimpan master tunjangan
- `deductions` menyimpan master potongan
- `employee_allowances` menyimpan mapping tunjangan per karyawan
- `employee_deductions` menyimpan mapping potongan per karyawan
- `payrolls` menyimpan snapshot hasil payroll per karyawan per periode
- `payroll_items` menyimpan detail item tunjangan dan potongan yang masuk ke payroll

## Struktur Folder Penting

```text
app/
  Filament/
    Resources/
  Http/Controllers/
  Models/
  Providers/
  Services/
database/
  migrations/
  seeders/
resources/
  views/
    filament/
    payrolls/
routes/
```

File penting:

- `app/Services/PayrollCalculator.php`
- `app/Services/PayrollGenerator.php`
- `app/Services/PayrollSlipData.php`
- `app/Http/Controllers/PayrollPrintController.php`
- `resources/views/payrolls/pdf.blade.php`

## Cara Menjalankan Project

### Requirement

- PHP 8.2 atau lebih baru
- Composer
- Node.js 18+ dan npm
- SQLite

### Instalasi

1. Clone repository.
2. Install dependency PHP.
3. Install dependency frontend.
4. Siapkan file environment.
5. Generate app key.
6. Buat file database SQLite jika belum ada.
7. Jalankan migration dan seeder.
8. Build asset frontend.
9. Jalankan server Laravel.

Contoh perintah:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
php artisan serve
```

Untuk Windows PowerShell, jika `touch` tidak tersedia, gunakan:

```powershell
New-Item -ItemType File -Path .\database\database.sqlite -Force
```

Lalu akses:

- Aplikasi: `http://127.0.0.1:8000`
- Admin panel: `http://127.0.0.1:8000/admin`

## Script yang Tersedia

```bash
composer install
composer run dev
php artisan migrate --seed
php artisan serve
npm run dev
npm run build
```

Catatan:

- `composer run dev` akan menjalankan server Laravel, queue listener, log watcher, dan Vite sekaligus.
- Database default project ini adalah SQLite.

## Aturan Bisnis yang Dipakai

- Satu payroll merepresentasikan satu karyawan pada satu bulan dan satu tahun tertentu.
- Payroll massal dibuat berdasarkan posisi yang dipilih admin.
- Tunjangan dan potongan payroll diambil dari relasi karyawan, bukan input nominal manual.
- `Basic Salary` diambil dari data karyawan.
- `Take Home Pay` selalu dihitung otomatis oleh sistem.
- Sistem melewati payroll yang sudah ada untuk periode yang sama saat generate massal.
- Detail item payroll disimpan ke `payroll_items` sebagai snapshot histori.

## Catatan Implementasi

- Riwayat payroll mendukung pencarian nama, NIK, dan jabatan.
- Format nominal menggunakan format Rupiah seperti `Rp1.500.000`.
- Slip gaji dapat dibuka dalam format PDF satu halaman untuk data normal.
- Halaman detail payroll menyesuaikan tampilan dark mode dan light mode.

## Dokumen Tambahan

- [PRD.md](./PRD.md)

## Lisensi

Project ini dibuat untuk kebutuhan home test teknis.
