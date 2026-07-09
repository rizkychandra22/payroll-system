# PRD - Payroll System

## 1. Ringkasan Produk

Payroll System adalah aplikasi web sederhana untuk membantu admin HR mengelola data penggajian karyawan. Sistem ini memungkinkan admin menyimpan master data payroll, mengatur tunjangan dan potongan per karyawan, membuat payroll manual, melakukan generate payroll massal, melihat histori payroll, dan mencetak slip gaji dalam format PDF.

Dokumen ini menggambarkan kebutuhan produk berdasarkan implementasi project saat ini.

## 2. Latar Belakang

Pada proses payroll sederhana, admin sering mengelola data karyawan, tunjangan, potongan, dan hasil payroll secara manual. Risiko yang muncul antara lain:

- salah hitung take home pay
- duplikasi payroll pada periode yang sama
- histori payroll sulit ditelusuri
- detail slip gaji tidak terdokumentasi dengan rapi

Sistem ini dibuat untuk menyederhanakan proses tersebut dalam satu panel admin berbasis web.

## 3. Tujuan Produk

### Tujuan utama

- Mempermudah admin HR mengelola payroll karyawan.
- Mengurangi perhitungan manual untuk tunjangan, potongan, dan take home pay.
- Menyediakan histori payroll yang bisa ditelusuri per karyawan dan periode.
- Menyediakan slip gaji yang bisa dilihat dan dicetak dalam bentuk PDF.

### Indikator keberhasilan

- Admin dapat membuat payroll tanpa menghitung nominal secara manual.
- Admin dapat generate payroll banyak karyawan sekaligus untuk satu periode.
- Admin dapat membuka detail payroll historis dan melihat rinciannya.
- Admin dapat mencetak slip gaji langsung dari sistem.

## 4. Pengguna Utama

### Admin HR

Tanggung jawab:

- mengelola data karyawan
- mengelola master tunjangan
- mengelola master potongan
- mengatur tunjangan dan potongan per karyawan
- membuat payroll manual
- generate payroll massal
- melihat histori payroll
- melihat dan mencetak slip gaji

## 5. Scope Produk

### In scope

- Login admin ke panel Filament
- CRUD employee
- CRUD allowance
- CRUD deduction
- CRUD employee allowances
- CRUD employee deductions
- Pembuatan payroll manual
- Generate payroll massal berdasarkan posisi
- Riwayat payroll
- Detail slip gaji
- Cetak slip gaji PDF

### Out of scope

- Self-service portal untuk karyawan
- Approval workflow payroll
- Integrasi bank
- Integrasi absensi
- Perhitungan pajak progresif dinamis
- Role dan permission multi-level
- Export Excel payroll history

## 6. User Stories

### Master data

- Sebagai admin HR, saya ingin menambah dan mengubah data karyawan agar payroll bisa dibuat berdasarkan data yang valid.
- Sebagai admin HR, saya ingin menambah master tunjangan dan potongan agar komponen payroll bisa dipakai ulang.
- Sebagai admin HR, saya ingin menetapkan tunjangan dan potongan untuk setiap karyawan agar payroll masing-masing karyawan sesuai kebijakan.

### Payroll manual

- Sebagai admin HR, saya ingin memilih satu karyawan lalu sistem otomatis menampilkan jabatan, gaji pokok, total tunjangan, total potongan, dan take home pay agar saya tidak perlu menghitung manual.

### Generate payroll

- Sebagai admin HR, saya ingin memilih bulan, tahun, dan posisi lalu generate payroll untuk seluruh karyawan yang sesuai agar proses payroll bulanan lebih cepat.
- Sebagai admin HR, saya ingin sistem melewati payroll yang sudah pernah dibuat pada periode yang sama agar tidak terjadi duplikasi.

### Histori dan slip gaji

