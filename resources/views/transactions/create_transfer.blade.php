@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-5xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Internal Transfer</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Pindahkan dana antar wallet tanpa mengacaukan laporan.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                Transfer hanya memindahkan saldo dari wallet sumber ke wallet tujuan. Pastikan akun sumber dan tujuan tidak sama agar mutasi internal tetap bersih.
            </p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        @if($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('transfer.store') }}" method="POST" class="space-y-8">
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

            <div>
                <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.26em] text-slate-400">Nominal transfer</label>
                <div class="flex items-center rounded-[32px] border border-amber-100 bg-amber-50/70 px-5 py-5 transition focus-within:border-amber-300 focus-within:bg-white">
                    <span class="pr-3 text-4xl font-black text-amber-500">Rp</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" class="w-full border-none bg-transparent p-0 text-4xl font-black tracking-tight text-slate-950 placeholder:text-slate-300 focus:ring-0" placeholder="0" required autofocus>
                </div>
                <p class="mt-2 text-right text-xs text-slate-400">Gunakan nominal aktual agar saldo antar wallet tetap sinkron.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_72px_minmax(0,1fr)] lg:items-end">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Dari akun</label>
                    <select name="source_wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0">
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->bank_name }} | Rp {{ number_format($wallet->balance, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center lg:pb-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-500">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Ke akun</label>
                    <select name="destination_wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0">
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Catatan</label>
                    <input type="text" name="description" placeholder="Contoh: modal operasional, pindah saldo, tabungan" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                <a href="{{ route('dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                    Batal
                </a>
                <button type="submit" class="rounded-2xl bg-amber-500 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(245,158,11,0.22)] transition hover:-translate-y-0.5 hover:bg-amber-600">
                    Proses Transfer
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
