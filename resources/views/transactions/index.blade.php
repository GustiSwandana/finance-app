@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">

    <div class="mb-8">
        <h2 class="text-3xl font-black text-bgray-900 dark:text-white tracking-tight">Transaksi Baru</h2>
        <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400">Catat arus kas manual atau gunakan AI Scan.</p>
    </div>

    <div class="flex flex-col xl:flex-row gap-8 items-start">

        <div class="w-full xl:w-1/3 xl:sticky xl:top-24 z-10">

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-xl shadow-indigo-200 dark:shadow-none mb-6">
                <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-md border border-white/20 shadow-inner ring-1 ring-white/10">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <path d="M4 7V4h3" />
                                <path d="M20 7V4h-3" />
                                <path d="M4 17v3h3" />
                                <path d="M20 17v3h-3" />
                                <path d="M9 12h6" /> </svg>
                            <div class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-400 animate-pulse"></div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-black text-xl tracking-tight text-white">AI Scan Struk</h4>
                                <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest text-white border border-white/10">PRO</span>
                            </div>
                            <p class="text-xs font-medium text-indigo-100/90 mt-1">Upload struk, otomatis jadi data.</p>
                        </div>
                    </div>

                    <form action="{{ route('transactions.scan') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 mt-4">
                        @csrf
                        <div class="flex-1 relative">
                            <select name="wallet_id" class="w-full appearance-none rounded-lg border-none bg-black/20 py-2.5 pl-3 pr-8 text-xs font-bold text-black focus:ring-2 focus:ring-black/50 cursor-pointer">
                                @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}" class="text-black-900">{{ $wallet->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="cursor-pointer flex items-center justify-center rounded-lg bg-white text-black-700 px-4 py-2.5 text-xs font-bold hover:bg-black-50 transition shadow-sm active:scale-95">
                            <input type="file" name="receipt" class="hidden" onchange="this.form.submit()">
                            <span class="text-black">Upload 📸</span>
                        </label>
                    </form>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-1 dark:bg-darkblack-600 shadow-lg border border-bgray-100 dark:border-darkblack-400">

                <div class="flex p-1.5 bg-bgray-100 dark:bg-darkblack-500 rounded-xl mb-6">
                    <button onclick="switchTab('income')" id="tab-income" class="w-1/2 py-2.5 rounded-lg text-sm font-bold text-gray-500 transition-all duration-300 hover:text-gray-700">
                        Pemasukan
                    </button>
                    <button onclick="switchTab('expense')" id="tab-expense" class="w-1/2 py-2.5 rounded-lg text-sm font-bold text-gray-500 transition-all duration-300 hover:text-gray-700">
                        Pengeluaran
                    </button>
                </div>

                <div class="px-5 pb-6">
                    @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs font-medium flex items-start gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                        <ul class="list-none space-y-1">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                    @endif

                    <style>
                        input[type=number]::-webkit-inner-spin-button,
                        input[type=number]::-webkit-outer-spin-button {
                            -webkit-appearance: none;
                            margin: 0;
                        }

                        input[type=number] {
                            -moz-appearance: textfield;
                        }

                    </style>

                    <div id="form-income" class="block animate-fade-in">
                        <form action="{{ route('income.store') }}" method="POST">
                            @csrf

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-bgray-400 uppercase tracking-wider mb-2">Nominal Masuk</label>
                                <div class="group relative flex items-center w-full rounded-2xl bg-gray-50 border-2 border-gray-100 focus-within:border-success-300 focus-within:bg-white focus-within:shadow-lg focus-within:shadow-success-100/20 transition-all duration-300 ease-out dark:bg-darkblack-500 dark:border-darkblack-400">
                                    <div class="pl-4 pr-2 pointer-events-none">
                                        <span class="text-2xl font-black text-gray-300 group-focus-within:text-success-400 transition-colors duration-300">Rp</span>
                                    </div>
                                    <input type="number" name="amount" class="w-full bg-transparent border-none py-4 pr-4 text-2xl font-black text-gray-900 placeholder-gray-200 focus:ring-0 dark:text-white dark:placeholder-gray-600" placeholder="0" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs font-bold text-bgray-500">Ke Bank</label>
                                        <button type="button" onclick="addBank()" class="text-[10px] font-bold text-success-300 hover:underline">+ Baru</button>
                                    </div>
                                    <div class="relative">
                                        <select name="wallet_id" id="income_wallet_id" class="w-full appearance-none rounded-lg border border-bgray-200 px-3 py-2.5 text-sm font-bold text-bgray-700 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all hover:border-success-200">
                                            @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->bank_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-bgray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9l6 6 6-6" /></svg></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs font-bold text-bgray-500">Kategori</label>
                                        <button type="button" onclick="addCategory('income')" class="text-[10px] font-bold text-success-300 hover:underline">+ Baru</button>
                                    </div>
                                    <div class="relative">
                                        <select name="category_id" id="income_category_id" class="w-full appearance-none rounded-lg border border-bgray-200 px-3 py-2.5 text-sm font-bold text-bgray-700 focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all hover:border-success-200">
                                            @foreach($incomeCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-bgray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9l6 6 6-6" /></svg></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-bgray-500 mb-1.5">Detail</label>
                                <div class="flex gap-3">
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-1/3 rounded-lg border border-bgray-200 text-xs font-medium focus:border-success-300 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                    <input type="text" name="description" placeholder="Catatan (Opsional)" class="w-2/3 rounded-lg border border-bgray-200 text-xs font-medium focus:border-success-300 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-success-300 py-3.5 text-sm font-bold text-white shadow-lg shadow-success-300/30 hover:bg-success-400 hover:-translate-y-0.5 transition-all active:scale-95">
                                Simpan Pemasukan
                            </button>
                        </form>
                    </div>

                    <div id="form-expense" class="hidden animate-fade-in">
                        <form action="{{ route('expense.store') }}" method="POST">
                            @csrf

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-bgray-400 uppercase tracking-wider mb-2">Nominal Keluar</label>
                                <div class="group relative flex items-center w-full rounded-2xl bg-gray-50 border-2 border-gray-100 focus-within:border-error-300 focus-within:bg-white focus-within:shadow-lg focus-within:shadow-error-100/20 transition-all duration-300 ease-out dark:bg-darkblack-500 dark:border-darkblack-400">
                                    <div class="pl-4 pr-2 pointer-events-none">
                                        <span class="text-2xl font-black text-gray-300 group-focus-within:text-error-400 transition-colors duration-300">Rp</span>
                                    </div>
                                    <input type="number" name="amount" class="w-full bg-transparent border-none py-4 pr-4 text-2xl font-black text-gray-900 placeholder-gray-200 focus:ring-0 dark:text-white dark:placeholder-gray-600" placeholder="0" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs font-bold text-bgray-500">Dari Bank</label>
                                        <button type="button" onclick="addBank()" class="text-[10px] font-bold text-error-300 hover:underline">+ Baru</button>
                                    </div>
                                    <div class="relative">
                                        <select name="wallet_id" id="expense_wallet_id" class="w-full appearance-none rounded-lg border border-bgray-200 px-3 py-2.5 text-sm font-bold text-bgray-700 focus:border-error-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all hover:border-error-200">
                                            @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->bank_name }} ({{ number_format($wallet->balance) }})</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-bgray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9l6 6 6-6" /></svg></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs font-bold text-bgray-500">Kategori</label>
                                        <button type="button" onclick="addCategory('expense')" class="text-[10px] font-bold text-error-300 hover:underline">+ Baru</button>
                                    </div>
                                    <div class="relative">
                                        <select name="category_id" id="expense_category_id" class="w-full appearance-none rounded-lg border border-bgray-200 px-3 py-2.5 text-sm font-bold text-bgray-700 focus:border-error-300 focus:ring-0 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white cursor-pointer transition-all hover:border-error-200">
                                            @foreach($expenseCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-bgray-400"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9l6 6 6-6" /></svg></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-bgray-500 mb-1.5">Detail</label>
                                <div class="flex gap-3">
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-1/3 rounded-lg border border-bgray-200 text-xs font-medium focus:border-error-300 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                    <input type="text" name="description" placeholder="Keperluan..." class="w-2/3 rounded-lg border border-bgray-200 text-xs font-medium focus:border-error-300 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-error-300 py-3.5 text-sm font-bold text-white shadow-lg shadow-error-300/30 hover:bg-error-400 hover:-translate-y-0.5 transition-all active:scale-95">
                                Simpan Pengeluaran
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="w-full xl:w-2/3">
            <div class="rounded-2xl bg-white p-6 dark:bg-darkblack-600 shadow-sm border border-bgray-100 dark:border-darkblack-400">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-bgray-100 text-bgray-500 dark:bg-darkblack-500 dark:text-white">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Riwayat Terbaru</h3>
                            <p class="text-xs text-bgray-500">5 transaksi terakhir Anda</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-bgray-500 bg-bgray-100 px-3 py-1 rounded-full dark:bg-darkblack-500">{{ date('F Y') }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-left border-b border-bgray-100 dark:border-darkblack-400">
                                <th class="px-4 py-3 text-xs font-bold text-bgray-400 uppercase tracking-wider">Detail</th>
                                <th class="px-4 py-3 text-xs font-bold text-bgray-400 uppercase tracking-wider">Bank</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-bgray-400 uppercase tracking-wider">Nominal</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-bgray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-500">
                            @forelse($transactions as $data)
                            <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500 transition-colors group">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $data->type == 'income' ? 'bg-success-100 text-success-300' : ($data->type == 'expense' ? 'bg-error-100 text-error-300' : 'bg-warning-100 text-warning-300') }}">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                @if($data->type == 'income')
                                                <path d="M12 19V5M5 12l7-7 7 7" />
                                                @elseif($data->type == 'expense')
                                                <path d="M12 5v14M12 19l4-4M12 19l-4-4" />
                                                @else
                                                <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" /> @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-bgray-900 dark:text-white">{{ $data->category->name ?? 'Transfer' }}</p>
                                            <p class="text-[11px] text-bgray-500">{{ \Carbon\Carbon::parse($data->date)->format('d M') }} • {{ Str::limit($data->description, 20) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-block rounded-lg bg-bgray-50 border border-bgray-100 px-2.5 py-1 text-xs font-bold text-bgray-600 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-white">
                                        {{ $data->wallet->bank_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-sm font-black tracking-tight {{ $data->type == 'income' ? 'text-success-300' : ($data->type == 'expense' ? 'text-error-300' : 'text-warning-300') }}">
                                        {{ $data->type == 'income' ? '+' : ($data->type == 'expense' ? '-' : '') }}
                                        Rp {{ number_format($data->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('transactions.edit', $data->id) }}" class="p-2 rounded-lg text-bgray-400 hover:text-warning-300 hover:bg-warning-50 transition-colors">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                                        </a>
                                        <form action="{{ route('transactions.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-bgray-400 hover:text-error-300 hover:bg-error-50 transition-colors">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-400 text-sm">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Belum ada data transaksi.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $transactions->links('pagination::simple-tailwind') }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(type) {
        const formIncome = document.getElementById('form-income');
        const formExpense = document.getElementById('form-expense');
        const tabIncome = document.getElementById('tab-income');
        const tabExpense = document.getElementById('tab-expense');

        if (type === 'income') {
            formIncome.classList.remove('hidden');
            formExpense.classList.add('hidden');

            // Tab Style Active (Income) - White bg + Shadow + Green Text
            tabIncome.classList.add('bg-white', 'text-success-300', 'shadow-sm', 'dark:bg-darkblack-600');
            tabIncome.classList.remove('text-bgray-500', 'hover:text-bgray-700');

            // Tab Style Inactive (Expense)
            tabExpense.classList.remove('bg-white', 'text-error-300', 'shadow-sm', 'dark:bg-darkblack-600');
            tabExpense.classList.add('text-bgray-500', 'hover:text-bgray-700');

        } else {
            formExpense.classList.remove('hidden');
            formIncome.classList.add('hidden');

            // Tab Style Active (Expense) - White bg + Shadow + Red Text
            tabExpense.classList.add('bg-white', 'text-error-300', 'shadow-sm', 'dark:bg-darkblack-600');
            tabExpense.classList.remove('text-bgray-500', 'hover:text-bgray-700');

            // Tab Style Inactive (Income)
            tabIncome.classList.remove('bg-white', 'text-success-300', 'shadow-sm', 'dark:bg-darkblack-600');
            tabIncome.classList.add('text-bgray-500', 'hover:text-bgray-700');
        }
    }

    // Set default active tab style
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'expense') {
            switchTab('expense');
        } else {
            switchTab('income'); // Default Style Trigger
        }
    });

    async function addBank() {
        let name = prompt("Nama Bank Baru:");
        if (!name) return;
        let initialBalance = prompt("Saldo Awal (0 jika kosong):", "0");
        try {
            let response = await fetch("{{ route('wallets.storeAjax') }}", {
                method: "POST"
                , headers: {
                    "Content-Type": "application/json"
                    , "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
                , body: JSON.stringify({
                    bank_name: name
                    , balance: initialBalance
                })
            });
            let result = await response.json();
            if (result.success) {
                let newOption = new Option(result.data.bank_name, result.data.id, true, true);
                document.getElementById('income_wallet_id').add(newOption, undefined);
                document.getElementById('expense_wallet_id').add(newOption.cloneNode(true), undefined);
                alert("Bank ditambahkan!");
            }
        } catch (e) {
            alert("Gagal.");
        }
    }

    async function addCategory(type) {
        let name = prompt("Nama Kategori Baru:");
        if (!name) return;
        try {
            let response = await fetch("{{ route('categories.storeAjax') }}", {
                method: "POST"
                , headers: {
                    "Content-Type": "application/json"
                    , "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
                , body: JSON.stringify({
                    name: name
                    , type: type
                })
            });
            let result = await response.json();
            if (result.success) {
                let newOption = new Option(result.data.name, result.data.id, true, true);
                let targetId = type === 'income' ? 'income_category_id' : 'expense_category_id';
                document.getElementById(targetId).add(newOption, undefined);
                alert("Kategori ditambahkan!");
            }
        } catch (e) {
            alert("Gagal.");
        }
    }

</script>
@endpush
