@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-7 md:px-8 md:py-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Monthly Budget</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Awasi kategori yang mulai melewati batas.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Setiap kartu menunjukkan pemakaian aktual bulan berjalan dibanding limit kategori. Anda bisa ubah limit langsung dari kartu tanpa pindah halaman.
                </p>
            </div>
            <div class="min-w-[220px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Periode</p>
                <p class="mt-3 text-3xl font-black text-white">{{ date('F Y') }}</p>
                <p class="mt-2 text-xs text-white/65">Anggaran aktif saat ini</p>
            </div>
        </div>
    </section>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($budgetReport as $item)
        @php
            $percent = $item->percentage;
            if ($percent > 100) {
                $color = 'bg-rose-500';
                $textColor = 'text-rose-500';
                $panel = 'border-rose-100 bg-rose-50/50';
                $status = 'Melewati batas';
            } elseif ($percent >= 75) {
                $color = 'bg-amber-400';
                $textColor = 'text-amber-600';
                $panel = 'border-amber-100 bg-amber-50/50';
                $status = 'Perlu perhatian';
            } else {
                $color = 'bg-emerald-500';
                $textColor = 'text-emerald-600';
                $panel = 'border-emerald-100 bg-emerald-50/45';
                $status = 'Masih aman';
            }

            $width = min($percent, 100);
        @endphp

        <section class="app-panel {{ $panel }} border p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="app-eyebrow">Category</p>
                    <h2 class="mt-3 text-xl font-black tracking-tight text-slate-950">{{ $item->category->name }}</h2>
                </div>
                <span class="rounded-full bg-white/90 px-3 py-2 text-xs font-bold uppercase tracking-[0.18em] {{ $textColor }}">
                    {{ number_format($percent, 1) }}%
                </span>
            </div>

            <div class="mt-6">
                <div class="flex items-end gap-2">
                    <p class="text-3xl font-black tracking-tight text-slate-950">Rp {{ number_format($item->spent, 0, ',', '.') }}</p>
                    <p class="pb-1 text-sm font-semibold text-slate-400">dari {{ number_format($item->limit, 0, ',', '.') }}</p>
                </div>
                <p class="mt-2 text-sm font-medium {{ $textColor }}">{{ $status }}</p>
            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-white">
                <div class="{{ $color }} h-3 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
            </div>

            <form action="{{ route('budgets.store') }}" method="POST" class="mt-6 border-t border-white/70 pt-5">
                @csrf
                <input type="hidden" name="category_id" value="{{ $item->category->id }}">
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Perbarui limit</label>
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" value="{{ $item->limit }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pl-10 text-sm font-bold text-slate-900 focus:border-sky-300 focus:ring-0" placeholder="Set limit">
                    </div>
                    <button type="submit" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-900">
                        Simpan
                    </button>
                </div>
            </form>
        </section>
        @empty
        <section class="app-panel px-6 py-14 text-center md:col-span-2 xl:col-span-3">
            <p class="text-lg font-bold text-slate-800">Belum ada kategori pengeluaran</p>
            <p class="mt-2 text-sm text-slate-500">Tambahkan kategori pengeluaran lebih dulu agar modul budget bisa dipakai optimal.</p>
        </section>
        @endforelse
    </div>
</div>
@endsection
