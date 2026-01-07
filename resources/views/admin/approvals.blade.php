@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-10">
        <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Persetujuan Pendaftaran</h2>
        <p class="text-sm text-bgray-500 mt-1">Daftar user baru yang menunggu konfirmasi akses.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-xl border border-green-100 font-semibold text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">

        @if($pendingUsers->isEmpty())
        <div class="text-center py-10 text-bgray-400">
            Tidak ada pendaftaran baru yang menunggu persetujuan.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-bgray-100 dark:border-darkblack-400">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400">Nama</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-bgray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-500">
                    @foreach($pendingUsers as $user)
                    <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500">
                        <td class="px-6 py-4 font-bold text-bgray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-bgray-600 dark:text-bgray-300">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-bgray-500">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Tombol Terima --}}
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-success-300 hover:bg-success-400 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg shadow-success-300/30 transition-all">
                                        Terima
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form action="{{ route('admin.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak dan hapus pendaftaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg shadow-red-500/30 transition-all">
                                        Tolak
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
