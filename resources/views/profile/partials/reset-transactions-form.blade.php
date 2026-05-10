<section class="space-y-6">
    <header class="border-b border-amber-100 pb-5">
        <p class="app-eyebrow text-amber-500">Reset Data</p>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">
            Reset seluruh transaksi
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-600">
            Menghapus semua transaksi milik akun ini dan mereset saldo semua wallet ke Rp 0. Wallet dan kategori tetap disimpan.
        </p>
    </header>

    <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">Transaksi</p>
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $transactionCount ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Wallet</p>
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $walletCount ?? 0 }}</p>
        </div>
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-transaction-reset')"
        @disabled(($transactionCount ?? 0) === 0)
        class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(245,158,11,0.18)] transition hover:-translate-y-0.5 hover:bg-amber-600 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
    >Reset transaksi</button>

    <x-modal name="confirm-transaction-reset" :show="$errors->transactionReset->isNotEmpty()" focusable>
        <form method="post" action="{{ route('transactions.reset-all') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black tracking-tight text-slate-950">
                Reset seluruh transaksi?
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                Tindakan ini akan menghapus seluruh transaksi dan mengubah saldo semua wallet menjadi Rp 0. Untuk melanjutkan, centang persetujuan, ketik <span class="font-black text-slate-950">RESET TRANSAKSI</span>, lalu masukkan password.
            </p>

            <label class="mt-5 flex items-start gap-3 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">
                <input type="checkbox" name="confirm_reset_transactions" value="1" class="mt-0.5 h-5 w-5 rounded border-amber-300 text-amber-500 focus:ring-amber-500">
                <span>Saya paham tindakan ini tidak bisa dibatalkan dan saldo wallet akan menjadi Rp 0.</span>
            </label>
            <x-input-error :messages="$errors->transactionReset->get('confirm_reset_transactions')" class="mt-2" />

            <div class="mt-5">
                <label for="reset_confirmation_text" class="mb-2 block text-sm font-bold text-slate-700">Ketik RESET TRANSAKSI</label>
                <input id="reset_confirmation_text" name="confirmation_text" type="text" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0" autocomplete="off" />
                <x-input-error :messages="$errors->transactionReset->get('confirmation_text')" class="mt-2" />
            </div>

            <div class="mt-5">
                <label for="reset_password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                <input id="reset_password" name="password" type="password" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0" />
                <x-input-error :messages="$errors->transactionReset->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-amber-600">
                    Reset permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
