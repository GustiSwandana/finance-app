@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto space-y-8">

    <section class="app-hero px-6 py-7 md:px-8 md:py-9">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="app-eyebrow">Finance Insight</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight md:text-5xl">Laporan yang lebih mudah dibaca dan diputuskan.</h2>
                <p class="mt-3 text-sm text-white/80 md:text-base">
                    Lihat tren pemasukan dan pengeluaran dalam format yang lebih bersih, lalu masuk ke laporan detail saat Anda butuh investigasi lebih dalam.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <a href="{{ route('reports.detail') }}" class="rounded-2xl bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Explore</p>
                    <p class="mt-2 text-sm font-bold text-white">Detail</p>
                </a>
                <a href="{{ route('reports.export.excel') }}" class="rounded-2xl bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Export</p>
                    <p class="mt-2 text-sm font-bold text-white">Excel</p>
                </a>
                <a href="{{ route('reports.export.pdf') }}" class="rounded-2xl bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Archive</p>
                    <p class="mt-2 text-sm font-bold text-white">PDF</p>
                </a>
            </div>
        </div>
    </section>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-950 dark:text-white">Laporan Keuangan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Analisis pendapatan dan pengeluaran Anda.</p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('reports.detail') }}" class="group flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:hover:bg-indigo-950/30">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/></svg>
                Laporan Detail
            </a>
            <a href="{{ route('reports.export.excel') }}" class="group flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:border-green-200 hover:bg-green-50 hover:text-green-600 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:hover:bg-green-950/30">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 group-hover:scale-110 transition-transform"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                Export Excel
            </a>
            <a href="{{ route('reports.export.pdf') }}" class="group flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-slate-800 hover:shadow-lg dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        <div class="app-panel rounded-[28px] p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">Ringkasan Bulanan</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">Tahun Ini</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-300">
                            <th class="px-4 py-3">Bulan</th>
                            <th class="px-4 py-3 text-right text-success-300">Masuk</th>
                            <th class="px-4 py-3 text-right text-error-300">Keluar</th>
                            <th class="px-4 py-3 text-right">Net</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($monthlyStats as $stat)
                            @php
                                $net = $stat->total_income - $stat->total_expense;
                                $monthName = \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y');
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-900/60">
                                <td class="px-4 py-4 text-sm font-bold text-slate-950 dark:text-white">{{ $monthName }}</td>
                                <td class="px-4 py-4 text-sm text-right font-medium text-success-300">+ {{ number_format($stat->total_income/1000, 0) }}k</td>
                                <td class="px-4 py-4 text-sm text-right font-medium text-error-300">- {{ number_format($stat->total_expense/1000, 0) }}k</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $net >= 0 ? 'bg-success-50 text-success-300' : 'bg-error-50 text-error-300' }}">
                                        Rp {{ number_format($net, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada data transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="app-panel rounded-[28px] p-6">
            <div class="flex justify-between items-center mb-6">
                 <div class="flex items-center gap-2">
                    <div class="h-8 w-1 bg-purple-500 rounded-full"></div>
                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">Ringkasan Mingguan</h3>
                </div>
                <span class="text-xs font-medium bg-purple-50 text-purple-500 px-3 py-1 rounded-full dark:bg-purple-900/20">{{ date('Y') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-300">
                            <th class="px-4 py-3">Minggu</th>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3 text-right">Net</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($weeklyStats as $stat)
                            @php
                                $net = $stat->total_income - $stat->total_expense;
                                $start = \Carbon\Carbon::parse($stat->start_date)->format('d M');
                                $end = \Carbon\Carbon::parse($stat->end_date)->format('d M');
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-900/60">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                                        <span class="text-sm font-bold text-slate-950 dark:text-white">W-{{ $stat->week_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $start }} - {{ $end }}</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-sm font-bold {{ $net >= 0 ? 'text-success-300' : 'text-error-300' }}">
                                        {{ $net >= 0 ? '+' : '' }} Rp {{ number_format($net, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada transaksi minggu ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
