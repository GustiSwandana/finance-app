@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-4xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">New User</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Buat akun baru dengan kontrol akses yang jelas.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                Admin bisa langsung menentukan role dan apakah akun baru langsung aktif atau menunggu aktivasi manual.
            </p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap</label>
                    <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Role</label>
                    <select name="role" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="rounded-[28px] border border-slate-200 bg-slate-50 px-5 py-4">
                    <label for="is_active" class="flex cursor-pointer items-center gap-3">
                        <input id="is_active" type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500" checked>
                        <span>
                            <span class="block text-sm font-bold text-slate-900">Aktifkan akun sekarang</span>
                            <span class="block text-xs text-slate-500">Nonaktifkan jika akun perlu menunggu approval atau setup lanjutan.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                <a href="{{ route('users.index') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">Batal</a>
                <button type="submit" class="rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.25)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Simpan User
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
