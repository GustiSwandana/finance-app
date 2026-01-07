@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto mt-10">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-bgray-900 dark:text-white">Import Mutasi Bank</h2>
        <p class="text-sm text-bgray-500 mt-2">Upload file PDF mutasi rekening (Khusus format BCA).</p>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('import.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold text-bgray-700 dark:text-white mb-2">Pilih Akun Bank</label>
                <select name="wallet_id" class="w-full h-12 rounded-xl border-2 border-bgray-100 px-4 font-bold text-bgray-900 focus:border-indigo-500 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                    @foreach($wallets as $w)
                    <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-bgray-700 dark:text-white mb-2">File PDF Mutasi</label>
                <input type="file" name="bank_files[]" accept=".pdf" multiple class="w-full p-3 ..." required>
                <p class="text-xs text-gray-500 mt-2">
                    *Tips: Anda bisa memilih banyak file sekaligus (tahan tombol Ctrl saat memilih file).
                </p>
            </div>

            <button type="submit" class="w-full rounded-xl bg-indigo-600 py-4 text-sm font-bold text-white shadow-lg hover:bg-indigo-700 transition-all">
                Scan PDF & Preview Data
            </button>
        </form>
    </div>
</div>
@endsection
