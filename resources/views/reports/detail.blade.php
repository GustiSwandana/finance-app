@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 shadow-sm">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Laporan Detail</h2>
                <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Filter, cari, dan analisis riwayat transaksi.</p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('reports.detail.excel', request()->all()) }}" class="flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-green-700 shadow-sm transition-all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" /></svg>
                Excel
            </a>

            <a href="{{ route('reports.detail.pdf', request()->all()) }}" class="flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 shadow-sm transition-all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" /></svg>
                PDF
            </a>
        </div>
    </div>

    <div class="mb-8 rounded-2xl bg-white p-8 shadow-sm border border-bgray-100 dark:bg-darkblack-600 dark:border-darkblack-400">
        <form action="{{ route('reports.detail') }}" method="GET">

            <div class="mb-6">
                <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block tracking-wider">Pencarian</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-bgray-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                    </div>
                    <input type="text" name="search" id="report-search" value="{{ $search }}" placeholder="Cari deskripsi transaksi atau nama kategori..." class="w-full h-12 rounded-xl border-2 border-bgray-100 bg-bgray-50 pl-12 text-sm font-medium focus:border-indigo-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">

                <div class="group">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block tracking-wider">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full h-11 rounded-xl border-2 border-bgray-100 bg-white px-3 text-sm font-bold text-bgray-700 focus:border-indigo-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert transition-all">
                </div>

                <div class="group">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block tracking-wider">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full h-11 rounded-xl border-2 border-bgray-100 bg-white px-3 text-sm font-bold text-bgray-700 focus:border-indigo-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert transition-all">
                </div>

                <div class="group">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block tracking-wider">Bank</label>
                    <div class="relative">
                        <select name="wallet_id" class="w-full h-11 appearance-none rounded-xl border-2 border-bgray-100 bg-white px-4 text-sm font-bold text-bgray-700 focus:border-indigo-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all">
                            <option value="all">Semua Bank</option>
                            @foreach($wallets as $w)
                            <option value="{{ $w->id }}" {{ $walletId == $w->id ? 'selected' : '' }}>{{ $w->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block tracking-wider">Opsi</label>
                    <div class="flex gap-2">
                        <div class="relative w-1/2">
                            <select name="type" class="w-full h-11 appearance-none rounded-xl border-2 border-bgray-100 bg-white px-3 text-sm font-bold text-bgray-700 focus:border-indigo-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer">
                                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Masuk</option>
                                <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>
                        <div class="relative w-1/2">
                            <select name="sort" class="w-full h-11 appearance-none rounded-xl border-2 border-bgray-100 bg-white px-3 text-sm font-bold text-bgray-700 focus:border-indigo-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="highest" {{ $sort == 'highest' ? 'selected' : '' }}>Terbesar</option>
                                <option value="lowest" {{ $sort == 'lowest' ? 'selected' : '' }}>Terkecil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full h-11 flex justify-center items-center gap-2 rounded-xl bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" /></svg>
                        Terapkan
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="rounded-2xl bg-green-50 p-6 border border-green-200 dark:bg-darkblack-600 dark:border-green-900">
            <p class="text-xs font-bold uppercase text-green-500 mb-1">Total Pemasukan</p>
            <h3 class="text-2xl font-black text-green-600 dark:text-green-400">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
        <div class="rounded-2xl bg-red-50 p-6 border border-red-200 dark:bg-darkblack-600 dark:border-red-900">
            <p class="text-xs font-bold uppercase text-red-500 mb-1">Total Pengeluaran</p>
            <h3 class="text-2xl font-black text-red-600 dark:text-red-400">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
        </div>
        <div class="rounded-2xl bg-blue-50 p-6 border border-blue-200 dark:bg-darkblack-600 dark:border-blue-900">
            <p class="text-xs font-bold uppercase text-blue-500 mb-1">Selisih (Net)</p>
            <h3 class="text-2xl font-black {{ $netBalance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                Rp {{ number_format($netBalance, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Hasil Pencarian</h3>
            <span class="text-xs font-bold text-bgray-500 bg-bgray-100 px-3 py-1 rounded-full border border-bgray-200 dark:border-darkblack-500">{{ count($transactions) }} Data Ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead>
                    <tr class="border-b border-bgray-100 dark:border-darkblack-400 text-xs font-bold text-bgray-400 uppercase">
                        <th class="px-4 py-3">Tanggal & Waktu</th>
                        <th class="px-4 py-3">Kategori / Ket</th>
                        <th class="px-4 py-3">Bank</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-500">
                    @forelse($transactions as $data)
                    <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500 transition-colors">

                        <td class="px-4 py-4">
                            <div class="text-sm font-bold text-bgray-700 dark:text-white">
                                {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-bgray-400 font-medium mt-0.5 flex items-center gap-1">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" /></svg>
                                {{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }}
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $data->type == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        @if($data->type == 'income')
                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                        @elseif($data->type == 'expense')
                                        <path d="M12 5v14M12 19l4-4M12 19l-4-4" />
                                        @else
                                        <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" /> @endif
                                    </svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-bgray-900 dark:text-white truncate max-w-[150px]">{{ $data->category->name ?? 'Transfer' }}</p>
                                    <p class="text-xs text-bgray-500 truncate max-w-[200px]" title="{{ $data->description }}">{{ $data->description }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-block rounded-lg bg-bgray-100 border border-bgray-200 px-2.5 py-1 text-xs font-bold text-bgray-600 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                {{ $data->wallet->bank_name }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-right">
                            <span class="text-sm font-black {{ $data->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $data->type == 'income' ? '+' : ($data->type == 'expense' ? '-' : '') }}
                                Rp {{ number_format($data->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center text-bgray-400">
                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                                <p class="text-sm">Tidak ada transaksi yang cocok dengan filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'report-search') {
            if (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b') {
                e.stopPropagation();
            }
        }
    }, true);

</script>
@endpush
