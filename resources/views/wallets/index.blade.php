@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <div class="mb-10 flex items-center gap-5">

<div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-white ring-1 ring-blue-100 shadow-sm dark:bg-blue-900/30 dark:text-white dark:ring-blue-800">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 12V8H6a2 2 0 0 1-2-2 2 2 0 0 1 2-2h12v4" />
        <path d="M4 6v12a2 2 0 0 0 2 2h14v-4" />
        <path d="M18 12a2 2 0 0 0-2 2 2 2 0 0 0 2 2h4v-4h-4z" />
    </svg>
</div>

        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Kelola Dompet & Bank</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-0.5">Pusat pengaturan seluruh sumber dana Anda.</p>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-8">

        <div class="w-full xl:w-1/3 h-fit">
            <div class="rounded-xl bg-white p-6 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400 sticky top-24">
                <div class="mb-6 flex items-center gap-4 border-b border-bgray-100 pb-5 dark:border-darkblack-400">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-bgray-50 text-bgray-900 ring-1 ring-bgray-100 dark:bg-darkblack-500 dark:text-white dark:ring-darkblack-400">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-bgray-900 dark:text-white leading-tight">Tambah Akun Baru</h3>
                        <p class="text-xs font-medium text-bgray-500 dark:text-bgray-400">Masukan detail bank atau e-wallet.</p>
                    </div>
                </div>

                <form action="{{ route('wallets.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="mb-2 block text-sm font-bold text-bgray-700 dark:text-white">Nama Bank / E-Wallet</label>
                        <div class="relative">
                            <input type="text" name="bank_name" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 px-4 py-3 pl-10 text-sm font-semibold text-bgray-900 focus:border-blue-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-colors" placeholder="Contoh: Jenius, GoPay, Tunai" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-bold text-bgray-700 dark:text-white">Saldo Awal</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-bgray-500 group-focus-within:text-blue-500">Rp</span>
                            <input type="number" name="balance" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 px-4 py-3 pl-10 text-lg font-bold text-bgray-900 focus:border-blue-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-colors" placeholder="0" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-blue-700 hover:shadow-blue-500/50 active:scale-95">
                        Simpan Akun
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full xl:w-2/3">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Daftar Akun Saya</h3>
                <span class="rounded-full bg-bgray-100 px-3 py-1 text-xs font-bold text-bgray-600 dark:bg-darkblack-500 dark:text-bgray-50">{{ count($wallets) }} Akun</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-2">
                @foreach($wallets as $wallet)
                <div class="group relative overflow-hidden rounded-xl bg-white p-5 dark:bg-darkblack-600 shadow-sm border border-bgray-200 dark:border-darkblack-400 transition-all hover:shadow-md hover:border-blue-300 dark:hover:border-blue-700">

                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50 dark:bg-blue-900/20 transition-transform group-hover:scale-150"></div>

                    <div class="relative flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="h-8 w-8 flex items-center justify-center rounded-full bg-bgray-100 text-bgray-600 dark:bg-darkblack-500 dark:text-white">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">{{ $wallet->bank_name }}</h4>
                            </div>
                            <span class="text-xs font-medium text-bgray-500 uppercase tracking-wide">Dompet Utama</span>
                        </div>

                        <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini? Hati-hati, data transaksi terkait mungkin error.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-bgray-400 hover:bg-error-50 hover:text-error-300 transition-colors" title="Hapus Akun">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm text-bgray-500 mb-1">Saldo Saat Ini</p>
                        <p class="text-2xl font-extrabold text-bgray-900 dark:text-white tracking-tight group-hover:text-blue-600 transition-colors">
                            Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            @if($wallets->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-darkblack-600 rounded-xl border border-dashed border-bgray-300 dark:border-darkblack-400">
                <div class="h-16 w-16 rounded-full bg-bgray-100 flex items-center justify-center text-bgray-400 mb-4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <path d="M12 12h.01" /></svg>
                </div>
                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Belum ada akun</h4>
                <p class="text-sm text-bgray-500">Tambahkan akun bank atau dompet digital Anda di sebelah kiri.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
