@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-7 md:px-8 md:py-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Wallet Hub</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Kelola semua sumber dana dalam satu panel.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Tambahkan rekening bank, e-wallet, atau kas tunai. Semua wallet di sini akan dipakai oleh transaksi, transfer, import mutasi, dan laporan saldo.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="metric-card min-w-[180px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Total Wallet</p>
                    <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $wallets->count() }}</p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Sumber dana aktif</p>
                </div>
                <div class="metric-card min-w-[180px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Saldo Tercatat</p>
                    <p class="mt-3 text-3xl font-black text-sky-600">Rp {{ number_format($wallets->sum('balance'), 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Akumulasi semua akun</p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,360px)_minmax(0,1fr)] xl:items-start">
        <section class="app-panel p-6 xl:sticky xl:top-24">
            <div class="border-b border-slate-200 pb-5 dark:border-slate-800">
                <p class="app-eyebrow bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">Add Wallet</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Tambah akun baru</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Gunakan nama yang jelas agar mudah dipilih saat transaksi dan transfer.</p>
            </div>

            @if ($errors->any())
            <div class="mt-6 rounded-[24px] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
                <p class="font-bold">Wallet belum bisa disimpan</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('wallets.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Nama bank / e-wallet</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Contoh: BCA, Jago, GoPay, Kas Tunai" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Saldo awal</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400 dark:text-slate-500">Rp</span>
                        <input type="number" name="balance" value="{{ old('balance') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pl-10 text-lg font-black tracking-tight text-slate-900 focus:border-sky-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500" placeholder="0" required>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-sky-600 px-4 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(2,132,199,0.25)] transition hover:-translate-y-0.5 hover:bg-sky-700">
                    Simpan Akun
                </button>
            </form>
        </section>

        <section class="app-panel p-6">
            <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 md:flex-row md:items-end md:justify-between dark:border-slate-800">
                <div>
                    <p class="app-eyebrow bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">Account List</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Daftar akun saya</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Hapus hanya jika Anda benar-benar sudah tidak memakai wallet tersebut.</p>
                </div>
                <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                    {{ count($wallets) }} akun
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                @forelse($wallets as $wallet)
                <article class="rounded-[28px] border border-slate-200 bg-slate-50/70 p-5 transition hover:-translate-y-0.5 hover:border-sky-200 hover:bg-white hover:shadow-lg dark:border-slate-800 dark:bg-slate-950/70 dark:hover:border-sky-900/60 dark:hover:bg-slate-950">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 12V8H6a2 2 0 0 1-2-2 2 2 0 0 1 2-2h12v4" />
                                        <path d="M4 6v12a2 2 0 0 0 2 2h14v-4" />
                                        <path d="M18 12a2 2 0 0 0-2 2 2 2 0 0 0 2 2h4v-4h-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ $wallet->bank_name }}</h3>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Wallet aktif</p>
                                </div>
                            </div>
                            <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Saldo saat ini</p>
                            <p class="mt-2 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                        </div>

                        <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini? Hati-hati, data transaksi terkait mungkin error.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-2xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-rose-950/30 dark:hover:text-rose-200" title="Hapus akun">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </article>
                @empty
                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center lg:col-span-2 dark:border-slate-800 dark:bg-slate-950/70">
                    <p class="text-lg font-bold text-slate-800 dark:text-white">Belum ada wallet</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tambahkan akun bank atau dompet digital pertama Anda dari panel di sebelah kiri.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
