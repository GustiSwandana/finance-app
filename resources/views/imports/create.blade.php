@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Import Mutasi Bank</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Upload file mutasi bank lalu preview sebelum disimpan.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                Alur ini cocok untuk backfill data historis. Anda bisa pilih lebih dari satu PDF sekaligus, lalu periksa hasil parsing sebelum masuk ke transaksi.
            </p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        @if (session('error'))
        <div class="rounded-[24px] border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-200">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mt-6 rounded-[24px] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
            <p class="font-bold">File belum bisa diproses</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('bank-mutations.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Pilih akun bank</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        @foreach($wallets as $w)
                        <option value="{{ $w->id }}" {{ (int) old('wallet_id') === $w->id ? 'selected' : '' }}>{{ $w->bank_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Jenis mutasi bank</label>
                    <select name="bank_source" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-indigo-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white">
                        <option value="auto" @selected(old('bank_source') === 'auto')>Deteksi otomatis dari PDF</option>
                        <option value="bca" @selected(old('bank_source') === 'bca')>BCA</option>
                        <option value="mandiri" @selected(old('bank_source') === 'mandiri')>Mandiri / Livin</option>
                        <option value="bri" @selected(old('bank_source') === 'bri')>BRI / BritAma</option>
                        <option value="generic" @selected(old('bank_source') === 'generic')>File tabel umum CSV/XLSX</option>
                    </select>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pilih bank jika file berasal dari bank tertentu agar parsing lebih akurat.</p>
                </div>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Format didukung</p>
                <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">PDF, CSV, TXT, XLSX, XLS</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Untuk CSV/XLSX gunakan kolom seperti tanggal/date, deskripsi/keterangan, debit, credit/kredit, nominal/amount, atau tipe/type.
                </p>
            </div>

            <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-950/70">
                <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">File mutasi bank</label>
                <input type="file" name="bank_files[]" accept=".pdf,.csv,.txt,.xlsx,.xls" multiple class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-bold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-200 dark:file:bg-indigo-950/70 dark:file:text-indigo-200 dark:hover:file:bg-indigo-900" required>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Anda bisa upload sampai 20 file sekaligus. Tahan `Ctrl` atau `Shift` saat memilih file mutasi.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Sumber</p>
                    <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">{{ $wallets->count() }} wallet</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih tujuan import sebelum upload PDF.</p>
                </div>
                <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Preview</p>
                    <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">Selektif</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Semua hasil bisa ditinjau dulu sebelum disimpan.</p>
                </div>
                <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Duplikat</p>
                    <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">Manual check</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Hapus centang transaksi yang sudah pernah masuk.</p>
                </div>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(79,70,229,0.22)] transition hover:-translate-y-0.5 hover:bg-indigo-700">
                Scan File dan Preview Data
            </button>
        </form>
    </section>
</div>
@endsection
