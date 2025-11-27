@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Dashboard Keuangan</h2>
            <p class="text-sm text-bgray-500 dark:text-bgray-400">Halo {{ Auth::user()->name }}, inilah ringkasan keuanganmu hari ini.</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mb-1">Total Aset Bersih</p>
            <h1 class="text-4xl font-black text-bgray-900 dark:text-white tracking-tight">
                Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="relative overflow-hidden rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400 group hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 12h.01"/></svg>
                </div>
                <span class="text-xs font-bold uppercase text-bgray-400 tracking-wider">Utama</span>
            </div>
            <div>
                <p class="text-sm font-medium text-bgray-500">Bank BCA</p>
                <h3 class="text-2xl font-bold text-bgray-900 dark:text-white mt-1">Rp {{ number_format($bca->balance ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute bottom-0 right-0 w-24 h-16 opacity-50 group-hover:opacity-100 transition-opacity">
                <canvas id="chartBCA"></canvas>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400 group hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-yellow-50 text-yellow-500 dark:bg-yellow-900/30 dark:text-yellow-400">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="text-xs font-bold uppercase text-bgray-400 tracking-wider">Tabungan</span>
            </div>
            <div>
                <p class="text-sm font-medium text-bgray-500">Mandiri</p>
                <h3 class="text-2xl font-bold text-bgray-900 dark:text-white mt-1">Rp {{ number_format($mandiri->balance ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute bottom-0 right-0 w-24 h-16 opacity-50 group-hover:opacity-100 transition-opacity">
                <canvas id="chartMandiri"></canvas>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400 group hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-orange-50 text-orange-500 dark:bg-orange-900/30 dark:text-orange-400">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 12l-4-4-4 4"/><path d="M12 16V8"/></svg>
                </div>
                <span class="text-xs font-bold uppercase text-bgray-400 tracking-wider">Cadangan</span>
            </div>
            <div>
                <p class="text-sm font-medium text-bgray-500">Bank BRI</p>
                <h3 class="text-2xl font-bold text-bgray-900 dark:text-white mt-1">Rp {{ number_format($bri->balance ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute bottom-0 right-0 w-24 h-16 opacity-50 group-hover:opacity-100 transition-opacity">
                <canvas id="chartBRI"></canvas>
            </div>
        </div>

    </div>

    <div class="flex flex-col xl:flex-row gap-8">
        
        <div class="w-full xl:w-2/3 flex flex-col gap-8">
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('transactions.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white border border-bgray-200 hover:border-success-300 hover:bg-success-50 transition-all group cursor-pointer dark:bg-darkblack-600 dark:border-darkblack-400">
                    <div class="h-10 w-10 rounded-full bg-success-100 text-success-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    </div>
                    <span class="text-xs font-bold text-bgray-700 dark:text-white">Pemasukan</span>
                </a>
                
                <a href="{{ route('transactions.index', ['tab' => 'expense']) }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white border border-bgray-200 hover:border-error-300 hover:bg-error-50 transition-all group cursor-pointer dark:bg-darkblack-600 dark:border-darkblack-400">
                    <div class="h-10 w-10 rounded-full bg-error-100 text-error-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg>
                    </div>
                    <span class="text-xs font-bold text-bgray-700 dark:text-white">Pengeluaran</span>
                </a>

                <a href="{{ route('transfer.create') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white border border-bgray-200 hover:border-warning-300 hover:bg-warning-50 transition-all group cursor-pointer dark:bg-darkblack-600 dark:border-darkblack-400">
                    <div class="h-10 w-10 rounded-full bg-warning-100 text-warning-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8"/></svg>
                    </div>
                    <span class="text-xs font-bold text-bgray-700 dark:text-white">Transfer</span>
                </a>

                <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-white border border-bgray-200 hover:border-purple-500 hover:bg-purple-50 transition-all group cursor-pointer dark:bg-darkblack-600 dark:border-darkblack-400">
                    <div class="h-10 w-10 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-bgray-700 dark:text-white">Laporan</span>
                </a>
            </div>

        </div>

        <div class="w-full xl:w-1/3 flex flex-col gap-8">


            <div class="rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Transaksi Terakhir</h3>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-blue-500 hover:text-blue-600">Lihat Semua</a>
                </div>
                
                <div class="flex flex-col gap-4">
                    @php 
                        $latestTransactions = \App\Models\Transaction::where('user_id', Auth::id())->with('category', 'wallet')->latest('date')->take(5)->get(); 
                    @endphp

                    @forelse($latestTransactions as $trx)
                        <div class="flex items-center justify-between border-b border-bgray-100 pb-4 dark:border-darkblack-500 last:border-0 last:pb-0">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $trx->type == 'income' ? 'bg-success-50 text-success-300' : ($trx->type == 'expense' ? 'bg-error-50 text-error-300' : 'bg-warning-50 text-warning-300') }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        @if($trx->type == 'income') <path d="M12 19V5M5 12l7-7 7 7"/>
                                        @elseif($trx->type == 'expense') <path d="M12 5v14M12 19l4-4M12 19l-4-4"/>
                                        @else <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8"/> @endif
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-bgray-900 dark:text-white">{{ $trx->category->name ?? 'Transfer' }}</h4>
                                    <p class="text-xs font-medium text-bgray-500">{{ $trx->description }} • {{ $trx->date->format('d M') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $trx->type == 'income' ? 'text-success-300' : ($trx->type == 'expense' ? 'text-error-300' : 'text-warning-300') }}">
                                    {{ $trx->type == 'income' ? '+' : ($trx->type == 'expense' ? '-' : '') }}
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs font-medium text-bgray-400">{{ $trx->wallet->bank_name }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-gray-400 py-4">Belum ada transaksi.</p>
                    @endforelse
                </div>
                
            </div>
            
            <div class="relative overflow-hidden rounded-2xl bg-purple-600 p-6 text-white shadow-lg">
                <div class=""></div>
                <h3 class="relative z-10 text-lg font-bold">Tips Hemat!</h3>
                <p class="relative z-10 mt-2 text-sm text-purple-100 leading-relaxed">
                    Gunakan fitur "Tagihan Rutin" agar tidak telat bayar listrik & internet. Denda telat itu mahal lho!
                </p>
                <a href="{{ route('subscriptions.index') }}" class="relative z-10 mt-4 inline-block rounded-lg bg-white px-4 py-2 text-xs font-bold text-purple-600 hover:bg-purple-50 transition">Cek Tagihan</a>
            </div>

        </div>
        

    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Slider
    if($(".card-slider").length){
        $(".card-slider").slick({
            dots: true,
            infinite: true,
            autoplay: true,
            speed: 500,
            fade: true,
            cssEase: "linear",
            arrows: false,
        });
    }

    // 2. Grafik Mini (Sparkline)
    function createMiniChart(canvasId, color, data) {
        const canvas = document.getElementById(canvasId);
        if(canvas){
            new Chart(canvas.getContext("2d"), {
                type: "line",
                data: {
                    labels: ["1", "2", "3", "4", "5", "6"],
                    datasets: [{
                        data: data,
                        borderColor: color,
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: {
                            target: 'origin',
                            above: color + '20' // Opacity 20%
                        },
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    layout: { padding: 0 },
                    elements: { line: { tension: 0.4 } }
                }
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Data Dummy untuk grafik (Nanti bisa diganti data real dari controller)
        createMiniChart("chartBCA", "#2563EB", [10, 25, 15, 30, 20, 40]);
        createMiniChart("chartMandiri", "#EAB308", [50, 55, 52, 58, 60, 65]);
        createMiniChart("chartBRI", "#F97316", [15, 10, 12, 8, 14, 10]);
    });
</script>
@endpush