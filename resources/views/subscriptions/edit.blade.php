@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Subscription Editor</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Perbarui detail tagihan rutin dengan alur yang lebih bersih.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Pastikan nama layanan, nominal, dan jadwal tagihan sudah tepat agar modul pembayaran rutin tetap akurat.</p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama layanan</label>
                    <input type="text" name="name" id="subscription-name-edit" value="{{ old('name', $subscription->name) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-fuchsia-300 focus:bg-white focus:ring-0" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Biaya bulanan</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount', $subscription->amount) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 pl-10 text-sm font-semibold text-slate-800 focus:border-fuchsia-300 focus:bg-white focus:ring-0" required>
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Jatuh tempo</label>
                <select name="due_date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-800 focus:border-fuchsia-300 focus:bg-white focus:ring-0">
                    @for($i = 1; $i <= 28; $i++)
                    <option value="{{ $i }}" {{ $subscription->due_date == $i ? 'selected' : '' }}>Setiap tanggal {{ $i }}</option>
                    @endfor
                    <option value="30" {{ $subscription->due_date == 30 ? 'selected' : '' }}>Akhir bulan</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                <a href="{{ route('subscriptions.index') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">Batal</a>
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
        if (target.id === 'subscription-name-edit' && (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b')) {
            e.stopPropagation();
        }
    }, true);
</script>
@endpush
