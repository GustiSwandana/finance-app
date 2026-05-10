@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Expense Entry</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Pantau pengeluaran dengan kontras yang tetap nyaman di dark mode.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Pilih wallet sumber, kategori, tanggal, dan catatan pengeluaran agar histori transaksi tetap rapi dan mudah ditinjau ulang.</p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/20 dark:text-rose-200">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('expense.store') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <label class="mb-3 block text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-300">Total pengeluaran</label>
                <div class="relative rounded-3xl border border-rose-100 bg-rose-50/70 px-5 py-5 transition focus-within:border-rose-300 focus-within:bg-white dark:border-rose-900/40 dark:bg-rose-950/20 dark:focus-within:bg-slate-900/60">
                    <span class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-2xl font-black text-rose-500">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" class="w-full border-none bg-transparent pl-10 pr-2 text-4xl font-black tracking-tight text-slate-950 placeholder:text-slate-300 focus:ring-0 dark:text-white dark:placeholder:text-slate-600" placeholder="0" required autofocus>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Sumber dana</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                            {{ $wallet->bank_name }} | Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Kategori</label>
                    <select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Catatan</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Contoh: makan siang, ongkir, listrik" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-rose-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">Batal</a>
                <button type="submit" class="rounded-2xl bg-rose-500 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(244,63,94,0.2)] transition hover:-translate-y-0.5 hover:bg-rose-600">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
