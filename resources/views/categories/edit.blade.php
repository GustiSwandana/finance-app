@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Category Editor</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Perbarui label kategori agar struktur laporan tetap rapi.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Gunakan nama yang spesifik dan pastikan tipe transaksi sudah tepat sebelum menyimpan perubahan.</p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Nama label</label>
                <input type="text" name="name" id="category-name-edit" value="{{ old('name', $category->name) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-fuchsia-300 focus:bg-white focus:ring-0" placeholder="Contoh: Belanja Harian, Gaji, Transport" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Tipe transaksi</label>
                <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-fuchsia-300 focus:bg-white focus:ring-0">
                    <option value="expense" {{ $category->type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    <option value="income" {{ $category->type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                <a href="{{ route('categories.index') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">Batal</a>
                <button type="submit" class="rounded-2xl bg-fuchsia-600 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(192,38,211,0.18)] transition hover:-translate-y-0.5 hover:bg-fuchsia-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'category-name-edit' && (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b')) {
            e.stopPropagation();
        }
    }, true);
</script>
@endpush
