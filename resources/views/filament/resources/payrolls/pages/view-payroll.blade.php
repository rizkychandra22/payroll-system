<x-filament-panels::page>
    @php
        $employeeInformation = $this->getEmployeeInformation();
        $highlights = $this->getPayrollHighlights();
        $allowanceItems = $this->getAllowanceItems();
        $deductionItems = $this->getDeductionItems();
        $summaryRows = $this->getSummaryRows();
    @endphp

    <div class="mx-auto max-w-6xl">
        <section class="relative overflow-hidden rounded-[2rem] bg-stone-50 text-slate-900 shadow-[0_30px_90px_rgba(15,23,42,0.18)] ring-1 ring-stone-200 dark:bg-slate-950 dark:text-white dark:shadow-[0_30px_90px_rgba(2,6,23,0.7)] dark:ring-white/10">
            <div class="absolute inset-x-0 top-0 h-3 bg-gradient-to-r from-amber-500 via-orange-400 to-rose-500"></div>
            <div class="absolute -right-16 top-14 h-44 w-44 rounded-full bg-amber-300/20 blur-3xl dark:bg-amber-400/10"></div>
            <div class="absolute -left-12 bottom-20 h-36 w-36 rounded-full bg-sky-200/20 blur-3xl dark:bg-sky-400/10"></div>

            <div class="relative px-6 pb-8 pt-10 sm:px-8 lg:px-10">
                <div class="flex flex-col gap-6 border-b border-dashed border-stone-300 pb-8 dark:border-white/10 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-100 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-amber-900 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-200">
                            Slip Gaji
                        </div>

                        <div class="space-y-2">
                            <h2 class="max-w-2xl text-3xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                                {{ $employeeInformation['Nama Karyawan'] }}
                            </h2>

                            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600 dark:text-slate-300">
                                <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-medium uppercase tracking-[0.18em] text-white dark:bg-white dark:text-slate-950">
                                    {{ $employeeInformation['Jabatan'] }}
                                </span>
                                <span>
                                    Gaji {{ $employeeInformation['Bulan Payroll'] }} {{ $employeeInformation['Tahun Payroll'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[26rem]">
                        <div class="rounded-[1.5rem] bg-slate-900 px-5 py-5 text-white shadow-xl dark:bg-gradient-to-br dark:from-amber-400 dark:via-orange-400 dark:to-rose-500 dark:text-slate-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-900/70">
                                Gaji Bersih
                            </p>

                            <p class="mt-3 text-3xl font-semibold tracking-tight text-white dark:text-slate-950">
                                {{ $highlights['Take Home Pay'] }}
                            </p>

                            <p class="mt-3 text-sm text-slate-300 dark:text-slate-900/80">
                                Gaji Pokok + Total Tunjangan - Total Potongan
                            </p>
                        </div>

                        <div class="rounded-[1.5rem] border border-stone-200 bg-white px-5 py-5 shadow-sm dark:border-white/10 dark:bg-slate-900/80">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">
                                Dibuat pada

                            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">
                                {{ $this->getGeneratedAtLabel() }}
                            </p>

                            <div class="mt-4 h-px bg-gradient-to-r from-transparent via-stone-300 to-transparent dark:via-white/10"></div>

                            <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">
                                NIK {{ $employeeInformation['NIK'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                    <div class="space-y-6">
                        <div class="rounded-[1.75rem] border border-stone-200 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-slate-900/80">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">
                                        Informasi Penggajian
                                    </p>
                                    <h3 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">
                                        Detail Karyawan
                                    </h3>
                                </div>

                                <div class="hidden rounded-full bg-slate-100 px-4 py-2 text-xs font-medium text-slate-600 dark:bg-white/5 dark:text-slate-300 sm:block">
                                    Gaji {{ $employeeInformation['Bulan Payroll'] }} {{ $employeeInformation['Tahun Payroll'] }}
                                </div>
                            </div>

                            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                                @foreach ($employeeInformation as $label => $value)
                                    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4 dark:border-white/10 dark:bg-slate-950/60">
                                        <dt class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                            {{ $label }}
                                        </dt>

                                        <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $value }}
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($highlights as $label => $amount)
                                <div class="rounded-[1.5rem] border border-stone-200 bg-white px-5 py-5 shadow-sm dark:border-white/10 dark:bg-slate-900/80">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                                        {{ $label }}
                                    </p>

                                    <p class="mt-3 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">
                                        {{ $amount }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-6 text-white shadow-2xl dark:from-slate-900 dark:via-slate-900 dark:to-slate-950">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-300/80">
                                    Ringkasan Penggajian
                                </p>

                                <h3 class="mt-2 text-2xl font-semibold tracking-tight text-white">
                                    Komposisi Gaji
                                </h3>
                            </div>

                            <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-medium text-slate-300 dark:border-white/10 dark:bg-white/5">
                                Ringkasan Akhir
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            @foreach ($summaryRows as $row)
                                <div @class([
                                    'flex items-center justify-between gap-4 rounded-2xl px-4 py-4',
                                    'bg-gradient-to-r from-amber-400 to-orange-400 text-slate-950 shadow-lg' => $row['highlight'] ?? false,
                                    'border border-white/10 bg-white/5' => ! ($row['highlight'] ?? false),
                                ])>
                                    <span @class([
                                        'text-sm',
                                        'font-semibold text-slate-950' => $row['highlight'] ?? false,
                                        'text-slate-200' => ! ($row['highlight'] ?? false),
                                    ])>
                                        {{ $row['label'] }}
                                    </span>

                                    <span @class([
                                        'font-semibold',
                                        'text-xl text-slate-950' => $row['highlight'] ?? false,
                                        'text-base text-white' => ! ($row['highlight'] ?? false),
                                    ])>
                                        {{ $row['amount'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-2xl border border-white/10 bg-black/20 px-4 py-4 text-sm text-slate-300">
                            <p class="font-medium text-white">
                                Formula
                            </p>

                            <p class="mt-2">
                                Take Home Pay = Gaji Pokok + Total Tunjangan - Total Potongan
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden rounded-[1.75rem] border border-emerald-200 bg-white shadow-sm dark:border-emerald-500/20 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between border-b border-stone-200 bg-emerald-50 px-6 py-5 dark:border-white/10 dark:bg-emerald-500/10">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">
                                    Detail Tunjangan
                                </p>
                                <h3 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">
                                    Tunjangan
                                </h3>
                            </div>

                            <div class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:bg-emerald-400 dark:text-slate-950">
                                {{ $highlights['Total Tunjangan'] }}
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            @forelse ($allowanceItems as $item)
                                <div class="flex items-center justify-between gap-4 border-b border-dashed border-stone-200 py-4 last:border-b-0 dark:border-white/10">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                        {{ $item['name'] }}
                                    </span>

                                    <span class="text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $item['amount'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-stone-50 px-4 py-4 text-sm text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                    Tidak ada tunjangan pada payroll ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-[1.75rem] border border-rose-200 bg-white shadow-sm dark:border-rose-500/20 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between border-b border-stone-200 bg-rose-50 px-6 py-5 dark:border-white/10 dark:bg-rose-500/10">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-700 dark:text-rose-300">
                                    Detail Potongan
                                </p>
                                <h3 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">
                                    Potongan
                                </h3>
                            </div>

                            <div class="rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm dark:bg-rose-400 dark:text-slate-950">
                                {{ $highlights['Total Potongan'] }}
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            @forelse ($deductionItems as $item)
                                <div class="flex items-center justify-between gap-4 border-b border-dashed border-stone-200 py-4 last:border-b-0 dark:border-white/10">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                        {{ $item['name'] }}
                                    </span>

                                    <span class="text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $item['amount'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-stone-50 px-4 py-4 text-sm text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                    Tidak ada potongan pada payroll ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
