@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto mt-8">

    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-warning-100 text-warning-300">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Transfer Antar Akun</h2>
            <p class="text-sm text-bgray-500 dark:text-bgray-300">Pindahkan dana dengan mudah & cepat.</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">

        @if($errors->any())
        <div class="mb-6 flex items-start gap-3 rounded-lg bg-error-50 p-4 text-error-300 border border-error-100">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" /></svg>
            <div class="text-sm font-medium">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('transfer.store') }}" method="POST">
            @csrf

            <style>
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }

                input[type=number] {
                    -moz-appearance: textfield;
                }

            </style>

            <div class="mb-8">
                <label class="mb-3 block text-xs font-bold text-white uppercase tracking-widest">
                    Nominal Transfer
                </label>

                <div class="group relative flex items-center w-full rounded-2xl bg-gray-50 border-2 border-gray-100 focus-within:border-warning-300 focus-within:bg-white focus-within:shadow-xl focus-within:shadow-warning-100/20 transition-all duration-300 ease-out dark:bg-darkblack-500 dark:border-darkblack-400">

                    <div class="pl-6 pr-4 pointer-events-none">
                        <span class="text-4xl font-black text-white group-focus-within:text-warning-400 transition-colors duration-300">Rp</span>
                    </div>

                    <input type="number" name="amount" value="{{ old('amount') }}" class="w-full bg-transparent border-none py-6 pr-6 text-4xl font-black text-gray-900 placeholder-gray-200 focus:ring-0 dark:text-white dark:placeholder-gray-600" placeholder="0" required autofocus>
                </div>

                <div class="mt-2 flex justify-end">
                    <p class="text-xs text-gray-400">Minimal transfer Rp 10.000</p>
                </div>
            </div>

            <div class="my-8 h-px w-full bg-bgray-200 dark:bg-darkblack-400"></div>

            <div class="relative grid gap-6 md:grid-cols-2 mb-8 items-end">

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-error-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 19V5M5 12l7-7 7 7" transform="rotate(180 12 12)" /></svg>
                        Dari Akun (Sumber)
                    </label>
                    <div class="relative">
                        <select name="source_wallet_id" class="w-full appearance-none rounded-lg border border-bgray-300 bg-white px-4 py-3.5 text-base font-medium text-bgray-900 focus:border-warning-300 focus:ring-2 focus:ring-warning-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow cursor-pointer">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">
                                {{ $wallet->bank_name }} — (Rp {{ number_format($wallet->balance, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" /></svg>
                        </div>
                    </div>
                </div>

                <div class="hidden md:flex absolute left-1/2 top-[42px] -translate-x-1/2 items-center justify-center w-10 h-10 bg-bgray-100 rounded-full text-bgray-400 border border-bgray-200 z-10 shadow-sm">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </div>
                <div class="flex md:hidden justify-center -my-3 z-10">
                    <div class="w-10 h-10 flex items-center justify-center bg-bgray-100 rounded-full text-bgray-400 border border-bgray-200 shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M12 19l4-4M12 19l-4-4" /></svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-success-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 19V5M5 12l7-7 7 7" /></svg>
                        Ke Akun (Tujuan)
                    </label>
                    <div class="relative">
                        <select name="destination_wallet_id" class="w-full appearance-none rounded-lg border border-bgray-300 bg-white px-4 py-3.5 text-base font-medium text-bgray-900 focus:border-warning-300 focus:ring-2 focus:ring-warning-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow cursor-pointer">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">
                                {{ $wallet->bank_name }}
                            </option>
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
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-bgray-300 px-4 py-3.5 text-base text-bgray-900 focus:border-warning-300 focus:ring-2 focus:ring-warning-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow">
                </div>

                <div>
                    <label class="mb-2 flex items-center gap-2 text-sm font-bold text-bgray-900 dark:text-white">
                        <svg width="18" height="18" class="text-bgray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                        Catatan (Opsional)
                    </label>
                    <input type="text" name="description" placeholder="Contoh: Nabung, Bayar Utang" class="w-full rounded-lg border border-bgray-300 px-4 py-3.5 text-base text-bgray-900 focus:border-warning-300 focus:ring-2 focus:ring-warning-100 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white transition-shadow">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('dashboard') }}" class="rounded-lg px-6 py-3 text-sm font-bold text-bgray-600 hover:bg-bgray-100 hover:text-bgray-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-warning-300 px-8 py-3 text-base font-bold text-white shadow-lg shadow-warning-300/30 transition-all hover:bg-warning-400 hover:shadow-warning-400/40 active:scale-95">
                    <span>Proses Transfer</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5l7 7-7 7" /></svg>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
