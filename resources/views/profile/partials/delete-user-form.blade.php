<section class="space-y-6">
    <header class="border-b border-rose-100 pb-5">
        <p class="app-eyebrow text-rose-400">Danger Zone</p>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">
            {{ __('Hapus akun') }}
        </h2>

        <p class="mt-2 text-sm text-slate-600">
            {{ __('Tindakan ini permanen. Semua data akun, wallet, kategori, transaksi, dan resource terkait akan dihapus setelah konfirmasi berhasil.') }}
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-2xl bg-rose-500 px-5 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(244,63,94,0.18)] transition hover:-translate-y-0.5 hover:bg-rose-600"
    >{{ __('Hapus akun') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black tracking-tight text-slate-950">
                {{ __('Yakin ingin menghapus akun ini?') }}
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                Setelah dihapus, akun dan seluruh datanya tidak bisa dipulihkan lagi. Untuk melanjutkan, centang persetujuan, ketik <span class="font-black text-slate-950">HAPUS AKUN</span>, lalu masukkan password.
            </p>

            <label class="mt-5 flex items-start gap-3 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                <input type="checkbox" name="confirm_permanent_delete" value="1" class="mt-0.5 h-5 w-5 rounded border-rose-300 text-rose-500 focus:ring-rose-500">
                <span>Saya paham akun dan seluruh data saya akan dihapus permanen.</span>
            </label>
            <x-input-error :messages="$errors->userDeletion->get('confirm_permanent_delete')" class="mt-2" />

            <div class="mt-5">
                <label for="delete_confirmation_text" class="mb-2 block text-sm font-bold text-slate-700">Ketik HAPUS AKUN</label>
                <input
                    id="delete_confirmation_text"
                    name="confirmation_text"
                    type="text"
                    class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0"
                    autocomplete="off"
                />
                <x-input-error :messages="$errors->userDeletion->get('confirmation_text')" class="mt-2" />
            </div>

            <div class="mt-5">
                <label for="delete_password" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Password') }}</label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-rose-300 focus:bg-white focus:ring-0"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    {{ __('Batal') }}
                </button>
                <button type="submit" class="rounded-2xl bg-rose-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-600">
                    {{ __('Hapus akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
