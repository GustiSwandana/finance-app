@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">

    <section class="app-hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Recurring Bills</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Kelola tagihan rutin sebelum jatuh tempo.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Pantau layanan bulanan, pilih wallet pembayaran, dan tandai tagihan lunas dari satu halaman yang ringkas.</p>
            </div>
            <div class="min-w-[180px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Total</p>
                <p class="mt-3 text-3xl font-black text-white">{{ count($subscriptions) }}</p>
                <p class="mt-2 text-xs text-white/65">Layanan aktif</p>
            </div>
        </div>
    </section>

    @if($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-red-600 dark:border-red-900/40 dark:bg-red-950/20 dark:text-red-200">
        <span class="text-sm font-semibold">{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-8 items-start">

        <div class="w-full xl:w-1/3 xl:sticky xl:top-24 z-10">
            <div class="app-panel p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">Buat Baru</h3>
                    <div class="rounded-lg bg-teal-50 p-2 text-teal-600 dark:bg-slate-900 dark:text-teal-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" /></svg>
                    </div>
                </div>

                <form action="{{ route('subscriptions.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Layanan</label>
                        <div class="flex items-center overflow-hidden rounded-xl border-2 border-slate-200 bg-white transition-colors focus-within:border-teal-500 dark:border-slate-800 dark:bg-slate-900/70">
                            <div class="pl-4 text-slate-400 dark:text-slate-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" /></svg>
                            </div>
                            <input type="text" name="name" class="w-full border-none bg-transparent px-3 py-3 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:ring-0 dark:text-white dark:placeholder:text-slate-500" placeholder="Contoh: Netflix, Listrik" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Biaya Bulanan</label>
                        <div class="flex items-center overflow-hidden rounded-xl border-2 border-slate-200 bg-white transition-colors focus-within:border-teal-500 dark:border-slate-800 dark:bg-slate-900/70">
                            <div class="pl-4 text-sm font-bold text-slate-400 dark:text-slate-500">Rp</div>
                            <input type="number" name="amount" class="w-full border-none bg-transparent px-3 py-3 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:ring-0 dark:text-white dark:placeholder:text-slate-500" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-8 group">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 transition-colors group-focus-within:text-teal-500 dark:text-slate-400">
                            Jatuh Tempo
                        </label>

                        <div class="relative w-full">

                            <div class="pointer-events-none absolute left-4 top-4 z-10 -translate-y-1/2 transform text-slate-400 transition-colors group-focus-within:text-teal-500 dark:text-slate-500">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>

                            <select name="due_date" class="w-full cursor-pointer appearance-none rounded-xl border-2 border-slate-200 bg-white py-3.5 pl-12 pr-10 text-sm font-bold text-slate-950 transition-all focus:border-teal-500 focus:bg-white focus:ring-0 dark:border-slate-800 dark:bg-slate-900/70 dark:text-white">
                                @for($i = 1; $i <= 28; $i++) <option value="{{ $i }}">Setiap Tanggal {{ $i }}</option>
                                    @endfor
                                    <option value="30">Akhir Bulan</option>
                            </select>

                            <div class="pointer-events-none absolute right-4 top-1/2 z-10 -translate-y-1/2 transform text-slate-400 dark:text-slate-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-teal-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-teal-600/25 transition-all hover:bg-teal-700 hover:shadow-teal-600/35 active:scale-95">

                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>

                        <span>Simpan Tagihan</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full xl:w-2/3">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-950 dark:text-white">Daftar Tagihan</h3>
                <span class="rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">{{ count($subscriptions) }} Layanan</span>
            </div>

            <div class="grid gap-5 sm:grid-cols-1 lg:grid-cols-2">
                @foreach($subscriptions as $sub)
                @php $isPaid = $sub->isPaidThisMonth(); @endphp

                <div class="app-panel group relative overflow-hidden rounded-2xl p-6 transition-all hover:-translate-y-1 {{ $isPaid ? 'border-success-200 dark:border-emerald-900/50' : 'border-warning-200 dark:border-amber-900/50' }}">

                    <div class="absolute top-5 right-5 z-10">
                        @if($isPaid)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-success-100 bg-success-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-success-300 dark:border-emerald-900/40 dark:bg-emerald-950/25 dark:text-emerald-200">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                <path d="M20 6L9 17L4 12" /></svg>
                            Lunas
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-warning-100 bg-warning-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-warning-300 animate-pulse dark:border-amber-900/40 dark:bg-amber-950/25 dark:text-amber-200">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                <circle cx="12" cy="12" r="10" /></svg>
                            Tagihan
                        </span>
                        @endif
                    </div>

                    <div class="flex flex-col h-full justify-between relative z-0">
                        <div class="mb-4">
                            <h4 class="truncate pr-20 text-lg font-bold text-slate-950 dark:text-white">{{ $sub->name }}</h4>
                            <p class="mt-2 flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-[10px] uppercase tracking-wider dark:bg-slate-900 dark:text-slate-200">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" /></svg>
                                    Tgl {{ $sub->due_date }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-6">
                            <div class="text-2xl font-black tracking-tight text-slate-950 dark:text-white">
                                Rp {{ number_format($sub->amount, 0, ',', '.') }}
                                <span class="text-xs font-medium tracking-normal text-slate-400 dark:text-slate-500">/bln</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 border-t border-slate-200 pt-4 dark:border-slate-800">

                            @if(!$isPaid)
                            <form action="{{ route('subscriptions.pay', $sub->id) }}" method="POST" class="flex-1 flex gap-2 w-full">
                                @csrf
                                <div class="relative w-full">
                                    <select name="wallet_id" class="h-11 w-full cursor-pointer appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-xs font-bold text-slate-700 focus:border-teal-500 focus:ring-0 dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                        @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path d="M6 9l6 6 6-6" /></svg>
                                    </div>
                                </div>
                                <button type="submit" class="h-11 shrink-0 rounded-xl bg-slate-950 px-5 text-xs font-bold text-white transition-colors shadow-sm hover:bg-slate-900 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100" onclick="return confirm('Bayar tagihan ini sekarang?')">
                                    Bayar
                                </button>
                            </form>
                            @else
                            <div class="flex h-11 flex-1 items-center gap-2 rounded-xl border border-transparent bg-slate-50 px-3 text-xs text-slate-400 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                <svg width="14" height="14" class="text-success-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" /></svg>
                                Lunas tgl <span class="font-bold text-slate-600 dark:text-white">{{ $sub->last_paid_at->format('d M') }}</span>
                            </div>
                            @endif

                            <form action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus langganan ini?');" class="flex items-center">
                                @csrf @method('DELETE')
                                <button class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-rose-950/30 dark:hover:text-rose-200" title="Hapus">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($subscriptions->isEmpty())
            <div class="mt-4 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-white py-16 dark:border-slate-800 dark:bg-slate-950/70">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-900 dark:text-slate-500">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <path d="M12 6v6l4 2" /></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-950 dark:text-white">Belum ada tagihan</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">Tambahkan tagihan rutin Anda di formulir sebelah kiri.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
