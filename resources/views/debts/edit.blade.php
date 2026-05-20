@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-5xl flex-col gap-8">
    <form id="delete-form" action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">{{ $debt->type == 'receivable' ? 'Receivable Editor' : 'Payable Editor' }}</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Perbarui data {{ $debt->type == 'receivable' ? 'piutang' : 'utang' }} tanpa kehilangan konteks.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Edit nama pihak terkait, nominal, wallet, jatuh tempo, dan catatan dari satu form yang lebih rapi.</p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('debts.update', $debt->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jenis</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0">
                        <option value="receivable" @selected(old('type', $debt->type) === 'receivable')>Piutang</option>
                        <option value="payable" @selected(old('type', $debt->type) === 'payable')>Utang</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama orang</label>
                    <input type="text" name="name" id="debt-name" value="{{ old('name', $debt->name) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jumlah</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount', $debt->amount) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 pl-10 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" required>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Wallet terkait</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0">
                        @foreach($wallets as $w)
                        <option value="{{ $w->id }}" {{ (int) old('wallet_id', $debt->wallet_id) === $w->id ? 'selected' : '' }}>{{ $w->bank_name }} | Saldo {{ number_format($w->balance) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jatuh tempo</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $debt->due_date ? $debt->due_date->format('Y-m-d') : '') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Catatan</label>
                <input type="text" name="description" id="debt-desc" value="{{ old('description', $debt->description) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" placeholder="Opsional">
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <button type="button" onclick="if(confirm('Yakin ingin menghapus? Saldo cicilan yang sudah berjalan akan dikembalikan ke dompet utama.')) document.getElementById('delete-form').submit()" class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                    Hapus Data
                </button>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('debts.index') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">Batal</a>
                    <button type="submit" class="rounded-2xl bg-slate-950 px-6 py-3 text-sm font-bold text-white transition hover:bg-slate-900">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if ((target.id === 'debt-name' || target.id === 'debt-desc') && (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b')) {
            e.stopPropagation();
        }
    }, true);
</script>
@endpush
