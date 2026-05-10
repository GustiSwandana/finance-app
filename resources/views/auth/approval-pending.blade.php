<x-guest-layout
    title="Menunggu Persetujuan"
    eyebrow="Account Review"
    heading="Akun Anda sedang menunggu persetujuan admin."
    description="Email Anda sudah terverifikasi. Admin perlu mengaktifkan akun sebelum fitur aplikasi bisa digunakan."
>
    <p class="inline-flex rounded-full bg-amber-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-amber-700 dark:bg-amber-950/60 dark:text-amber-200">Pending Approval</p>
    <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Menunggu persetujuan admin</h2>
    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
        Akun Anda sudah masuk antrean review. Setelah admin menyetujui akun, Anda dapat login dan mengakses dashboard, transaksi, laporan, dan fitur lainnya.
    </p>

    @if (session('status'))
    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">
        {{ session('status') }}
    </div>
    @endif

    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
        <form method="POST" action="{{ route('logout') }}" class="flex-1">
            @csrf
            <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
