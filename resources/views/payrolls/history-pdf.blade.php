<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payroll History</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 11mm;
        }

        body {
            margin: 0;
            color: #1f2937;
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            line-height: 1.35;
        }

        .header {
            margin-bottom: 12px;
            text-align: center;
        }

        .title {
            margin: 0;
            font-size: 19px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .subtitle {
            margin-top: 4px;
            color: #475569;
            font-size: 10px;
        }

        .meta-table,
        .summary-table,
        .history-table {
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
            width: 92px;
        }

        .meta-value {
            font-weight: bold;
        }

        .meta-right {
            width: 38%;
        }

        .summary-wrap {
            margin-bottom: 12px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
        }

        .summary-title {
            margin: 0 0 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-table td {
            padding: 3px 0;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .history-table th,
        .history-table td {
            padding: 7px 8px;
            vertical-align: top;
        }

        .history-table {
            table-layout: fixed;
        }

        .history-table thead th {
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            text-align: left;
            font-size: 10px;
            white-space: nowrap;
        }

        .history-table thead th.center,
        .history-table tbody td.center {
            text-align: center;
        }

        .history-table thead th.right,
        .history-table tbody td.right {
            text-align: right;
        }

        .history-table tbody td {
            border-bottom: 1px dashed #dbe4ee;
        }

        .history-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .nowrap {
            white-space: nowrap;
        }

        .employee-name {
            display: block;
            font-weight: bold;
        }

        .employee-nik {
            display: block;
            margin-top: 2px;
            color: #64748b;
            font-size: 9px;
        }

        .generated-date,
        .generated-time {
            display: block;
        }

        .generated-time {
            color: #475569;
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            color: #64748b;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Payroll History</h1>
        <div class="subtitle">Ringkasan histori payroll karyawan</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="62%">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Periode:</td>
                        <td class="meta-value">{{ $monthLabel }} {{ $year }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Positions:</td>
                        <td class="meta-value">{{ $positions !== [] ? implode(', ', $positions) : 'All Positions' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Total Data:</td>
                        <td class="meta-value">{{ $summary['record_count'] }}</td>
                    </tr>
                </table>
            </td>
            <td class="meta-right">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Printed At:</td>
                        <td class="meta-value">{{ $printedAt }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Filter Type:</td>
                        <td class="meta-value">Month, Year, and Positions</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="summary-wrap">
        <h2 class="summary-title">Summary</h2>
        <table class="summary-table">
            <tr>
                <td>Total Basic Salary</td>
                <td>{{ $summary['basic_salary'] }}</td>
            </tr>
            <tr>
                <td>Total Allowance</td>
                <td>{{ $summary['total_allowance'] }}</td>
            </tr>
            <tr>
                <td>Total Deduction</td>
                <td>{{ $summary['total_deduction'] }}</td>
            </tr>
            <tr>
                <td>Total Take Home Pay</td>
                <td>{{ $summary['take_home_pay'] }}</td>
            </tr>
        </table>
    </div>

    <table class="history-table">
        <colgroup>
            <col style="width: 18%;">
            <col style="width: 12%;">
            <col style="width: 8%;">
            <col style="width: 7%;">
            <col style="width: 11%;">
            <col style="width: 11%;">
            <col style="width: 11%;">
            <col style="width: 11%;">
            <col style="width: 11%;">
        </colgroup>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Position</th>
                <th class="center">Month</th>
                <th class="center">Year</th>
                <th class="right">Basic Salary</th>
                <th class="right">Allowance</th>
                <th class="right">Deduction</th>
                <th class="right">Take Home Pay</th>
                <th>Generated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payrolls as $payroll)
                <tr>
                    <td>
                        <span class="employee-name">{{ $payroll->employee?->full_name ?? '-' }}</span>
                        <span class="employee-nik">{{ $payroll->employee?->nik ?? '-' }}</span>
                    </td>
                    <td>{{ $payroll->employee?->position ?? '-' }}</td>
                    <td class="center">
                        {{
                            [
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December',
                            ][(int) $payroll->payroll_month] ?? $payroll->payroll_month
                        }}
                    </td>
                    <td class="center nowrap">{{ (int) $payroll->payroll_year }}</td>
                    <td class="right nowrap">{{ \App\Services\CurrencyFormatter::rupiah($payroll->basic_salary) }}</td>
                    <td class="right nowrap">{{ \App\Services\CurrencyFormatter::rupiah($payroll->total_allowance) }}</td>
                    <td class="right nowrap">{{ \App\Services\CurrencyFormatter::rupiah($payroll->total_deduction) }}</td>
                    <td class="right nowrap">{{ \App\Services\CurrencyFormatter::rupiah($payroll->take_home_pay) }}</td>
                    <td>
                        <span class="generated-date">{{ optional($payroll->generated_at)->format('d/m/Y') }}</span>
                        <span class="generated-time">{{ optional($payroll->generated_at)->format('H:i:s') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="center">Tidak ada data payroll untuk dicetak.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen payroll history ini dihasilkan oleh sistem payroll.</div>
</body>
</html>
