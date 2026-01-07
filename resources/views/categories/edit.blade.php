@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto mt-10">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 shadow-sm dark:bg-purple-900 dark:text-purple-300">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-bgray-900 dark:text-white tracking-tight">Edit Kategori</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Perbarui nama atau jenis kategori transaksi.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf 
            @method('PUT')

            <div class="space-y-6">
                
                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Nama Label</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-400 group-focus-within:text-purple-500 transition-colors pointer-events-none">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <input type="text" name="name" id="category-name-edit" 
                            value="{{ old('name', $category->name) }}" 
                            class="w-full h-12 rounded-xl border-2 border-bgray-100 bg-bgray-50 px-4 pl-11 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" 
                            placeholder="Contoh: Belanja, Gaji" required>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Tipe Transaksi</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-400 group-focus-within:text-purple-500 transition-colors pointer-events-none">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 10v12"/><path d="M17 14v-10"/><path d="M21 8l-4-4-4 4"/><path d="M3 16l4 4 4-4"/></svg>
                        </div>
                        
                        <select name="type" class="w-full h-12 appearance-none rounded-xl border-2 border-bgray-100 bg-bgray-50 px-4 pl-12 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:bg-white focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all">
                            <option value="expense" {{ $category->type == 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                            <option value="income" {{ $category->type == 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                        </select>
                        
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400 pointer-events-none">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 flex items-center justify-end gap-4 border-t border-bgray-100 pt-6 dark:border-darkblack-500">
                <a href="{{ route('categories.index') }}" class="rounded-lg px-6 py-3 text-sm font-bold text-bgray-500 hover:bg-bgray-100 hover:text-bgray-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-xl bg-purple-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:bg-purple-700 hover:shadow-purple-500/50 active:scale-95">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                    Update Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // FIX BUG KEYBOARD (K & B)
    // Mencegah shortcut template mengambil alih input saat mengetik nama kategori
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'category-name-edit') {
            if (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b') {
                e.stopPropagation();
            }
        }
    }, true);
</script>
@endpush