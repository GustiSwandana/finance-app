@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto mt-10">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Transaksi</h2>
        <p class="text-sm text-bgray-500">
            Mengedit 
            <span class="font-bold {{ $transaction->type == 'income' ? 'text-success-300' : 'text-error-300' }}">
                {{ strtoupper($transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran') }}
            </span>
        </p>
    </div>

    <div class="rounded-lg bg-white p-8 dark:bg-darkblack-600 shadow-sm">
        
        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-6">
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Jumlah (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 py-3 px-4 text-lg font-bold dark:bg-darkblack-500 dark:text-white dark:border-darkblack-400" required>
            </div>

            <div class="grid gap-6 md:grid-cols-2 mb-6">
                <div>
                    <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Bank</label>
                    <select name="wallet_id" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 py-3 px-4 dark:bg-darkblack-500 dark:text-white dark:border-darkblack-400">
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ $transaction->wallet_id == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->bank_name }} (Sisa: Rp {{ number_format($wallet->balance) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Kategori</label>
                    <select name="category_id" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 py-3 px-4 dark:bg-darkblack-500 dark:text-white dark:border-darkblack-400">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Tanggal</label>
                <input type="date" name="date" value="{{ $transaction->date->format('Y-m-d') }}" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 py-3 px-4 dark:bg-darkblack-500 dark:text-white dark:border-darkblack-400">
            </div>
            <div class="mb-6">
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Keterangan</label>
                <input type="text" name="description" value="{{ $transaction->description }}" class="w-full rounded-lg border border-bgray-300 bg-bgray-50 py-3 px-4 dark:bg-darkblack-500 dark:text-white dark:border-darkblack-400">
            </div>

            <div class="flex justify-end pt-4 gap-3">
                <a href="{{ route('transactions.index') }}" class="px-6 py-3 text-sm font-bold text-gray-600 hover:text-gray-800 dark:text-gray-300">Batal</a>
                <button type="submit" class="rounded-lg bg-success-300 px-10 py-3 text-base font-bold text-white hover:bg-success-400">
                    Update Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection