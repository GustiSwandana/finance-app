@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto space-y-8">

    <section class="app-hero px-6 py-7 md:px-8 md:py-9">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="app-eyebrow">Access Review</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight md:text-5xl">Setujui pengguna baru dengan cepat dan jelas.</h2>
                <p class="mt-3 text-sm text-white/80 md:text-base">
                    Semua pendaftaran yang menunggu approval ditampilkan dalam satu tempat agar proses aktivasi admin tetap rapi.
                </p>
            </div>
            <div class="rounded-[24px] bg-white/12 px-5 py-4 backdrop-blur-sm">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-white/70">Pending Queue</p>
                <p class="mt-2 text-3xl font-extrabold text-white">{{ $pendingUsers->count() }}</p>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="rounded-2xl border border-green-100 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-semibold text-amber-700 dark:border-amber-900/40 dark:bg-amber-950/20 dark:text-amber-200">
        {{ session('error') }}
    </div>
    @endif

    <div class="app-panel rounded-[28px] p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Approval Table</p>
                <h3 class="mt-2 text-xl font-extrabold text-slate-950 dark:text-white">Pendaftaran yang menunggu konfirmasi</h3>
            </div>
        </div>

        @if($pendingUsers->isEmpty())
        <div class="rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-slate-400 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-500">
            Tidak ada pendaftaran baru yang menunggu persetujuan.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Nama</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Verifikasi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($pendingUsers as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/60">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-teal-50 text-sm font-extrabold text-teal-700">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-950 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500">Menunggu aktivasi</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->hasVerifiedEmail())
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">Terverifikasi</span>
                            @else
                            <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-200">Belum verifikasi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" @disabled(! $user->hasVerifiedEmail()) class="rounded-xl bg-teal-700 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none dark:disabled:bg-slate-800 dark:disabled:text-slate-500">
                                        Terima
                                    </button>
                                </form>

                                <form action="{{ route('admin.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak dan hapus pendaftaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-red-500 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-red-500/20 transition hover:bg-red-600">
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
