@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-7 md:px-8 md:py-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Cashflow Desk</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Catat pemasukan dan pengeluaran tanpa ribet.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Halaman ini jadi pusat operasional harian: input transaksi manual, tambah bank atau kategori dengan cepat, lalu cek histori terbaru di tempat yang sama.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="min-w-[170px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Wallet</p>
                    <p class="mt-3 text-3xl font-black text-white">{{ $wallets->count() }}</p>
                    <p class="mt-2 text-xs text-white/65">Sumber dana aktif</p>
                </div>
                <div class="min-w-[170px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Pemasukan</p>
                    <p class="mt-3 text-3xl font-black text-emerald-300">{{ $incomeCategories->count() }}</p>
                    <p class="mt-2 text-xs text-white/65">Kategori masuk</p>
                </div>
                <div class="min-w-[170px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Pengeluaran</p>
                    <p class="mt-3 text-3xl font-black text-rose-300">{{ $expenseCategories->count() }}</p>
                    <p class="mt-2 text-xs text-white/65">Kategori keluar</p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,380px)_minmax(0,1fr)] xl:items-start">
        <div class="space-y-6 xl:sticky xl:top-24">
            <section class="overflow-hidden rounded-[32px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.24),_transparent_42%),linear-gradient(135deg,#0f766e,#155e75_55%,#1d4ed8)] p-6 text-white shadow-[0_28px_80px_rgba(15,23,42,0.18)]">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/15">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7V4h3" />
                            <path d="M20 7V4h-3" />
                            <path d="M4 17v3h3" />
                            <path d="M20 17v3h-3" />
                            <path d="M9 12h6" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-black tracking-tight">AI Scan Struk</h2>
                            <span class="rounded-full border border-white/25 bg-white/15 px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.24em]">Fast lane</span>
                        </div>
                        <p class="mt-2 text-sm text-cyan-50/90">Upload foto struk, pilih wallet tujuan, lalu biarkan sistem bantu isi transaksi awal.</p>
                    </div>
                </div>

                <form action="{{ route('transactions.scan') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-3">
                    @csrf
                    <div>
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.26em] text-cyan-50/70">Wallet tujuan</label>
                        <select name="wallet_id" class="w-full rounded-2xl border border-white/20 bg-white px-4 py-3 text-sm font-bold text-slate-900 focus:border-white focus:ring-0">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-900">
                        <input type="file" name="receipt" class="hidden" onchange="this.form.submit()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        Upload Foto Struk
                    </label>
                </form>
            </section>

            <section class="app-panel p-6">
                <div class="rounded-2xl bg-slate-100 p-1.5">
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="switchTab('income')" id="tab-income" type="button" class="rounded-2xl px-4 py-3 text-sm font-bold transition-all">
                            Pemasukan
                        </button>
                        <button onclick="switchTab('expense')" id="tab-expense" type="button" class="rounded-2xl px-4 py-3 text-sm font-bold transition-all">
                            Pengeluaran
                        </button>
                    </div>
                </div>

                @if($errors->any())
                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

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

                <div id="form-income" class="mt-6 block">
                    <form action="{{ route('income.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.26em] text-slate-400">Nominal masuk</label>
                            <div class="flex items-center rounded-[28px] border border-emerald-100 bg-emerald-50/70 px-4 py-4 transition focus-within:border-emerald-300 focus-within:bg-white">
                                <span class="pr-3 text-2xl font-black text-emerald-500">Rp</span>
                                <input type="number" name="amount" class="w-full border-none bg-transparent p-0 text-3xl font-black tracking-tight text-slate-950 placeholder:text-slate-300 focus:ring-0" placeholder="0" required>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-500">Masuk ke wallet</label>
                                    <button type="button" onclick="openQuickAdd('wallet')" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">+ Tambah wallet</button>
                                </div>
                                <select name="wallet_id" id="income_wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0">
                                    @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-500">Kategori</label>
                                    <button type="button" onclick="openQuickAdd('income')" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">+ Tambah kategori</button>
                                </div>
                                <select name="category_id" id="income_category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0">
                                    @foreach($incomeCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)]">
                            <div>
                                <label class="mb-2 block text-xs font-bold text-slate-500">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0">
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-bold text-slate-500">Catatan</label>
                                <input type="text" name="description" placeholder="Contoh: bonus proyek, refund, titipan" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-0">
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-4 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.25)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                            Simpan Pemasukan
                        </button>
                    </form>
                </div>

                <div id="form-expense" class="mt-6 hidden">
                    <form action="{{ route('expense.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.26em] text-slate-400">Nominal keluar</label>
                            <div class="flex items-center rounded-[28px] border border-rose-100 bg-rose-50/70 px-4 py-4 transition focus-within:border-rose-300 focus-within:bg-white">
                                <span class="pr-3 text-2xl font-black text-rose-500">Rp</span>
                                <input type="number" name="amount" class="w-full border-none bg-transparent p-0 text-3xl font-black tracking-tight text-slate-950 placeholder:text-slate-300 focus:ring-0" placeholder="0" required>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-500">Ambil dari wallet</label>
                                    <button type="button" onclick="openQuickAdd('wallet')" class="text-xs font-bold text-rose-600 hover:text-rose-700">+ Tambah wallet</button>
                                </div>
                                <select name="wallet_id" id="expense_wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0">
                                    @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->bank_name }} ({{ number_format($wallet->balance) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-500">Kategori</label>
                                    <button type="button" onclick="openQuickAdd('expense')" class="text-xs font-bold text-rose-600 hover:text-rose-700">+ Tambah kategori</button>
                                </div>
                                <select name="category_id" id="expense_category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0">
                                    @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-[170px_minmax(0,1fr)]">
                            <div>
                                <label class="mb-2 block text-xs font-bold text-slate-500">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0">
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-bold text-slate-500">Catatan</label>
                                <input type="text" name="description" placeholder="Contoh: belanja stok, listrik, operasional" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-rose-300 focus:bg-white focus:ring-0">
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-rose-500 px-4 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(244,63,94,0.22)] transition hover:-translate-y-0.5 hover:bg-rose-600">
                            Simpan Pengeluaran
                        </button>
                    </form>
                </div>
            </section>
        </div>

        <section class="app-panel p-6">
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="app-eyebrow">Recent Activity</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Riwayat transaksi terbaru</h2>
                    <p class="mt-2 text-sm text-slate-500">Pantau transaksi terakhir dan edit langsung jika ada input yang perlu dibetulkan.</p>
                </div>
                <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500">
                    {{ date('F Y') }}
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3">Wallet</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $data)
                        <tr class="group transition hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $data->type == 'income' ? 'bg-emerald-100 text-emerald-600' : ($data->type == 'expense' ? 'bg-rose-100 text-rose-500' : 'bg-amber-100 text-amber-600') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            @if($data->type == 'income')
                                            <path d="M12 5v14M12 5l4 4M12 5L8 9" />
                                            @elseif($data->type == 'expense')
                                            <path d="M12 19V5M12 19l4-4M12 19l-4-4" />
                                            @else
                                            <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" />
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $data->description ?: 'Tanpa catatan' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }} | {{ ucfirst($data->type) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-600">
                                    {{ $data->wallet->bank_name }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm font-semibold text-slate-700">{{ $data->category->name ?? 'Transfer' }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-black tracking-tight {{ $data->type == 'income' ? 'text-emerald-600' : ($data->type == 'expense' ? 'text-rose-500' : 'text-amber-600') }}">
                                    {{ $data->type == 'income' ? '+' : ($data->type == 'expense' ? '-' : '') }} Rp {{ number_format($data->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('transactions.edit', $data->id) }}" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600" aria-label="Edit transaksi" title="Edit transaksi">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500" aria-label="Hapus transaksi" title="Hapus transaksi">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3 text-slate-400">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-slate-700">Belum ada transaksi</p>
                                        <p class="mt-1 text-sm">Mulai dari form di sebelah kiri untuk membangun histori keuangan Anda.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t border-slate-200 pt-4">
                {{ $transactions->links('pagination::simple-tailwind') }}
            </div>
        </section>
    </div>
</div>

<div id="quick-add-modal" class="app-modal" aria-hidden="true">
    <div class="app-modal__backdrop" onclick="closeQuickAdd()"></div>
    <div class="app-modal__panel p-6 sm:p-7" role="dialog" aria-modal="true" aria-labelledby="quick-add-title">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5 dark:border-slate-800">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Tambah Cepat</p>
                <h2 id="quick-add-title" class="mt-2 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Tambah data</h2>
                <p id="quick-add-help" class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Data baru langsung muncul di pilihan transaksi.</p>
            </div>
            <button type="button" onclick="closeQuickAdd()" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" aria-label="Tutup modal">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <form id="quick-add-form" class="mt-6 space-y-5">
            <input type="hidden" id="quick-add-kind" value="wallet">
            <div>
                <label for="quick-add-name" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Nama</label>
                <input id="quick-add-name" type="text" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-teal-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500" required>
            </div>
            <div id="quick-add-balance-wrap">
                <label for="quick-add-balance" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Saldo awal</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                    <input id="quick-add-balance" type="number" value="0" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 pl-10 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-teal-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                </div>
            </div>
            <div id="quick-add-message" class="hidden rounded-2xl border px-4 py-3 text-sm font-semibold"></div>
            <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3.5 text-sm font-bold text-white transition hover:bg-slate-900 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                Simpan dan Pakai
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(type) {
        const formIncome = document.getElementById('form-income');
        const formExpense = document.getElementById('form-expense');
        const tabIncome = document.getElementById('tab-income');
        const tabExpense = document.getElementById('tab-expense');

        if (type === 'income') {
            formIncome.classList.remove('hidden');
            formExpense.classList.add('hidden');

            tabIncome.classList.add('bg-white', 'text-emerald-600', 'shadow-sm');
            tabIncome.classList.remove('text-slate-500');

            tabExpense.classList.remove('bg-white', 'text-rose-500', 'shadow-sm');
            tabExpense.classList.add('text-slate-500');
        } else {
            formExpense.classList.remove('hidden');
            formIncome.classList.add('hidden');

            tabExpense.classList.add('bg-white', 'text-rose-500', 'shadow-sm');
            tabExpense.classList.remove('text-slate-500');

            tabIncome.classList.remove('bg-white', 'text-emerald-600', 'shadow-sm');
            tabIncome.classList.add('text-slate-500');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        switchTab(urlParams.get('tab') === 'expense' ? 'expense' : 'income');
    });

    const quickAddModal = document.getElementById('quick-add-modal');
    const quickAddForm = document.getElementById('quick-add-form');
    const quickAddKind = document.getElementById('quick-add-kind');
    const quickAddName = document.getElementById('quick-add-name');
    const quickAddBalance = document.getElementById('quick-add-balance');
    const quickAddBalanceWrap = document.getElementById('quick-add-balance-wrap');
    const quickAddTitle = document.getElementById('quick-add-title');
    const quickAddHelp = document.getElementById('quick-add-help');
    const quickAddMessage = document.getElementById('quick-add-message');

    function openQuickAdd(kind) {
        quickAddKind.value = kind;
        quickAddForm.reset();
        quickAddBalance.value = '0';
        quickAddMessage.className = 'hidden rounded-2xl border px-4 py-3 text-sm font-semibold';
        quickAddMessage.textContent = '';

        const isWallet = kind === 'wallet';
        quickAddTitle.textContent = isWallet ? 'Tambah wallet baru' : 'Tambah kategori baru';
        quickAddHelp.textContent = isWallet
            ? 'Wallet baru langsung tersedia untuk pemasukan dan pengeluaran.'
            : `Kategori ${kind === 'income' ? 'pemasukan' : 'pengeluaran'} baru langsung tersedia di form aktif.`;
        quickAddName.placeholder = isWallet ? 'Contoh: BCA, Jago, GoPay' : 'Contoh: Belanja Harian, Gaji';
        quickAddBalanceWrap.classList.toggle('hidden', !isWallet);
        quickAddModal.classList.add('is-open');
        quickAddModal.setAttribute('aria-hidden', 'false');

        setTimeout(() => quickAddName.focus(), 50);
    }

    function closeQuickAdd() {
        quickAddModal.classList.remove('is-open');
        quickAddModal.setAttribute('aria-hidden', 'true');
    }

    function showQuickAddMessage(message, isError = false) {
        quickAddMessage.textContent = message;
        quickAddMessage.className = isError
            ? 'rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200'
            : 'rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200';
    }

    function addOption(selectId, text, value, selected = true) {
        const select = document.getElementById(selectId);
        if (!select) return;
        select.add(new Option(text, value, selected, selected), undefined);
    }

    quickAddForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const kind = quickAddKind.value;
        const name = quickAddName.value.trim();

        if (!name) {
            showQuickAddMessage('Nama wajib diisi.', true);
            return;
        }

        const button = quickAddForm.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Menyimpan...';

        try {
            const isWallet = kind === 'wallet';
            const response = await fetch(isWallet ? "{{ route('wallets.storeAjax') }}" : "{{ route('categories.storeAjax') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(isWallet ? {
                    bank_name: name,
                    balance: quickAddBalance.value || 0
                } : {
                    name: name,
                    type: kind
                })
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Data belum bisa disimpan.');
            }

            if (isWallet) {
                addOption('income_wallet_id', result.data.bank_name, result.data.id);
                addOption('expense_wallet_id', `${result.data.bank_name} (${Number(quickAddBalance.value || 0).toLocaleString('id-ID')})`, result.data.id);
                showQuickAddMessage('Wallet berhasil ditambahkan.');
            } else {
                addOption(kind === 'income' ? 'income_category_id' : 'expense_category_id', result.data.name, result.data.id);
                showQuickAddMessage('Kategori berhasil ditambahkan.');
            }

            setTimeout(closeQuickAdd, 450);
        } catch (error) {
            showQuickAddMessage(error.message || 'Gagal menambahkan data.', true);
        } finally {
            button.disabled = false;
            button.textContent = 'Simpan dan Pakai';
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && quickAddModal.classList.contains('is-open')) {
            closeQuickAdd();
        }
    });
</script>
@endpush
