@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto mt-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah User Baru</h2>
        <p class="text-sm text-bgray-500">Buat akun untuk anggota keluarga atau tim.</p>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-bgray-900 dark:text-white mb-2">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full rounded-xl border-bgray-300 bg-bgray-50 px-4 py-3 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-bgray-900 dark:text-white mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full rounded-xl border-bgray-300 bg-bgray-50 px-4 py-3 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-bgray-900 dark:text-white mb-2">Password</label>
                        <input type="password" name="password" class="w-full rounded-xl border-bgray-300 bg-bgray-50 px-4 py-3 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-bgray-900 dark:text-white mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border-bgray-300 bg-bgray-50 px-4 py-3 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('users.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700">Batal</a>
                <button type="submit" class="rounded-xl bg-success-300 px-8 py-3 text-sm font-bold text-white hover:bg-success-400 shadow-lg shadow-success-300/30 transition-all">Simpan User</button>
            </div>
        </form>
    </div>
</div>
@endsection