- Sebagai admin HR, saya ingin melihat histori payroll agar saya bisa memeriksa payroll yang sudah dibuat.
- Sebagai admin HR, saya ingin membuka detail salah satu payroll agar saya bisa melihat komponen tunjangan dan potongan yang membentuk take home pay.
- Sebagai admin HR, saya ingin mencetak slip gaji dalam format PDF agar bisa dibagikan atau diarsipkan.

## 7. Aturan Bisnis

- Setiap karyawan memiliki `basic_salary`.
- Setiap karyawan dapat memiliki banyak tunjangan.
- Setiap karyawan dapat memiliki banyak potongan.
- Payroll disimpan per `employee`, `month`, dan `year`.
- Payroll manual dan payroll massal sama-sama mengambil data tunjangan dan potongan dari relasi karyawan.
- Tunjangan dan potongan pada payroll disimpan sebagai snapshot di `payroll_items`.
- Formula payroll:

```text
Take Home Pay = Basic Salary + Total Allowance - Total Deduction
```

- Payroll massal tidak membuat data baru jika payroll untuk karyawan dan periode tersebut sudah ada.

## 8. Functional Requirements

### 8.1 Authentication

- Sistem harus menyediakan halaman login admin.
- Hanya user yang login yang dapat mengakses panel admin.

### 8.2 Employee Management

- Sistem harus menyediakan daftar karyawan.
- Sistem harus menyediakan create dan edit data karyawan.
- Data minimal karyawan:
  - NIK
  - nama lengkap
  - jabatan
  - basic salary
  - join date

### 8.3 Allowance Management

- Sistem harus menyediakan daftar master tunjangan.
- Sistem harus menyediakan create dan edit tunjangan.
- Data minimal tunjangan:
  - nama
  - nominal
  - deskripsi

### 8.4 Deduction Management

- Sistem harus menyediakan daftar master potongan.
- Sistem harus menyediakan create dan edit potongan.
- Data minimal potongan:
  - nama
  - nominal
  - deskripsi

### 8.5 Employee Allowance Management

- Sistem harus memungkinkan admin memilih karyawan.
- Sistem harus memungkinkan admin memilih satu atau lebih tunjangan untuk karyawan.
- Sistem harus menampilkan total amount tunjangan per karyawan.
- Sistem harus menampilkan basic salary dan position sebagai informasi pendukung.

### 8.6 Employee Deduction Management

- Sistem harus memungkinkan admin memilih karyawan.
- Sistem harus memungkinkan admin memilih satu atau lebih potongan untuk karyawan.
- Sistem harus menampilkan total amount potongan per karyawan.
- Sistem harus menampilkan basic salary dan position sebagai informasi pendukung.

### 8.7 Manual Payroll Creation

- Sistem harus menyediakan form pembuatan payroll manual.
- Saat admin memilih karyawan:
  - `Position` harus otomatis terisi dan readonly
  - `Basic Salary` harus otomatis terisi dan readonly
  - daftar tunjangan harus tampil readonly sesuai data karyawan
  - daftar potongan harus tampil readonly sesuai data karyawan
  - `Total Allowance` harus dihitung otomatis
  - `Total Deduction` harus dihitung otomatis
  - `Take Home Pay` harus dihitung otomatis

### 8.8 Bulk Payroll Generation

- Sistem harus menyediakan halaman `Generate Payroll`.
- Admin harus dapat memilih:
  - bulan payroll
  - tahun payroll
  - satu atau lebih posisi
- Sistem harus membuat payroll untuk semua karyawan dengan posisi yang dipilih.
- Sistem harus menampilkan notifikasi jumlah payroll yang berhasil dibuat dan jumlah yang dilewati.

### 8.9 Payroll History

- Sistem harus menyediakan daftar histori payroll.
- Sistem harus menampilkan:
  - nama karyawan
  - NIK
  - jabatan
  - bulan payroll
  - tahun payroll
  - basic salary
  - total tunjangan
  - total potongan
  - take home pay
  - generated at
- Sistem harus mendukung pencarian berdasarkan nama, NIK, dan jabatan.

### 8.10 Payroll Detail

