<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $slip['title'] }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            margin: 0;
            color: #1f2937;
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            line-height: 1.3;
        }

        .document {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .title {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 12px;
            border: 2px solid #1f2937;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .meta-table,
        .detail-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table {
            margin-bottom: 12px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 82px;
        }

        .meta-value {
            font-weight: bold;
        }

        .meta-right {
            width: 46%;
            padding-left: 16px;
        }

        .divider {
            border-top: 2px solid #cbd5e1;
            margin: 10px 0 0;
        }

        .section {
            margin-top: 12px;
            page-break-inside: avoid;
        }

        .section-title {
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .detail-table th,
        .detail-table td,
        .summary-table td {
            padding: 6px 6px;
        }

        .detail-table thead th {
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            font-size: 11px;
            text-align: left;
        }

        .detail-table tbody td {
            border-bottom: 1px dashed #dbe4ee;
        }

        .detail-table tbody tr:last-child td {
            border-bottom: none;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .head-center {
            text-align: center !important;
        }

        .head-right {
            text-align: right !important;
        }

        .money {
            font-weight: bold;
        }

        .summary-block {
            margin-top: 14px;
            border-top: 2px solid #cbd5e1;
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .summary-heading {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-note {
            margin: 0 0 8px;
            color: #6b7280;
            font-size: 10px;
        }

        .summary-table td {
            padding: 4px 0;
        }

        .summary-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .summary-table .highlight td {
            padding-top: 8px;
            border-top: 2px solid #94a3b8;
            font-weight: bold;
        }

        .closing {
            margin-top: 16px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 0.5px;
            font-size: 10px;
        }

        .split-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .split-column {
            vertical-align: top;
        }

        .split-gap {
            width: 14px;
            vertical-align: top;
        }

        .summary-table .label {
            width: 72%;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <h1 class="title">Slip Gaji Karyawan</h1>
            {{-- <div class="badge">{{ $slip['payroll_number'] }}</div> --}}
        </div>

        <table class="meta-table">
            <tr>
                <td width="54%">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Nama:</td>
                            <td class="meta-value">{{ $slip['employee_information']['Nama Karyawan'] }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">NIK:</td>
                            <td class="meta-value">{{ $slip['employee_information']['NIK'] }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Jabatan:</td>
                            <td class="meta-value">{{ $slip['employee_information']['Jabatan'] }}</td>
                        </tr>
                    </table>
                </td>
                <td class="meta-right">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Periode:</td>
                            <td class="meta-value">
                                {{ $slip['employee_information']['Bulan Payroll'] }}
                                {{ $slip['employee_information']['Tahun Payroll'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="meta-label">Dibuat pada:</td>
                            <td class="meta-value">{{ $slip['generated_at_label'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="section">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Komponen</th>
                        <th class="head-center" width="24%">Jenis</th>
                        <th class="head-right" width="24%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="text-center">Pendapatan</td>
                        <td class="text-right money">{{ $slip['highlights']['Gaji Pokok'] }}</td>
                    </tr>
                    <tr>
                        <td>Total Tunjangan</td>
                        <td class="text-center">Benefit</td>
                        <td class="text-right money">{{ $slip['highlights']['Total Tunjangan'] }}</td>
                    </tr>
                    <tr>
                        <td>Total Potongan</td>
                        <td class="text-center">Potongan</td>
                        <td class="text-right money">{{ $slip['highlights']['Total Potongan'] }}</td>
                    </tr>
                    <tr>
                        <td>Gaji Bersih</td>
                        <td class="text-center">Final</td>
                        <td class="text-right money">{{ $slip['highlights']['Take Home Pay'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="split-table">
                <tr>
                    <td class="split-column" width="49%">
                        <h2 class="section-title">Tunjangan</h2>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Nama Tunjangan</th>
                                    <th class="head-right" width="34%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($slip['allowance_items'] as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="text-right money">{{ $item['amount'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Tidak ada tunjangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                    <td class="split-gap" width="2%"></td>
                    <td class="split-column" width="49%">
                        <h2 class="section-title">Potongan</h2>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Nama Potongan</th>
                                    <th class="head-right" width="34%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($slip['deduction_items'] as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="text-right money">{{ $item['amount'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Tidak ada potongan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="summary-block">
            <h2 class="summary-heading">Ringkasan Penggajian</h2>
            <p class="summary-note">
                Gaji Bersih = gaji pokok + total tunjangan - total potongan.
            </p>

            <table class="summary-table">
                <tbody>
                    @foreach ($slip['summary_rows'] as $row)
                        <tr class="{{ ($row['highlight'] ?? false) ? 'highlight' : '' }}">
                            <td class="label">{{ $row['label'] }}</td>
                            <td class="amount">{{ $row['amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="closing">Dokumen slip gaji ini dihasilkan oleh payroll system.</div>
    </div>
</body>
</html>
