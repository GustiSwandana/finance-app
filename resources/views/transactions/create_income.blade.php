@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto mt-8">

    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-100 text-success-300">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19V5M5 12l7-7 7 7" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Catat Pemasukan</h2>
            <p class="text-sm text-bgray-500 dark:text-bgray-300">Tambahkan sumber dana baru ke dompet Anda.</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('income.store') }}" method="POST">
            @csrf

            <div class="mb-8">
                <label class="mb-3 block text-sm font-bold text-bgray-600 dark:text-bgray-50 uppercase tracking-wider">
                    Jumlah Pemasukan
                </label>
                <div class="relative group">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl font-bold text-bgray-400 group-focus-within:text-success-300 transition-colors">Rp</span>
                    <input type="number" name="amount" class="w-full rounded-xl border-2 border-bgray-200 bg-bgray-50 py-5 pl-14 pr-5 text-3xl font-bold text-bgray-900 focus:border-success-300 focus:bg-white focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-all placeholder:text-bgray-300" placeholder="0" required autofocus>
                </div>
            </div>

            <div class="my-8 h-px w-full bg-bgray-200 dark:bg-darkblack-400"></div>

            <div class="grid gap-6 md:grid-cols-2 mb-6">

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-bgray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2" />
                            <path d="M6 12h.01M18 12h.01" /></svg>
                        Masuk ke Bank
                    </label>
                    <div class="relative">
                        <select name="wallet_id" class="w-full appearance-none rounded-lg border border-bgray-300 bg-white px-4 py-3.5 text-base font-medium text-bgray-900 focus:border-success-300 focus:ring-2 focus:ring-success-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow cursor-pointer">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->bank_name }} — (Rp {{ number_format($wallet->balance) }})</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" /></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-bgray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                            <line x1="7" y1="7" x2="7.01" y2="7" /></svg>
                        Kategori
                    </label>
                    <div class="relative">
                        <select name="category_id" class="w-full appearance-none rounded-lg border border-bgray-300 bg-white px-4 py-3.5 text-base font-medium text-bgray-900 focus:border-success-300 focus:ring-2 focus:ring-success-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow cursor-pointer">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 mb-8">
                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-bgray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" /></svg>
                        Tanggal
                    </label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-bgray-300 px-4 py-3.5 text-base text-bgray-900 focus:border-success-300 focus:ring-2 focus:ring-success-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow">
                </div>

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-bgray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                        Catatan (Opsional)
                    </label>
                    <input type="text" name="description" placeholder="Contoh: Gaji Bulan Ini" class="w-full rounded-lg border border-bgray-300 px-4 py-3.5 text-base text-bgray-900 focus:border-success-300 focus:ring-2 focus:ring-success-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('dashboard') }}" class="rounded-lg px-6 py-3 text-sm font-bold text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-success-300 px-8 py-3 text-base font-bold text-white shadow-lg shadow-success-300/30 transition-all hover:bg-success-400 hover:shadow-success-400/40 active:scale-95">
                    <span>Simpan Pemasukan</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" /></svg>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
