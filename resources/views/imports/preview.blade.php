@extends('layouts.app')

@section('content')
<div class="w-full max-w-5xl mx-auto">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Preview Import</h2>
            <p class="text-sm text-bgray-500">
                Menyimpan ke: <b>{{ $wallet->bank_name }}</b>
                @if(!empty($transactions))
                <span class="ml-2 bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">
                    Format Terdeteksi: {{ $transactions[0]['bank_detected'] }}
                </span>
                @endif
            </p>
        </div>
        <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg font-bold text-sm">
            {{ count($transactions) }} Transaksi
        </div>
    </div>

    <form action="{{ route('import.store') }}" method="POST">
        @csrf
        <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-bgray-100 dark:bg-darkblack-600 dark:border-darkblack-400 mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-bgray-100 text-xs font-bold text-bgray-400 uppercase">
                            <th class="px-4 py-3 text-center">Pilih</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Deskripsi (Dari Bank)</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3">Tebakan Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-500">
                        @forelse($transactions as $idx => $trx)
                        <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="transactions[]" value="{{ json_encode($trx) }}" checked class="w-5 h-5 rounded border-bgray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-3 text-sm font-bold dark:text-white">
                                {{ \Carbon\Carbon::parse($trx['date'])->format('d M') }}
                            </td>
                            <td class="px-4 py-3 text-xs text-bgray-500 max-w-xs truncate">
                                {{ $trx['description'] }}
                            </td>
                            <td class="px-4 py-3">
                                @if($trx['type'] == 'income')
                                <span class="text-xs font-bold text-success-500 bg-success-50 px-2 py-1 rounded">Masuk (CR)</span>
                                @else
                                <span class="text-xs font-bold text-error-500 bg-error-50 px-2 py-1 rounded">Keluar (DB)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-bold dark:text-white">
                                Rp {{ number_format($trx['amount'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs border border-dashed border-bgray-300 px-2 py-1 rounded dark:text-bgray-300">
                                    {{ $trx['category_guess'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-400">
                                Tidak ada data transaksi yang terbaca. Pastikan format PDF sesuai (BCA).
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('import.create') }}" class="rounded-xl px-6 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">Batal & Upload Ulang</a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-lg hover:bg-indigo-700 active:scale-95 transition-all">
                Simpan Transaksi Terpilih
            </button>
        </div>
    </form>
</div>
@endsection
