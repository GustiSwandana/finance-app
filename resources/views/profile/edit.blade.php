@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-6xl flex-col gap-8">
    <section class="app-hero">
        <div class="max-w-3xl">
            <p class="app-eyebrow">Profile Settings</p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Atur identitas akun dan keamanan akses Anda.</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                Semua pengaturan profil penting dikumpulkan di satu halaman: data akun, ubah password, dan kontrol penghapusan akun.
            </p>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
        <section class="app-panel p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </section>

        <div class="space-y-6">
            <section class="app-panel p-6 sm:p-8">
                @include('profile.partials.update-password-form')
            </section>

            <section class="app-panel border border-amber-100 bg-amber-50/35 p-6 sm:p-8">
                @include('profile.partials.reset-transactions-form')
            </section>

            <section class="app-panel border border-rose-100 bg-rose-50/45 p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </div>
</div>
@endsection
