@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto mt-10">
    
    <div class="mb-8">
        <h2 class="text-3xl font-black text-bgray-900 dark:text-white tracking-tight">Edit Profil</h2>
        <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400">Perbarui informasi akun dan keamanan Anda.</p>
    </div>

    <div class="rounded-2xl bg-white p-8 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')

            <div class="space-y-8">

                <div>
                    <label class="block text-xs font-bold text-bgray-500 uppercase tracking-wider mb-4">Foto Profil</label>
                    <div class="flex items-center gap-6">
                        
                        <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                            <div class="h-24 w-24 rounded-full overflow-hidden border-4 border-bgray-100 dark:border-darkblack-500 shadow-sm transition-transform group-hover:scale-105">
                                <img id="avatar-preview" 
                                     class="h-full w-full object-cover" 
                                     src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/avatar/profile-52x52.png') }}" 
                                     alt="Avatar Preview" />
                            </div>
                            <div class="absolute bottom-0 right-0 translate-x-1 translate-y-1 bg-white dark:bg-darkblack-500 rounded-full p-2 border border-bgray-200 dark:border-darkblack-400 shadow-md text-bgray-500 hover:text-success-300 transition-colors z-10">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1">
                            <input type="file" id="avatarInput" name="avatar" onchange="previewImage(event)" class="hidden" accept="image/*">
                            <button type="button" onclick="document.getElementById('avatarInput').click()" 
                                class="mb-2 rounded-lg bg-bgray-100 px-4 py-2 text-xs font-bold text-bgray-700 hover:bg-bgray-200 transition-colors dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                                Pilih Foto Baru
                            </button>
                            <p class="text-xs text-bgray-400">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-bgray-100 dark:border-darkblack-500">

                <div class="grid gap-6 md:grid-cols-2">
                    
                    <div class="group">
                        <label class="mb-2 block text-sm font-bold text-bgray-700 dark:text-white group-focus-within:text-success-300 transition-colors">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-bgray-400 group-focus-within:text-success-300 transition-colors z-10">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                class="w-full rounded-xl border-2 border-bgray-200 bg-white px-4 py-3.5 pl-12 text-sm font-bold text-bgray-900 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" required>
                        </div>
                    </div>

                    <div class="group">
                        <label class="mb-2 block text-sm font-bold text-bgray-700 dark:text-white group-focus-within:text-success-300 transition-colors">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-bgray-400 group-focus-within:text-success-300 transition-colors z-10">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full rounded-xl border-2 border-bgray-200 bg-white px-4 py-3.5 pl-12 text-sm font-bold text-bgray-900 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all" required>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-dashed border-bgray-300 bg-bgray-50 p-6 dark:bg-darkblack-600 dark:border-darkblack-400">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2 bg-white dark:bg-darkblack-500 rounded-lg shadow-sm border border-bgray-200 dark:border-darkblack-400 text-bgray-500">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-bgray-900 dark:text-white">Ganti Password</h4>
                            <p class="text-[10px] text-bgray-400">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>
                    </div>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-bgray-400 group-focus-within:text-warning-300 transition-colors z-10">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <input type="password" name="password" placeholder="Password Baru" 
                                class="w-full rounded-xl border-2 border-bgray-200 bg-white px-4 py-3.5 pl-12 text-sm font-bold text-bgray-900 focus:border-warning-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all">
                        </div>
                        
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-bgray-400 group-focus-within:text-warning-300 transition-colors z-10">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" placeholder="Ulangi Password" 
                                class="w-full rounded-xl border-2 border-bgray-200 bg-white px-4 py-3.5 pl-12 text-sm font-bold text-bgray-900 focus:border-warning-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white transition-all">
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10 flex items-center justify-end gap-4 border-t border-bgray-100 pt-6 dark:border-darkblack-500">
                <a href="{{ route('users.index') }}" class="rounded-lg px-6 py-3 text-sm font-bold text-bgray-500 hover:bg-bgray-100 hover:text-bgray-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-success-300 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-success-300/30 transition-all hover:bg-success-400 hover:shadow-success-400/40 active:scale-95">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview Image Logic
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush