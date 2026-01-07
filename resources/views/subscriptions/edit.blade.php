@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto mt-10">

    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 shadow-sm dark:bg-purple-900 dark:text-purple-300">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-bgray-900 dark:text-white tracking-tight">Edit Tagihan</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Perbarui informasi layanan langganan Anda.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST">
            @csrf @method('PUT')

            <style>
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }

                input[type=number] {
                    -moz-appearance: textfield;
                }

            </style>

            <div class="space-y-6">

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Nama Layanan</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-400 group-focus-within:text-purple-500 transition-colors pointer-events-none">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" /></svg>
                        </div>
                        <input type="text" name="name" id="subscription-name-edit" value="{{ old('name', $subscription->name) }}" class="w-full h-12 rounded-xl border-2 border-bgray-100 bg-white px-4 pl-11 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" required>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Biaya Bulanan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-bgray-400 group-focus-within:text-purple-500 transition-colors">Rp</span>
                        <input type="number" name="amount" value="{{ old('amount', $subscription->amount) }}" class="w-full h-12 rounded-xl border-2 border-bgray-100 bg-white px-4 pl-11 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" required>
                    </div>
                </div>

                <div class="group">
                    <label class="mb-2 block text-xs font-bold text-bgray-500 uppercase tracking-wider group-focus-within:text-purple-500 transition-colors">Jatuh Tempo</label>
                    <div class="relative">
                        <select name="due_date" class="w-full h-12 appearance-none rounded-xl border-2 border-bgray-100 bg-white px-4 pl-11 text-sm font-bold text-bgray-900 focus:border-purple-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all">
                            @for($i = 1; $i <= 28; $i++) <option value="{{ $i }}" {{ $subscription->due_date == $i ? 'selected' : '' }}>Setiap Tanggal {{ $i }}</option>
                                @endfor
                                <option value="30" {{ $subscription->due_date == 30 ? 'selected' : '' }}>Akhir Bulan</option>
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-400 group-focus-within:text-purple-500 transition-colors pointer-events-none">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" /></svg>
                        </div>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-bgray-400">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" /></svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 flex items-center justify-end gap-4 border-t border-bgray-100 pt-6 dark:border-darkblack-500">
                <a href="{{ route('subscriptions.index') }}" class="rounded-lg px-6 py-3 text-sm font-bold text-bgray-500 hover:bg-bgray-100 hover:text-bgray-900 transition-colors">Batal</a>
                <button type="submit" class="flex items-center gap-2 rounded-xl bg-purple-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:bg-purple-700 hover:shadow-purple-500/50 active:scale-95">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" /></svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fix Bug Keyboard K/B di halaman Edit
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (target.id === 'subscription-name-edit') {
            if (e.key.toLowerCase() === 'k' || e.key.toLowerCase() === 'b') {
                e.stopPropagation();
            }
        }
    }, true);

</script>
@endpush
