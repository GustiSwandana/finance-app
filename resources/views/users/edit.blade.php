@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-5xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="max-w-3xl">
            <p class="app-eyebrow">User Editor</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Perbarui profil, role, dan status akun.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                Halaman ini menggabungkan pembaruan profil, aktivasi akun, dan reset password dalam satu alur yang lebih rapi.
            </p>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
                <div class="rounded-[32px] border border-slate-200 bg-slate-50 p-6">
                    <label class="block text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Foto profil</label>
                    <div class="mt-5 flex flex-col items-center text-center">
                        <button type="button" onclick="document.getElementById('avatarInput').click()" class="group relative">
                            <div class="h-28 w-28 overflow-hidden rounded-full border-4 border-white shadow-lg">
                                <img id="avatar-preview" class="h-full w-full object-cover" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/avatar/profile-52x52.png') }}" alt="Avatar Preview" />
                            </div>
                            <span class="absolute bottom-1 right-1 flex h-10 w-10 items-center justify-center rounded-full bg-slate-950 text-white shadow-md transition group-hover:bg-emerald-500">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </span>
                        </button>
                        <input type="file" id="avatarInput" name="avatar" onchange="previewImage(event)" class="hidden" accept="image/*">
                        <p class="mt-4 text-sm font-bold text-slate-800">{{ $user->name }}</p>
                        <p class="mt-1 text-xs text-slate-500">JPG atau PNG, maksimal 2MB.</p>
                        <button type="button" onclick="document.getElementById('avatarInput').click()" class="mt-5 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-emerald-200 hover:text-emerald-700">
                            Ganti Foto
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" required>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Role akses</label>
                            <select name="role" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0">
                                <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            </select>
                        </div>
                        <div class="rounded-[28px] border border-slate-200 bg-slate-50 px-5 py-4">
                            <label for="is_active" class="flex cursor-pointer items-center gap-3">
                                <input id="is_active" type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500" @checked(old('is_active', $user->is_active))>
                                <span>
                                    <span class="block text-sm font-bold text-slate-900">Akun aktif</span>
                                    <span class="block text-xs text-slate-500">User bisa login saat status ini aktif.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="rounded-[32px] border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-black tracking-tight text-slate-950">Ganti password</h2>
                                <p class="mt-1 text-sm text-slate-500">Kosongkan kedua field di bawah jika password tidak perlu diubah.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-slate-700">Password baru</label>
                                <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:ring-0" placeholder="Password baru">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:ring-0" placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                <a href="{{ route('users.index') }}" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                    Batal
                </a>
                <button type="submit" class="rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.25)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
