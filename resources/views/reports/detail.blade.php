@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Transaction Explorer</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Telusuri transaksi dengan filter yang lebih nyaman dibaca.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Gunakan pencarian, rentang tanggal, wallet, tipe, dan sorting untuk menemukan transaksi tertentu, lalu ekspor hasil yang sedang aktif tanpa perlu menyusun ulang filter.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('reports.detail.excel', request()->all()) }}" class="rounded-2xl bg-white px-4 py-3 text-sm font-bold text-emerald-700 transition hover:bg-emerald-50">
                    Export Excel
                </a>
                <a href="{{ route('reports.detail.pdf', request()->all()) }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-900">
                    Export PDF
                </a>
            </div>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <div class="border-b border-slate-200 pb-5">
            <p class="app-eyebrow bg-slate-100 text-slate-600">Filter Panel</p>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Atur pencarian laporan</h2>
            <p class="mt-2 text-sm text-slate-500">Semua ekspor akan mengikuti filter yang sedang aktif di halaman ini.</p>
        </div>

        <form action="{{ route('reports.detail') }}" method="GET" class="mt-6 space-y-5">
            <div>
                <label for="report-search" class="mb-2 block text-sm font-bold text-slate-700">Pencarian</label>
                <input type="text" name="search" id="report-search" value="{{ $search }}" placeholder="Cari deskripsi transaksi atau nama kategori..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-indigo-300 focus:bg-white focus:ring-0">
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Dari tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Sampai tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Wallet</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0">
                        <option value="all">Semua wallet</option>
                        @foreach($wallets as $w)
                        <option value="{{ $w->id }}" {{ $walletId == $w->id ? 'selected' : '' }}>{{ $w->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tipe</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        <option value="transfer" {{ $type == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Urutkan</label>
                    <select name="sort" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0">
                        <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="highest" {{ $sort == 'highest' ? 'selected' : '' }}>Nominal terbesar</option>
                        <option value="lowest" {{ $sort == 'lowest' ? 'selected' : '' }}>Nominal terkecil</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-200 pt-5">
                <a href="{{ route('reports.detail') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                    Reset
                </a>
                <button type="submit" class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(79,70,229,0.18)] transition hover:-translate-y-0.5 hover:bg-indigo-700">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </section>

    <section class="grid gap-5 md:grid-cols-3">
        <div class="app-panel rounded-3xl border border-emerald-100 bg-emerald-50/60 p-6">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Total pemasukan</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-emerald-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="app-panel rounded-3xl border border-rose-100 bg-rose-50/60 p-6">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-rose-600">Total pengeluaran</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-rose-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="app-panel rounded-3xl border border-sky-100 bg-sky-50/60 p-6">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-700">Saldo bersih</p>
            <p class="mt-3 text-3xl font-black tracking-tight {{ $netBalance >= 0 ? 'text-sky-700' : 'text-rose-600' }}">
                Rp {{ number_format($netBalance, 0, ',', '.') }}
            </p>
        </div>
    </section>

    <section class="app-panel overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow bg-slate-100 text-slate-600">Search Result</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Daftar transaksi</h2>
            </div>
            <span class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500">
                {{ $transactions->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                        <th class="px-4 py-4">Tanggal</th>
                        <th class="px-4 py-4">Kategori</th>
                        <th class="px-4 py-4">Deskripsi</th>
                        <th class="px-4 py-4">Wallet</th>
                        <th class="px-4 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $data)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-4 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $data->type == 'income' ? 'bg-emerald-100 text-emerald-700' : ($data->type == 'expense' ? 'bg-rose-100 text-rose-500' : 'bg-amber-100 text-amber-600') }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                        @if($data->type == 'income')
                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                        @elseif($data->type == 'expense')
                                        <path d="M12 5v14M12 19l4-4M12 19l-4-4" />
                                        @else
                                        <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" />
                                        @endif
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-900">{{ $data->category->name ?? 'Transfer' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="max-w-[260px] truncate text-sm text-slate-600" title="{{ $data->description }}">{{ $data->description ?: 'Tanpa deskripsi' }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-600">
                                {{ $data->wallet->bank_name }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="text-sm font-black {{ $data->type == 'income' ? 'text-emerald-700' : ($data->type == 'expense' ? 'text-rose-500' : 'text-amber-600') }}">
                                {{ $data->type == 'income' ? '+' : ($data->type == 'expense' ? '-' : '') }} Rp {{ number_format($data->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <p class="text-lg font-bold text-slate-800">Tidak ada transaksi yang cocok</p>
                            <p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci atau rentang tanggal untuk memperluas hasil.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4">
            {{ $transactions->links('pagination::simple-tailwind') }}
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'report-search' && (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b')) {
            e.stopPropagation();
        }
    }, true);
</script>
@endpush