- Sistem harus menyediakan halaman detail payroll historis untuk setiap karyawan.
- Halaman detail harus menampilkan:
  - nama karyawan
  - NIK
  - jabatan
  - bulan payroll
  - tahun payroll
  - basic salary
  - total tunjangan
  - total potongan
  - take home pay
  - rincian tunjangan
  - rincian potongan
  - ringkasan payroll

### 8.11 PDF Slip

- Sistem harus menyediakan action `Print`.
- Saat action dibuka, browser harus menampilkan PDF inline.
- PDF harus berisi ringkasan slip gaji satu karyawan untuk satu periode.

## 9. Non-Functional Requirements

- Antarmuka admin harus mudah dipakai dan rapi.
- Sistem harus dapat digunakan di mode terang dan gelap pada halaman detail payroll.
- Format nominal harus konsisten dalam Rupiah.
- PDF slip gaji harus tetap terbaca dengan baik saat dicetak atau disimpan.
- Sistem harus cukup cepat untuk kebutuhan data payroll sederhana skala kecil sampai menengah.

## 10. Entitas dan Relasi

### employees

- id
- nik
- full_name
- position
- basic_salary
- join_date

### allowances

- id
- name
- amount
- description

### deductions

- id
- name
- amount
- description

### employee_allowances

- id
- employee_id
- allowance_id

### employee_deductions

- id
- employee_id
- deduction_id

### payrolls

- id
- employee_id
- payroll_month
- payroll_year
- basic_salary
- total_allowance
- total_deduction
- take_home_pay
- generated_at

### payroll_items

- id
- payroll_id
- type
- name
- amount

## 11. Alur Operasional

### Alur A - setup data awal

1. Admin login ke panel.
2. Admin mengelola employee.
3. Admin mengelola master allowance.
4. Admin mengelola master deduction.
5. Admin mengatur allowance per employee.
6. Admin mengatur deduction per employee.

### Alur B - payroll manual

1. Admin membuka halaman `New Payroll`.
2. Admin memilih karyawan.
3. Sistem mengisi field payroll otomatis berdasarkan data karyawan.
4. Admin menyimpan payroll.
5. Sistem membuat payroll dan detail item payroll.

### Alur C - generate payroll massal

1. Admin membuka halaman `Generate Payroll`.
2. Admin memilih bulan.
3. Admin memilih tahun.
4. Admin memilih posisi yang ingin diproses.
5. Sistem mengambil seluruh karyawan yang cocok.
6. Sistem membuat payroll baru untuk yang belum memiliki payroll pada periode yang sama.
7. Sistem menampilkan hasil generate.

### Alur D - detail dan cetak

1. Admin membuka `Payroll History`.
2. Admin memilih salah satu payroll.
3. Admin melihat detail slip gaji.
4. Admin menekan `Print`.
5. Sistem membuka PDF slip gaji pada tab baru.

## 12. Acceptance Criteria Ringkas

- Admin dapat login ke `/admin`.
- Admin dapat mengelola employee, allowance, deduction, employee allowances, dan employee deductions.
- Payroll manual menghitung total dan take home pay otomatis.
- Generate payroll massal dapat membuat payroll untuk banyak karyawan dalam satu aksi.
- Histori payroll menampilkan data yang benar dan bisa dicari.
- Detail payroll menampilkan rincian allowance dan deduction.
- Slip gaji dapat dibuka sebagai PDF.

## 13. Risiko dan Catatan

- Jika jumlah item tunjangan atau potongan terlalu banyak, PDF bisa bertambah halaman.
- Saat ini sistem belum memiliki pembatasan role selain login admin.
- Belum ada test coverage khusus payroll.

## 14. Pengembangan Selanjutnya

- Tambah validasi unik payroll di level database.
- Tambah filter histori payroll.
- Tambah export Excel atau CSV.
- Tambah role dan permission.
- Tambah approval workflow payroll.
- Tambah dashboard metrik payroll.
