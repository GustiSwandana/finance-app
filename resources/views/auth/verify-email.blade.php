<x-guest-layout
    title="Verifikasi Email"
    eyebrow="Email Verification"
    heading="Satu langkah lagi sebelum akun bisa dipakai penuh."
    description="Periksa inbox email Anda lalu klik tautan verifikasi. Jika email belum masuk, kami bisa kirim ulang dari halaman ini."
>
    <p class="inline-flex rounded-full bg-emerald-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-200">Verify Identity</p>
    <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Verifikasi alamat email</h2>
    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Klik link yang kami kirim saat pendaftaran. Setelah email terverifikasi, akun tetap perlu disetujui admin sebelum fitur aplikasi bisa digunakan.</p>

    @if (session('status') === 'verification-link-sent')
    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">
        Link verifikasi baru sudah dikirim ke email Anda.
    </div>
    @endif

    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
            @csrf
            <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="sm:w-auto">
            @csrf
            <button type="submit" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-bold text-slate-600 transition hover:bg-white hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-950">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
