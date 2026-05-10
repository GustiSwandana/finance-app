@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-7 py-7 sm:px-8 sm:py-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Admin Console</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Kelola akses pengguna dari satu tempat.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/78 sm:text-base">
                    Lihat role, status aktivasi, dan tindakan penting pengguna tanpa harus pindah halaman. Approval user baru juga tetap bisa diakses cepat dari sini.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="hidden rounded-[24px] bg-white/10 px-5 py-4 backdrop-blur-sm xl:block">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-white/70">User Aktif</p>
                    <p class="mt-2 text-2xl font-black text-white">{{ $users->where('is_active', true)->count() }}</p>
                </div>
                <a href="{{ route('admin.approvals.index') }}" class="rounded-2xl border border-white/20 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5 hover:border-white hover:bg-slate-50">
                    Lihat Approval
                </a>
                <a href="{{ route('users.create') }}" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.25)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Tambah User
                </a>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
        {{ $errors->first() }}
    </div>
    @endif

    <section class="app-panel overflow-hidden rounded-[30px]">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-6 dark:border-slate-800 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="inline-flex rounded-full bg-slate-100 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-slate-600 dark:bg-slate-900 dark:text-slate-200">User Directory</p>
                <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Semua pengguna</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Status pending menunjukkan user belum diaktifkan atau belum disetujui.</p>
            </div>
            <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                {{ $users->total() }} pengguna
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-[0.24em] text-slate-400 dark:border-slate-800 dark:text-slate-500">
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Bergabung</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($users as $user)
                    <tr class="group transition hover:bg-slate-50/80 dark:hover:bg-slate-900/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-lg font-black text-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                        @if(Auth::id() == $user->id)
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-700">Anda</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">ID #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->isAdmin() ? 'border border-sky-200 bg-sky-50 text-sky-700' : 'border border-slate-200 bg-slate-100 text-slate-600' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->is_active ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-amber-200 bg-amber-50 text-amber-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-amber-950/30 dark:hover:text-amber-200" title="Edit user">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-rose-950/30 dark:hover:text-rose-200" title="Hapus user">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
            {{ $users->links('pagination::simple-tailwind') }}
        </div>
    </section>
</div>
@endsection
