@extends('layouts.app')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format($value ?? 0, 0, ',', '.');
    $walletCount = $walletCards->count();
    $mainWallets = collect([
        ['name' => 'BCA', 'wallet' => $bca, 'tone' => 'sky', 'icon' => 'card'],
        ['name' => 'Mandiri', 'wallet' => $mandiri, 'tone' => 'amber', 'icon' => 'cash'],
        ['name' => 'BRI', 'wallet' => $bri, 'tone' => 'orange', 'icon' => 'vault'],
    ]);
@endphp

<div class="mx-auto flex w-full max-w-7xl flex-col gap-7">
    <section class="app-hero px-5 py-6 sm:px-7 sm:py-8">
        <div class="relative z-10 grid gap-7 xl:grid-cols-[minmax(0,1fr)_minmax(320px,440px)] xl:items-end">
            <div class="max-w-3xl">
                <span class="app-eyebrow">Ringkasan Harian</span>
                <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Keuangan Anda dalam satu pandangan.
                </h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Pantau aset, arus kas bulan ini, transaksi terbaru, dan pekerjaan penting tanpa berpindah halaman.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('transactions.index') }}" class="rounded-2xl border border-white/15 bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Input</p>
                    <p class="mt-2 text-sm font-bold text-white">Transaksi</p>
                </a>
                <a href="{{ route('transfer.create') }}" class="rounded-2xl border border-white/15 bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Internal</p>
                    <p class="mt-2 text-sm font-bold text-white">Transfer</p>
                </a>
                <a href="{{ route('reports.index') }}" class="rounded-2xl border border-white/15 bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Analisis</p>
                    <p class="mt-2 text-sm font-bold text-white">Laporan</p>
                </a>
                <a href="{{ route('bank-mutations.create') }}" class="rounded-2xl border border-white/15 bg-white/12 px-4 py-4 text-left backdrop-blur-sm transition hover:bg-white/18">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Bulk</p>
                    <p class="mt-2 text-sm font-bold text-white">Import PDF</p>
                </a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <article class="app-panel overflow-hidden p-5 sm:p-6">
            <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Total aset</p>
                    <h2 class="mt-3 break-words text-3xl font-black tracking-tight text-slate-950 dark:text-white sm:text-4xl">
                        {{ $formatRupiah($totalSaldo) }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Akumulasi saldo dari {{ $walletCount }} wallet aktif.
                    </p>
                </div>
                <div class="grid min-w-[220px] grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-[11px] font-bold uppercase text-slate-400 dark:text-slate-500">Hari ini</p>
                        <p class="mt-2 text-2xl font-black text-slate-950 dark:text-white">{{ $todayTransactionCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                        <p class="text-[11px] font-bold uppercase text-slate-400 dark:text-slate-500">Bulan ini</p>
                        <p class="mt-2 text-2xl font-black text-slate-950 dark:text-white">{{ $monthlyTransactionCount }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-3 md:grid-cols-3">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-4 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-200">Masuk bulan ini</p>
                    <p class="mt-3 text-xl font-black text-emerald-700 dark:text-emerald-200">{{ $formatRupiah($monthlyIncome) }}</p>
                </div>
                <div class="rounded-2xl border border-rose-100 bg-rose-50/70 px-4 py-4 dark:border-rose-900/40 dark:bg-rose-950/20">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-rose-600 dark:text-rose-200">Keluar bulan ini</p>
                    <p class="mt-3 text-xl font-black text-rose-600 dark:text-rose-200">{{ $formatRupiah($monthlyExpense) }}</p>
                </div>
                <div class="rounded-2xl border border-sky-100 bg-sky-50/70 px-4 py-4 dark:border-sky-900/40 dark:bg-sky-950/20">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sky-700 dark:text-sky-200">Saldo bersih</p>
                    <p class="mt-3 text-xl font-black {{ $monthlyNet >= 0 ? 'text-sky-700 dark:text-sky-200' : 'text-rose-600 dark:text-rose-200' }}">{{ $formatRupiah($monthlyNet) }}</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="mb-2 flex items-center justify-between gap-3 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">
                    <span>Rasio pengeluaran bulan ini</span>
                    <span>{{ $expenseRatio }}%</span>
                </div>
                <div class="h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-full rounded-full {{ $expenseRatio >= 85 ? 'bg-rose-500' : ($expenseRatio >= 60 ? 'bg-amber-400' : 'bg-emerald-500') }}" style="width: {{ $expenseRatio }}%"></div>
                </div>
            </div>
        </article>

        <article class="app-panel p-5 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Prioritas</p>
                    <h2 class="mt-2 text-xl font-black tracking-tight text-slate-950 dark:text-white">Aksi cepat</h2>
                </div>
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-700 dark:bg-teal-950/40 dark:text-teal-200">Ready</span>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <a href="{{ route('transactions.index') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:border-teal-200 hover:bg-teal-50 dark:border-slate-800 dark:bg-slate-900/70 dark:hover:bg-teal-950/20">
                    <span>
                        <span class="block text-sm font-black text-slate-950 dark:text-white">Input transaksi</span>
                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Catat pemasukan atau pengeluaran.</span>
                    </span>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-teal-600">-></span>
                </a>
                <a href="{{ route('transfer.create') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:border-amber-200 hover:bg-amber-50 dark:border-slate-800 dark:bg-slate-900/70 dark:hover:bg-amber-950/20">
                    <span>
                        <span class="block text-sm font-black text-slate-950 dark:text-white">Transfer dana</span>
                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Pindahkan saldo antar wallet.</span>
                    </span>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-amber-600">-></span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:border-sky-200 hover:bg-sky-50 dark:border-slate-800 dark:bg-slate-900/70 dark:hover:bg-sky-950/20">
                    <span>
                        <span class="block text-sm font-black text-slate-950 dark:text-white">Cek tagihan</span>
                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Pastikan pembayaran rutin aman.</span>
                    </span>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-sky-600">-></span>
                </a>
            </div>
        </article>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        @foreach($mainWallets as $item)
            @php
                $wallet = $item['wallet'];
                $balance = $wallet?->balance ?? 0;
                $toneClass = match ($item['tone']) {
                    'sky' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/30 dark:text-sky-200',
                    'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-200',
                    default => 'bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-200',
                };
            @endphp
            <article class="metric-card p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">{{ $item['name'] }}</p>
                        <h3 class="mt-3 break-words text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ $formatRupiah($balance) }}</h3>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $wallet ? 'Wallet utama' : 'Belum tersedia' }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $toneClass }}">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                            <path d="M12 12h.01"></path>
                        </svg>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="grid gap-7 xl:grid-cols-[minmax(0,0.95fr)_minmax(360px,1.05fr)]">
        <div class="space-y-7">
            <section class="app-panel p-5 sm:p-6">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Wallet</p>
                        <h2 class="mt-2 text-xl font-black tracking-tight text-slate-950 dark:text-white">Semua sumber dana</h2>
                    </div>
                    <a href="{{ route('wallets.index') }}" class="text-sm font-bold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">Kelola wallet</a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @forelse($walletCards as $wallet)
                    <article class="rounded-2xl border border-slate-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900/70">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="truncate text-base font-black text-slate-950 dark:text-white">{{ $wallet->bank_name }}</p>
                                <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">{{ $wallet->type ?? 'wallet' }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-300">Aktif</span>
                        </div>
                        <p class="mt-4 break-words text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ $formatRupiah($wallet->balance) }}</p>
                    </article>
                    @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center dark:border-slate-800 dark:bg-slate-900/60 sm:col-span-2">
                        <p class="text-base font-bold text-slate-800 dark:text-white">Belum ada wallet</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tambahkan rekening, e-wallet, atau kas tunai agar dashboard bisa menghitung aset.</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="space-y-7">
            <section class="app-panel p-5 sm:p-6">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Aktivitas terbaru</p>
                        <h2 class="mt-2 text-xl font-black tracking-tight text-slate-950 dark:text-white">5 transaksi terakhir</h2>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">Lihat semua</a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($latestTransactions as $trx)
                    @php
                        $isIncome = $trx->type === 'income';
                        $isExpense = $trx->type === 'expense';
                        $amountPrefix = $isIncome ? '+' : ($isExpense ? '-' : '');
                        $tone = $isIncome
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-200'
                            : ($isExpense ? 'bg-rose-100 text-rose-600 dark:bg-rose-950/30 dark:text-rose-200' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/30 dark:text-amber-200');
                    @endphp
                    <article class="rounded-2xl border border-slate-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900/70">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex min-w-0 items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $tone }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                        @if($isIncome)
                                        <path d="M12 19V5M5 12l7-7 7 7"></path>
                                        @elseif($isExpense)
                                        <path d="M12 5v14M12 19l4-4M12 19l-4-4"></path>
                                        @else
                                        <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="truncate text-sm font-black text-slate-950 dark:text-white">{{ $trx->description ?: ($trx->category->name ?? 'Transfer') }}</h3>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $trx->date->format('d M Y') }} | {{ ucfirst($trx->type) }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-bold text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $trx->wallet->bank_name }}</span>
                                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-bold text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $trx->category->name ?? 'Transfer' }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="shrink-0 text-left text-sm font-black sm:text-right {{ $isIncome ? 'text-emerald-700 dark:text-emerald-200' : ($isExpense ? 'text-rose-600 dark:text-rose-200' : 'text-amber-600 dark:text-amber-200') }}">
                                {{ $amountPrefix }} {{ $formatRupiah($trx->amount) }}
                            </p>
                        </div>
                    </article>
                    @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-12 text-center dark:border-slate-800 dark:bg-slate-900/60">
                        <p class="text-base font-bold text-slate-800 dark:text-white">Belum ada transaksi</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Mulai catat pemasukan atau pengeluaran pertama Anda.</p>
                        <a href="{{ route('transactions.index') }}" class="mt-5 inline-flex rounded-2xl bg-teal-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-teal-700">Input transaksi</a>
                    </div>
                    @endforelse
                </div>
            </section>

            <section class="app-hero px-5 py-6">
                <div class="relative z-10">
                    <span class="app-eyebrow">Tips Hemat</span>
                    <h2 class="mt-4 text-2xl font-black tracking-tight text-white">Gunakan tagihan rutin sebagai pengingat otomatis.</h2>
                    <p class="mt-3 max-w-md text-sm leading-7 text-white/80">
                        Pencatatan rutin membantu menjaga arus kas tetap rapi dan menghindari denda keterlambatan.
                    </p>
                    <a href="{{ route('subscriptions.index') }}" class="mt-5 inline-flex rounded-2xl bg-white px-4 py-3 text-sm font-bold text-teal-700 transition hover:bg-teal-50">Buka Tagihan Rutin</a>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection
