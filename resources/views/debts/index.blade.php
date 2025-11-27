@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-900 text-white shadow-lg dark:bg-white dark:text-gray-900">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Utang & Piutang</h2>
            <p class="text-sm text-bgray-500 dark:text-bgray-400">Kelola pinjaman Anda dan teman Anda.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-200 text-red-600 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-8 rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">
        <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Catat Pinjaman Baru</h3>
        <form action="{{ route('debts.store') }}" method="POST">
            @csrf
            <div class="grid gap-4 md:grid-cols-5 items-end">
                <div class="md:col-span-1">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block">Jenis</label>
                    <select name="type" class="w-full rounded-lg border-bgray-300 text-sm focus:border-success-300 dark:bg-darkblack-500 dark:text-white">
                        <option value="receivable">Orang Utang ke Saya (Piutang)</option>
                        <option value="payable">Saya Utang ke Orang</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block">Nama Orang</label>
                    <input type="text" name="name" class="w-full rounded-lg border-bgray-300 text-sm dark:bg-darkblack-500 dark:text-white" placeholder="Nama..." required>
                </div>
                <div class="md:col-span-1">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="w-full rounded-lg border-bgray-300 text-sm font-bold dark:bg-darkblack-500 dark:text-white" placeholder="0" required>
                </div>
                <div class="md:col-span-1">
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-2 block">Via Bank</label>
                    <select name="wallet_id" class="w-full rounded-lg border-bgray-300 text-sm dark:bg-darkblack-500 dark:text-white">
                        @foreach($wallets as $w)
                            <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full rounded-lg bg-bgray-900 py-2.5 text-sm font-bold text-white hover:bg-black dark:bg-white dark:text-bgray-900 transition-all">
                        Simpan Data
                    </button>
                </div>
            </div>
            
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-1 block">Jatuh Tempo (Opsional)</label>
                    <input type="date" name="due_date" class="w-full rounded-lg border-bgray-300 text-xs dark:bg-darkblack-500 dark:text-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-bgray-500 uppercase mb-1 block">Catatan (Opsional)</label>
                    <input type="text" name="description" class="w-full rounded-lg border-bgray-300 text-xs dark:bg-darkblack-500 dark:text-white" placeholder="Ex: Beli Pulsa">
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-success-300">Piutang (Aset)</h3>
                <span class="text-xs font-medium bg-success-50 text-success-300 px-3 py-1 rounded-full">Orang pinjam ke saya</span>
            </div>
            
            @if($receivables->isEmpty())
                <div class="rounded-xl border-2 border-dashed border-bgray-300 p-8 text-center text-bgray-400">Tidak ada yang berhutang ke Anda.</div>
            @else
                <div class="flex flex-col gap-4">
                    @foreach($receivables as $debt)
                        <div class="rounded-xl bg-white p-5 shadow-sm border-l-4 border-success-300 dark:bg-darkblack-600">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-bold text-bgray-900 dark:text-white">{{ $debt->name }}</h4>
                                    <p class="text-xs text-bgray-500">{{ $debt->description ?? 'Tanpa catatan' }}</p>
                                    @if($debt->due_date)
                                        <p class="text-xs text-error-300 mt-1 font-medium">Jatuh tempo: {{ $debt->due_date->format('d M Y') }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-success-300">Rp {{ number_format($debt->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-bgray-100 dark:border-darkblack-500">
                                <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="flex gap-2 items-center justify-end">
                                    @csrf
                                    <span class="text-xs text-bgray-500">Masuk ke:</span>
                                    <select name="wallet_id" class="text-xs rounded border-bgray-200 py-1 pr-6 dark:bg-darkblack-500 dark:text-white">
                                        @foreach($wallets as $w)
                                            <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 rounded bg-success-50 text-success-300 text-xs font-bold hover:bg-success-300 hover:text-white transition-colors" onclick="return confirm('Yakin {{ $debt->name }} sudah bayar lunas? Saldo akan bertambah.')">
                                        Sudah Lunas
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-error-300">Utang Saya (Kewajiban)</h3>
                <span class="text-xs font-medium bg-error-50 text-error-300 px-3 py-1 rounded-full">Saya pinjam uang orang</span>
            </div>

            @if($payables->isEmpty())
                <div class="rounded-xl border-2 border-dashed border-bgray-300 p-8 text-center text-bgray-400">Anda bebas utang!</div>
            @else
                <div class="flex flex-col gap-4">
                    @foreach($payables as $debt)
                        <div class="rounded-xl bg-white p-5 shadow-sm border-l-4 border-error-300 dark:bg-darkblack-600">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-bold text-bgray-900 dark:text-white">{{ $debt->name }}</h4>
                                    <p class="text-xs text-bgray-500">{{ $debt->description ?? 'Tanpa catatan' }}</p>
                                    @if($debt->due_date)
                                        <p class="text-xs text-error-300 mt-1 font-medium">Jatuh tempo: {{ $debt->due_date->format('d M Y') }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-error-300">Rp {{ number_format($debt->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-bgray-100 dark:border-darkblack-500">
                                <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="flex gap-2 items-center justify-end">
                                    @csrf
                                    <span class="text-xs text-bgray-500">Ambil dari:</span>
                                    <select name="wallet_id" class="text-xs rounded border-bgray-200 py-1 pr-6 dark:bg-darkblack-500 dark:text-white">
                                        @foreach($wallets as $w)
                                            <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 rounded bg-error-50 text-error-300 text-xs font-bold hover:bg-error-300 hover:text-white transition-colors" onclick="return confirm('Lunasi utang ini sekarang? Saldo akan berkurang.')">
                                        Bayar Lunas
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection