@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <div class="mb-10 flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300 shadow-sm">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                <line x1="7" y1="7" x2="7.01" y2="7" /></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Kelola Kategori</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400">Atur label transaksi agar laporan lebih rapi.</p>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-8 items-start">

        <div class="w-full xl:w-1/3 xl:sticky xl:top-24 z-10">
            <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-xl shadow-bgray-100/50 dark:shadow-none border border-bgray-100 dark:border-darkblack-400">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Kategori Baru</h3>
                    <div class="p-2 bg-purple-50 dark:bg-darkblack-500 rounded-lg text-purple-500">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" /></svg>
                    </div>
                </div>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-8 group">
                        <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Nama Label</label>
                        <div class="relative">
                            <input type="text" name="name" class="w-full rounded-xl border-2 border-bgray-100 bg-bgray-50 px-4 py-3.5 pl-11 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" placeholder="Contoh: Belanja, Gaji" required>
                        </div>
                    </div>

                    <div class="mb-8 group">
                        <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">
                            Tipe Transaksi
                        </label>
                        <div class="relative">
                            <select name="type" class="w-full appearance-none rounded-xl border-2 border-bgray-100 bg-bgray-50 px-4 py-3.5 pl-12 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all">
                                <option value="expense">Pengeluaran (Expense)</option>
                                <option value="income">Pemasukan (Income)</option>
                            </select>


                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400 pointer-events-none">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-purple-600 py-4 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:bg-purple-700 hover:shadow-purple-500/50 active:scale-95">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full xl:w-2/3">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Daftar Kategori</h3>
                <span class="rounded-full bg-bgray-100 px-3 py-1 text-xs font-bold text-bgray-600 dark:bg-darkblack-500 dark:text-bgray-50">{{ count($categories) }} Label</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $cat)
                @php
                $isIncome = $cat->type == 'income';
                $borderColor = $isIncome ? 'border-l-4 border-success-300' : 'border-l-4 border-error-300';
                $iconBg = $isIncome ? 'bg-success-50 text-success-300' : 'bg-error-50 text-error-300';
                $icon = $isIncome
                ? '
                <path d="M12 19V5M5 12l7-7 7 7" />'
                : '
                <path d="M12 5v14M12 19l4-4M12 19l-4-4" />';
                $typeLabel = $isIncome ? 'Pemasukan' : 'Pengeluaran';
                @endphp

                <div class="group relative flex items-center justify-between rounded-xl bg-white p-4 shadow-sm border border-bgray-100 dark:bg-darkblack-600 dark:border-darkblack-400 transition-all hover:shadow-md hover:-translate-y-1 {{ $borderColor }}">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $iconBg }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                {!! $icon !!}
                            </svg>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-bgray-900 dark:text-white">{{ $cat->name }}</h4>
                            <p class="text-[10px] font-medium text-bgray-400 uppercase tracking-wide">{{ $typeLabel }}</p>
                        </div>
                    </div>

                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 p-2 rounded-lg text-bgray-400 hover:text-error-300 hover:bg-error-50" title="Hapus">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            @if($categories->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-darkblack-600 rounded-2xl border-2 border-dashed border-bgray-300 dark:border-darkblack-400 mt-4">
                <div class="h-16 w-16 rounded-full bg-bgray-100 flex items-center justify-center text-bgray-400 mb-4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                        <line x1="7" y1="7" x2="7.01" y2="7" /></svg>
                </div>
                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Belum ada kategori</h4>
                <p class="text-sm text-bgray-500">Tambahkan kategori baru di formulir sebelah kiri.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
