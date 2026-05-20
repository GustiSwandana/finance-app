@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-7 md:px-8 md:py-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Debt Tracker</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Kelola utang dan piutang tanpa kehilangan konteks.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                    Catat siapa berutang, kapan jatuh tempo, dan wallet mana yang terdampak saat pelunasan dilakukan. Tampilan ini memisahkan aset dan kewajiban agar lebih cepat dipantau.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="min-w-[180px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Piutang</p>
                    <p class="mt-3 text-3xl font-black text-emerald-300">{{ $receivables->count() }}</p>
                    <p class="mt-2 text-xs text-white/65">Tagihan masuk</p>
                </div>
                <div class="min-w-[180px] rounded-[24px] border border-white/15 bg-slate-950/35 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/60">Utang</p>
                    <p class="mt-3 text-3xl font-black text-rose-300">{{ $payables->count() }}</p>
                    <p class="mt-2 text-xs text-white/65">Kewajiban aktif</p>
                </div>
            </div>
        </div>
    </section>

    @if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
        {{ $errors->first() }}
    </div>
    @endif

    <section class="app-panel p-6">
        <div class="flex flex-col gap-2 border-b border-slate-200 pb-5">
            <p class="app-eyebrow">New Entry</p>
            <h2 class="text-2xl font-black tracking-tight text-slate-950">Catat pinjaman baru</h2>
            <p class="text-sm text-slate-500">Simpan data dasar dulu. Detail lain bisa Anda edit lagi nanti jika diperlukan.</p>
        </div>

        <form action="{{ route('debts.store') }}" method="POST" class="mt-6 space-y-5">
            @csrf
            <div class="grid gap-4 md:grid-cols-5">
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Jenis</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0">
                        <option value="receivable">Piutang</option>
                        <option value="payable">Utang</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Nama orang</label>
                    <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" placeholder="Nama pihak terkait" required>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Jumlah</label>
                    <input type="number" name="amount" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" placeholder="0" required>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Wallet</label>
                    <select name="wallet_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0">
                        @foreach($wallets as $w)
                        <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-900">
                        Simpan Data
                    </button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Jatuh tempo</label>
                    <input type="date" name="due_date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-500">Catatan</label>
                    <input type="text" name="description" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-sky-300 focus:bg-white focus:ring-0" placeholder="Contoh: pinjaman operasional, beli pulsa">
                </div>
            </div>
        </form>
    </section>

    <div class="grid gap-8 xl:grid-cols-2">
        <section class="app-panel p-6">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <p class="app-eyebrow">Receivables</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Piutang aktif</h2>
                    <p class="mt-2 text-sm text-slate-500">Dana yang harus kembali ke Anda.</p>
                </div>
                <span class="rounded-full bg-emerald-100 px-3 py-2 text-xs font-bold uppercase tracking-[0.22em] text-emerald-700">{{ $receivables->count() }} data</span>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($receivables as $debt)
                <article class="rounded-[28px] border border-emerald-100 bg-emerald-50/45 p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">{{ $debt->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $debt->description ?? 'Tanpa catatan tambahan.' }}</p>
                            <p class="mt-2 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Wallet: {{ $debt->wallet->bank_name ?? 'Belum tercatat' }}</p>
                            @if($debt->due_date)
                            <p class="mt-3 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                Jatuh tempo {{ $debt->due_date->format('d M Y') }}
                            </p>
                            @endif
                        </div>
                        <div class="text-left lg:text-right">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nominal</p>
                            <p class="mt-2 text-2xl font-black tracking-tight text-emerald-600">Rp {{ number_format($debt->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 border-t border-emerald-100 pt-4">
                        <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                            @csrf
                            <span class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Masuk ke wallet</span>
                            <select name="wallet_id" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:ring-0">
                                @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-600" onclick="return confirm('Yakin {{ $debt->name }} sudah bayar lunas? Saldo akan bertambah.')">
                                Tandai Lunas
                            </button>
                        </form>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('debts.edit', $debt->id) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Edit</a>
                            <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" onsubmit="return confirm('Hapus piutang ini? Saldo akan disesuaikan kembali jika belum lunas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
                @empty
                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <p class="text-base font-bold text-slate-700">Belum ada piutang aktif</p>
                    <p class="mt-2 text-sm text-slate-500">Saat ada orang berutang ke Anda, datanya akan muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </section>

        <section class="app-panel p-6">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <p class="app-eyebrow">Payables</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Utang saya</h2>
                    <p class="mt-2 text-sm text-slate-500">Kewajiban yang perlu segera diselesaikan.</p>
                </div>
                <span class="rounded-full bg-rose-100 px-3 py-2 text-xs font-bold uppercase tracking-[0.22em] text-rose-700">{{ $payables->count() }} data</span>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($payables as $debt)
                <article class="rounded-[28px] border border-rose-100 bg-rose-50/45 p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">{{ $debt->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $debt->description ?? 'Tanpa catatan tambahan.' }}</p>
                            <p class="mt-2 text-xs font-bold uppercase tracking-[0.18em] text-rose-700">Wallet: {{ $debt->wallet->bank_name ?? 'Belum tercatat' }}</p>
                            @if($debt->due_date)
                            <p class="mt-3 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                Jatuh tempo {{ $debt->due_date->format('d M Y') }}
                            </p>
                            @endif
                        </div>
                        <div class="text-left lg:text-right">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nominal</p>
                            <p class="mt-2 text-2xl font-black tracking-tight text-rose-500">Rp {{ number_format($debt->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 border-t border-rose-100 pt-4">
                        <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                            @csrf
                            <span class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Ambil dari wallet</span>
                            <select name="wallet_id" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:ring-0">
                                @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->bank_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-2xl bg-rose-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-rose-600" onclick="return confirm('Lunasi utang ini sekarang? Saldo akan berkurang.')">
                                Bayar Lunas
                            </button>
                        </form>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('debts.edit', $debt->id) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Edit</a>
                            <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" onsubmit="return confirm('Hapus utang ini? Saldo akan disesuaikan kembali jika belum lunas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
                @empty
                <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <p class="text-base font-bold text-slate-700">Anda bebas utang</p>
                    <p class="mt-2 text-sm text-slate-500">Bagus. Area ini akan tetap kosong sampai ada kewajiban baru.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
