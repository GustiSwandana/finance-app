@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <!-- Header Page -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-5">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="dark:stroke-black">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white tracking-tight">Manajemen Pengguna</h2>
                <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400 mt-1">Kelola akses dan profil pengguna aplikasi.</p>
            </div>
        </div>

        <a href="{{ route('users.create') }}" class="group flex items-center gap-2 rounded-xl bg-success-300 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-success-300/30 transition-all hover:bg-success-400 hover:shadow-success-400/40 hover:-translate-y-0.5 active:scale-95">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-90 transition-transform duration-300">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah User</span>
        </a>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
    <div class="mb-8 flex items-center gap-3 rounded-xl bg-red-50 p-4 text-red-600 border border-red-100">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" /></svg>
        <span class="text-sm font-semibold">{{ $errors->first() }}</span>
    </div>
    @endif

    <!-- Tabel User -->
    <div class="rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-bgray-100 dark:border-darkblack-400">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400 tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400 tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-bgray-400 tracking-wider">Bergabung</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-bgray-400 tracking-wider">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-500">
                    @foreach($users as $user)
                    <tr class="group hover:bg-bgray-50 dark:hover:bg-darkblack-500 transition-colors">
                        <!-- Kolom Nama & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-bgray-100 text-bgray-600 font-bold text-lg dark:bg-darkblack-500 dark:text-white border-2 border-white dark:border-darkblack-600 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-bgray-900 dark:text-white">{{ $user->name }}</span>
                                        @if(Auth::id() == $user->id)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-success-50 text-success-300 border border-success-100 tracking-wide">ANDA</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-bgray-400">ID: #{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Email -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-bgray-600 dark:text-bgray-300">{{ $user->email }}</span>
                        </td>

                        <!-- Kolom Tanggal -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-bgray-500">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" /></svg>
                                <span class="text-sm">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Logika Proteksi Tombol --}}
                                @if(Auth::id() == $user->id)

                                <!-- Edit (Hanya Milik Sendiri) -->
                                <a href="{{ route('users.edit', $user->id) }}" class="p-2 rounded-lg text-bgray-500 hover:bg-warning-50 hover:text-warning-300 transition-all" title="Edit Profil">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                                </a>

                                @else

                                <!-- Locked State (Punya Orang Lain) -->
                                <div class="group/lock relative flex items-center justify-center p-2">
                                    <svg width="16" height="16" class="text-bgray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                                    <span class="absolute bottom-full mb-2 hidden group-hover/lock:block whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-[10px] text-white">
                                        Akses Dibatasi
                                    </span>
                                </div>

                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 border-t border-bgray-100 pt-4 dark:border-darkblack-500">
            {{ $users->links('pagination::simple-tailwind') }}
        </div>
    </div>
</div>
@endsection
