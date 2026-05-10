@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-5xl flex-col gap-8">
    <section class="app-hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Transaction Editor</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Perbarui detail transaksi tanpa kehilangan kontrol saldo.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Form ini akan menyesuaikan saldo wallet secara otomatis setelah data transaksi disimpan ulang.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-200' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/70 dark:text-rose-200' }}">
                    {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </span>
                <span class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-white/80">
                    {{ optional($transaction->date)->format('d M Y') }}
                </span>
            </div>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">Edit Form</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Sesuaikan nominal, wallet, kategori, atau catatan</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Gunakan halaman ini saat ada koreksi input atau perpindahan pencatatan ke wallet lain.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600 transition hover:border-slate-300 hover:bg-white hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-slate-700 dark:hover:bg-slate-950">
                Kembali ke riwayat
            </a>
        </div>

        @if($errors->any())
        <div class="mt-6 rounded-[24px] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
            <p class="font-bold">Perubahan belum bisa disimpan</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Jumlah</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 pl-10 text-lg font-black tracking-tight text-slate-900 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500" placeholder="0" required>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Wallet</label>
                            <select name="wallet_id" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                                @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}" {{ (int) old('wallet_id', $transaction->wallet_id) === $wallet->id ? 'selected' : '' }}>
                                    {{ $wallet->bank_name }} - Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Kategori</label>
                            <select name="category_id" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (int) old('category_id', $transaction->category_id) === $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Tanggal</label>
                            <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white" required>
                        </div>

                        <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Status</p>
                            <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">{{ ucfirst($transaction->status ?? 'completed') }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Transaksi transfer tidak diedit dari halaman ini.</p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Keterangan</label>
                        <textarea name="description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Tambahkan konteks singkat jika diperlukan">{{ old('description', $transaction->description) }}</textarea>
                    </div>
                </div>

                <aside class="space-y-4">
                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 p-5 dark:border-slate-800 dark:bg-slate-950/70">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Ringkasan</p>
                        <p class="mt-3 text-2xl font-black tracking-tight {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-500 dark:text-rose-300' }}">
                            Rp {{ number_format(old('amount', $transaction->amount), 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Perubahan akan menyesuaikan saldo berdasarkan tipe transaksi saat ini.</p>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Checklist</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <p>Pilih wallet yang benar sebelum menyimpan ulang transaksi.</p>
                            <p>Pastikan kategori masih sesuai dengan jenis pemasukan atau pengeluaran.</p>
                            <p>Nominal pengeluaran tetap akan ditolak jika saldo wallet tidak cukup.</p>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
