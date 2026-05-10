@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Income Entry</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Catat pemasukan baru dengan tampilan yang lebih tenang di mode terang maupun gelap.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Gunakan halaman ini untuk menambahkan dana masuk ke wallet yang sesuai, lengkap dengan kategori dan catatan bila diperlukan.</p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('income.store') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label class="mb-3 block text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-300">Jumlah pemasukan</label>
                <div class="relative rounded-3xl border border-emerald-100 bg-emerald-50/70 px-5 py-5 transition focus-within:border-emerald-300 focus-within:bg-white dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:focus-within:bg-slate-900/60">
                    <span class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-2xl font-black text-emerald-500">Rp</span>
                    <input type="number" name="amount" class="w-full border-none bg-transparent pl-10 pr-2 text-4xl font-black tracking-tight text-slate-950 placeholder:text-slate-300 focus:ring-0 dark:text-white dark:placeholder:text-slate-600" placeholder="0" required autofocus>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Masuk ke wallet</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->bank_name }} | Rp {{ number_format($wallet->balance) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Kategori</label>
                    <select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Catatan</label>
                    <input type="text" name="description" placeholder="Contoh: gaji bulan ini" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Batal</a>
                <button type="submit" class="rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Simpan Pemasukan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
