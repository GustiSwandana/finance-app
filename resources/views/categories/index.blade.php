@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Category Library</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Rapikan struktur kategori untuk laporan yang lebih akurat.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Kategori dipakai di transaksi manual, import mutasi, dan rekap laporan. Pastikan label yang dipakai jelas dan tidak tumpang tindih.
                </p>
            </div>
            <div class="metric-card min-w-[220px]">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Total Label</p>
                <p class="mt-3 text-3xl font-black text-slate-950">{{ count($categories) }}</p>
                <p class="mt-2 text-xs text-slate-500">Gabungan pemasukan dan pengeluaran</p>
            </div>
        </div>
    </section>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,360px)_minmax(0,1fr)] xl:items-start">
        <section class="app-panel p-6 xl:sticky xl:top-24">
            <div class="border-b border-slate-200 pb-5">
                <p class="app-eyebrow">New Category</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Tambah kategori baru</h2>
                <p class="mt-2 text-sm text-slate-500">Tentukan tipe kategori dengan benar agar arus kas tidak salah hitung.</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama label</label>
                    <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-teal-300 focus:bg-white focus:ring-0" placeholder="Contoh: Belanja Harian, Gaji, Transport" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tipe transaksi</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-teal-300 focus:bg-white focus:ring-0">
                        <option value="expense">Pengeluaran</option>
                        <option value="income">Pemasukan</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-teal-600 px-4 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(15,118,110,0.22)] transition hover:-translate-y-0.5 hover:bg-teal-700">
                    Simpan Kategori
                </button>
            </form>
        </section>

        <section class="app-panel p-6">
            <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="app-eyebrow">Category List</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Daftar kategori</h2>
                    <p class="mt-2 text-sm text-slate-500">Hapus kategori yang benar-benar sudah tidak dipakai agar relasi data tetap aman.</p>
                </div>
                <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500">
                    {{ count($categories) }} label
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($categories as $cat)
                @php
                    $isIncome = $cat->type === 'income';
                @endphp
                <article class="rounded-[28px] border {{ $isIncome ? 'border-emerald-100 bg-emerald-50/40' : 'border-rose-100 bg-rose-50/40' }} p-5 transition hover:-translate-y-0.5 hover:bg-white hover:shadow-lg">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $isIncome ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-500' }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    @if($isIncome)
                                    <path d="M12 19V5M5 12l7-7 7 7" />
                                    @else
                                    <path d="M12 5v14M12 19l4-4M12 19l-4-4" />
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-950">{{ $cat->name }}</h3>
                                <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.2em] {{ $isIncome ? 'text-emerald-700' : 'text-rose-500' }}">
                                    {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-2xl border border-white/80 bg-white p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500" title="Hapus kategori">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </article>
                @empty
                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center md:col-span-2 xl:col-span-3">
                    <p class="text-lg font-bold text-slate-800">Belum ada kategori</p>
                    <p class="mt-2 text-sm text-slate-500">Tambahkan kategori baru dari panel di sebelah kiri untuk mulai merapikan laporan.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
