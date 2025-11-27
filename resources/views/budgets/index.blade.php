@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    
    <div class="mb-10 flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100 text-teal-600 dark:bg-teal-900 dark:text-teal-300 shadow-sm">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Anggaran Bulanan</h2>
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Rencanakan pengeluaran Anda bulan ini ({{ date('F Y') }}).</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        @foreach($budgetReport as $item)
            @php
                $percent = $item->percentage;
                if($percent > 100) {
                    $color = 'bg-error-300'; // Merah
                    $textColor = 'text-error-300';
                    $status = 'Over Budget!';
                } elseif($percent >= 75) {
                    $color = 'bg-warning-300'; // Kuning
                    $textColor = 'text-warning-300';
                    $status = 'Waspada';
                } else {
                    $color = 'bg-success-300'; // Hijau
                    $textColor = 'text-success-300';
                    $status = 'Aman';
                }
                
                // Cap di 100% untuk lebar bar agar tidak overflow css
                $width = $percent > 100 ? 100 : $percent;
            @endphp

            <div class="group relative rounded-2xl bg-white p-6 shadow-sm border border-bgray-100 dark:bg-darkblack-600 dark:border-darkblack-400 transition-all hover:shadow-lg">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-bgray-900 dark:text-white">{{ $item->category->name }}</h3>
                        <p class="text-xs font-medium text-bgray-500 uppercase tracking-wider mt-1">Terpakai Bulan Ini</p>
                    </div>
                    <div class="opacity-100 xl:opacity-0 group-hover:opacity-100 transition-opacity absolute top-4 right-4 xl:relative xl:top-0 xl:right-0">
                         </div>
                </div>

                <div class="flex items-end gap-1 mb-2">
                    <span class="text-2xl font-extrabold text-bgray-900 dark:text-white">Rp {{ number_format($item->spent, 0, ',', '.') }}</span>
                    <span class="text-sm font-medium text-bgray-400 mb-1"> / {{ number_format($item->limit, 0, ',', '.') }}</span>
                </div>

                <div class="w-full bg-bgray-100 rounded-full h-3 dark:bg-darkblack-500 mb-2 overflow-hidden">
                    <div class="{{ $color }} h-3 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                </div>

                <div class="flex justify-between items-center text-xs font-bold">
                    <span class="{{ $textColor }}">{{ number_format($percent, 1) }}% ({{ $status }})</span>
                    
                    <form action="{{ route('budgets.store') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $item->category->id }}">
                        <div class="relative">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-bgray-400">Rp</span>
                            <input type="number" name="amount" value="{{ $item->limit }}" class="w-24 rounded-lg border border-bgray-200 bg-bgray-50 py-1 pl-6 text-xs font-bold text-bgray-900 focus:border-teal-500 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white" placeholder="Set Limit">
                        </div>
                        <button type="submit" class="p-1.5 rounded-lg bg-gray-900 text-white hover:bg-black transition">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17L4 12"/></svg>
                        </button>
                    </form>
                </div>

            </div>
        @endforeach

    </div>

    @if($budgetReport->isEmpty())
        <div class="flex flex-col items-center justify-center py-20">
            <p class="text-bgray-500">Belum ada kategori pengeluaran. Tambahkan kategori dulu di menu Pengaturan.</p>
        </div>
    @endif
</div>
@endsection