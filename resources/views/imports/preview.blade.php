@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Import Preview</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Periksa hasil parsing sebelum transaksi disimpan.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Data akan masuk ke wallet <span class="font-bold text-white">{{ $wallet->bank_name }}</span>. Anda bisa membatalkan item yang tidak relevan sebelum proses simpan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(!empty($transactions))
                <span class="rounded-full bg-emerald-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-200">
                    {{ $transactions[0]['bank_detected'] }}
                </span>
                @endif
                <span class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-300">
                    {{ count($transactions) }} transaksi
                </span>
            </div>
        </div>
    </section>

    <form action="{{ route('bank-mutations.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Wallet Tujuan</p>
                <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ $wallet->bank_name }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Semua transaksi terpilih akan masuk ke akun ini.</p>
            </article>
            <article class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Baris Terbaca</p>
                <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ count($transactions) }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tinjau kembali sebelum Anda commit ke transaksi final.</p>
            </article>
            <article class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">File Terbaca</p>
                <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ count($fileSummaries ?? []) }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Batch import dari beberapa file mutasi sekaligus.</p>
            </article>
        </section>

        @if(!empty($fileSummaries))
        <section class="app-panel overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <p class="app-eyebrow bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">Batch Summary</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Ringkasan per file</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pastikan setiap file terbaca sebelum menyimpan transaksi.</p>
            </div>
            <div class="grid gap-3 p-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($fileSummaries as $summary)
                <article class="rounded-2xl border border-slate-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900/70">
                    <p class="truncate text-sm font-black text-slate-950 dark:text-white">{{ $summary['name'] }}</p>
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                        <div class="rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/70">
                            <p class="text-[10px] font-bold uppercase text-slate-400">Baris</p>
                            <p class="mt-1 text-sm font-black text-slate-900 dark:text-white">{{ $summary['count'] }}</p>
                        </div>
                        <div class="rounded-xl bg-emerald-50 px-3 py-2 dark:bg-emerald-950/30">
                            <p class="text-[10px] font-bold uppercase text-emerald-600 dark:text-emerald-200">Masuk</p>
                            <p class="mt-1 text-xs font-black text-emerald-700 dark:text-emerald-200">Rp {{ number_format($summary['income'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl bg-rose-50 px-3 py-2 dark:bg-rose-950/30">
                            <p class="text-[10px] font-bold uppercase text-rose-600 dark:text-rose-200">Keluar</p>
                            <p class="mt-1 text-xs font-black text-rose-600 dark:text-rose-200">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif

        <section class="app-panel overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <p class="app-eyebrow bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">Parsed Rows</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Data hasil pembacaan PDF</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Biarkan centang aktif pada transaksi yang ingin disimpan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1040px] text-left">
                    <thead>
                    <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-[0.24em] text-slate-400 dark:border-slate-800 dark:text-slate-500">
                            <th class="px-4 py-3 text-center">Pilih</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">File</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3">Tebakan Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($transactions as $trx)
                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-900/60">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" name="transactions[]" value="{{ json_encode($trx) }}" checked class="h-5 w-5 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950">
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($trx['date'])->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                <span class="block max-w-[160px] truncate">{{ $trx['source_file'] ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-300">
                                <span class="block max-w-md truncate">{{ $trx['description'] }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @if($trx['type'] == 'income')
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-200">Masuk</span>
                                @else
                                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-600 dark:bg-rose-950/70 dark:text-rose-200">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right text-sm font-black {{ $trx['type'] == 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                                Rp {{ number_format($trx['amount'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    {{ $trx['category_guess'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <p class="text-lg font-bold text-slate-800 dark:text-white">Tidak ada data yang terbaca</p>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pastikan format file sesuai dengan mutasi yang didukung aplikasi.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('bank-mutations.create') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">
                Upload Ulang
            </a>
            <button type="submit" class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(79,70,229,0.22)] transition hover:-translate-y-0.5 hover:bg-indigo-700">
                Simpan Transaksi Terpilih
            </button>
        </div>
    </form>
</div>
@endsection
