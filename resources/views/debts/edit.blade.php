@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto mt-10">

    <form id="delete-form" action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>

    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $debt->type == 'receivable' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} shadow-sm">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                @if($debt->type == 'receivable')
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                @else
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                @endif
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Edit Data {{ $debt->type == 'receivable' ? 'Piutang' : 'Utang' }}</h2>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perbarui informasi pinjaman ini.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-gray-800 shadow-lg border border-gray-100 dark:border-gray-700">
        <form action="{{ route('debts.update', $debt->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Orang</label>
                    <input type="text" name="name" id="debt-name" value="{{ old('name', $debt->name) }}" class="w-full h-12 rounded-xl border-2 border-gray-200 bg-white px-4 text-sm font-bold text-gray-900 focus:border-gray-900 focus:ring-0 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all" required>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount', $debt->amount) }}" class="w-full h-12 rounded-xl border-2 border-gray-200 bg-white px-4 pl-11 text-sm font-bold text-gray-900 focus:border-gray-900 focus:ring-0 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all" required>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-gray-500 uppercase tracking-wider">Sumber Dana / Bank</label>
                    <div class="relative">
                        <select name="wallet_id" class="w-full h-12 appearance-none rounded-xl border-2 border-gray-200 bg-white px-4 text-sm font-bold text-gray-900 focus:border-gray-900 focus:ring-0 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer transition-all">
                            @foreach($wallets as $w)
                            <option value="{{ $w->id }}" {{ $debt->wallet_id == $w->id ? 'selected' : '' }}>
                                {{ $w->bank_name }} (Saldo: {{ number_format($w->balance) }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" /></svg>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</label>
                    <input type="date" name="due_date" value="{{ $debt->due_date ? $debt->due_date->format('Y-m-d') : '' }}" class="w-full h-12 rounded-xl border-2 border-gray-200 bg-white px-4 text-sm font-medium text-gray-900 focus:border-gray-900 focus:ring-0 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:[color-scheme:dark] transition-all">
                </div>

                <div class="group md:col-span-2">
                    <label class="mb-2 block text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</label>
                    <input type="text" name="description" id="debt-desc" value="{{ old('description', $debt->description) }}" class="w-full h-12 rounded-xl border-2 border-gray-200 bg-white px-4 text-sm font-medium text-gray-900 focus:border-gray-900 focus:ring-0 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all" placeholder="Opsional...">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6 dark:border-gray-700">

                <button type="button" onclick="if(confirm('Yakin ingin menghapus? Saldo cicilan yang sudah berjalan akan dikembalikan ke Dompet Utama.')) document.getElementById('delete-form').submit()" class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors dark:hover:bg-red-900/20">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Hapus Data</span>
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('debts.index') }}" class="rounded-xl px-6 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-black active:scale-95 transition-all dark:bg-white dark:text-gray-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" /></svg>
                        Simpan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fix Bug Keyboard K & B
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'debt-name' || target.id === 'debt-desc') {
            if (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b') {
                e.stopPropagation();
            }
        }
    }, true);

</script>
@endpush
