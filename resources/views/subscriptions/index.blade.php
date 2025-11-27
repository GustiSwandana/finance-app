@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <div class="mb-10 flex items-center gap-5">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 shadow-sm dark:bg-purple-900 dark:text-purple-300">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Tagihan Rutin</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Kelola pembayaran bulanan agar tidak terlewat.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-8 flex items-center gap-3 rounded-xl bg-red-50 p-4 text-red-600 border border-red-100">
        <span class="text-sm font-semibold">{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-8 items-start">

        <div class="w-full xl:w-1/3 xl:sticky xl:top-24 z-10">
            <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-xl shadow-bgray-100/50 dark:shadow-none border border-bgray-100 dark:border-darkblack-400">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Buat Baru</h3>
                    <div class="p-2 bg-purple-50 dark:bg-darkblack-500 rounded-lg text-purple-500">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" /></svg>
                    </div>
                </div>

                <form action="{{ route('subscriptions.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider">Nama Layanan</label>
                        <div class="flex items-center rounded-xl border-2 border-bgray-100 bg-white overflow-hidden focus-within:border-purple-500 transition-colors dark:bg-darkblack-500 dark:border-darkblack-400">
                            <div class="pl-4 text-bgray-400">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" /></svg>
                            </div>
                            <input type="text" name="name" class="w-full border-none bg-transparent px-3 py-3 text-sm font-bold text-bgray-900 focus:ring-0 dark:text-white" placeholder="Contoh: Netflix, Listrik" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider">Biaya Bulanan</label>
                        <div class="flex items-center rounded-xl border-2 border-bgray-100 bg-white overflow-hidden focus-within:border-purple-500 transition-colors dark:bg-darkblack-500 dark:border-darkblack-400">
                            <div class="pl-4 font-bold text-bgray-400 text-sm">Rp</div>
                            <input type="number" name="amount" class="w-full border-none bg-transparent px-3 py-3 text-sm font-bold text-bgray-900 focus:ring-0 dark:text-white" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-8 group">
                        <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">
                            Jatuh Tempo
                        </label>

                        <div class="relative w-full">

                            <div class="absolute left-4 top-4 transform -translate-y-1/2 text-bgray-400 group-focus-within:text-purple-500 transition-colors pointer-events-none z-10">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>

                            <select name="due_date" class="w-full appearance-none rounded-xl border-2 border-bgray-100 bg-white py-3.5 pl-12 pr-10 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all">
                                @for($i = 1; $i <= 28; $i++) <option value="{{ $i }}">Setiap Tanggal {{ $i }}</option>
                                    @endfor
                                    <option value="30">Akhir Bulan</option>
                            </select>

                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-bgray-400 pointer-events-none z-10">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-purple-600 py-3.5 text-sm font-bold text-white 
           shadow-lg shadow-purple-500/30 transition-all 
           hover:bg-purple-700 hover:shadow-purple-500/50 active:scale-95">

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
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Daftar Tagihan</h3>
                <span class="rounded-full bg-bgray-100 px-3 py-1 text-xs font-bold text-bgray-600 dark:bg-darkblack-500 dark:text-bgray-50 border border-bgray-200 dark:border-darkblack-400">{{ count($subscriptions) }} Layanan</span>
            </div>

            <div class="grid gap-5 sm:grid-cols-1 lg:grid-cols-2">
                @foreach($subscriptions as $sub)
                @php $isPaid = $sub->isPaidThisMonth(); @endphp

                <div class="group relative overflow-hidden rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border transition-all hover:shadow-lg hover:-translate-y-1 {{ $isPaid ? 'border-success-200 dark:border-success-900' : 'border-warning-200 dark:border-warning-900' }}">

                    <div class="absolute top-5 right-5 z-10">
                        @if($isPaid)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-success-300 border border-success-100">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                <path d="M20 6L9 17L4 12" /></svg>
                            Lunas
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-warning-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-warning-300 border border-warning-100 animate-pulse">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                <circle cx="12" cy="12" r="10" /></svg>
                            Tagihan
                        </span>
                        @endif
                    </div>

                    <div class="flex flex-col h-full justify-between relative z-0">
                        <div class="mb-4">
                            <h4 class="text-lg font-bold text-bgray-900 dark:text-white pr-20 truncate">{{ $sub->name }}</h4>
                            <p class="text-xs font-medium text-bgray-500 mt-2 flex items-center gap-2">
                                <span class="flex items-center gap-1 bg-bgray-50 dark:bg-darkblack-500 px-2 py-1 rounded text-[10px] uppercase tracking-wider">
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
                            <div class="text-2xl font-black text-bgray-900 dark:text-white tracking-tight">
                                Rp {{ number_format($sub->amount, 0, ',', '.') }}
                                <span class="text-xs font-medium text-bgray-400 tracking-normal">/bln</span>
                            </div>
                        </div>

                        <div class="border-t border-bgray-100 pt-4 dark:border-darkblack-500 flex items-center gap-2">

                            @if(!$isPaid)
                            <form action="{{ route('subscriptions.pay', $sub->id) }}" method="POST" class="flex-1 flex gap-2 w-full">
                                @csrf
                                <div class="relative w-full">
                                    <select name="wallet_id" class="w-full h-11 appearance-none rounded-xl border border-bgray-200 bg-bgray-50 pl-3 pr-8 text-xs font-bold text-bgray-700 focus:border-purple-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer">
                                        @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-bgray-400">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path d="M6 9l6 6 6-6" /></svg>
                                    </div>
                                </div>
                                <button type="submit" class="h-11 shrink-0 rounded-xl bg-gray-900 px-5 text-xs font-bold text-white hover:bg-black dark:bg-white dark:text-gray-900 transition-colors shadow-sm" onclick="return confirm('Bayar tagihan ini sekarang?')">
                                    Bayar
                                </button>
                            </form>
                            @else
                            <div class="flex-1 h-11 flex items-center gap-2 text-xs text-bgray-400 bg-bgray-50 dark:bg-darkblack-500 px-3 rounded-xl border border-transparent dark:border-darkblack-400">
                                <svg width="14" height="14" class="text-success-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" /></svg>
                                Lunas tgl <span class="text-bgray-600 dark:text-white font-bold">{{ $sub->last_paid_at->format('d M') }}</span>
                            </div>
                            @endif

                            <form action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus langganan ini?');" class="flex items-center">
                                @csrf @method('DELETE')
                                <button class="flex h-11 w-11 items-center justify-center rounded-xl border border-bgray-100 bg-white text-bgray-400 hover:border-error-100 hover:bg-error-50 hover:text-error-300 transition-all dark:bg-darkblack-500 dark:border-darkblack-400" title="Hapus">
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
            <div class="mt-4 flex flex-col items-center justify-center py-16 bg-white dark:bg-darkblack-600 rounded-2xl border-2 border-dashed border-bgray-300 dark:border-darkblack-400">
                <div class="h-16 w-16 rounded-full bg-bgray-100 flex items-center justify-center text-bgray-400 mb-4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <path d="M12 6v6l4 2" /></svg>
                </div>
                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Belum ada tagihan</h4>
                <p class="text-sm text-bgray-500">Tambahkan tagihan rutin Anda di formulir sebelah kiri.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
