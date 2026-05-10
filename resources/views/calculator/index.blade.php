@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
    <section class="app-hero px-6 py-8 sm:px-8 sm:py-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="app-eyebrow">Smart Calculator</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Simulasikan pemasukan dan pengeluaran sebelum benar-benar dicatat.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">Halaman ini membantu menghitung skenario cepat. Hasil akhirnya bisa disalin lalu dipakai sebagai referensi saat input transaksi.</p>
            </div>
            <div class="metric-card min-w-[180px] px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Total item</p>
                <p id="total-count-badge" class="mt-3 text-3xl font-black text-slate-950">0</p>
                <p class="mt-2 text-xs text-slate-500">Rincian aktif</p>
            </div>
        </div>
    </section>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,440px)_minmax(0,1fr)]">
        <section class="app-panel overflow-hidden">
            <div class="bg-slate-950 px-6 py-7 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Input cepat</p>
                <div class="mt-4">
                    <label for="calc-label" class="mb-2 block text-sm font-bold text-slate-200">Keterangan transaksi</label>
                    <input type="text" id="calc-label" placeholder="Contoh: gaji, jualan, listrik, makan siang" class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-sm font-semibold text-white placeholder:text-slate-500 focus:border-indigo-400 focus:ring-0">
                </div>
                <div class="mt-6 text-right">
                    <div id="calc-history" class="min-h-[20px] text-sm font-medium text-slate-400 opacity-0"></div>
                    <div id="calc-display" class="mt-2 break-all text-5xl font-black tracking-tight text-white">0</div>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-5 grid grid-cols-2 gap-3">
                    <button onclick="addItem('in')" class="flex items-center justify-center gap-2 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-200 text-emerald-800">+</span>
                        Pemasukan
                    </button>
                    <button onclick="addItem('out')" class="flex items-center justify-center gap-2 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-200 text-rose-700">-</span>
                        Pengeluaran
                    </button>
                </div>

                <div class="grid grid-cols-4 gap-3">
                    <button onclick="clearDisplay()" class="calc-action text-rose-600">AC</button>
                    <button onclick="deleteDigit()" class="calc-action text-slate-700">Del</button>
                    <button onclick="appendOperator('%')" class="calc-action text-indigo-700">%</button>
                    <button onclick="appendOperator('/')" class="calc-op">/</button>

                    <button onclick="appendNumber('7')" class="calc-num">7</button>
                    <button onclick="appendNumber('8')" class="calc-num">8</button>
                    <button onclick="appendNumber('9')" class="calc-num">9</button>
                    <button onclick="appendOperator('*')" class="calc-op">*</button>

                    <button onclick="appendNumber('4')" class="calc-num">4</button>
                    <button onclick="appendNumber('5')" class="calc-num">5</button>
                    <button onclick="appendNumber('6')" class="calc-num">6</button>
                    <button onclick="appendOperator('-')" class="calc-op">-</button>

                    <button onclick="appendNumber('1')" class="calc-num">1</button>
                    <button onclick="appendNumber('2')" class="calc-num">2</button>
                    <button onclick="appendNumber('3')" class="calc-num">3</button>
                    <button onclick="appendOperator('+')" class="calc-op">+</button>

                    <button onclick="appendNumber('0')" class="calc-num">0</button>
                    <button onclick="appendNumber('000')" class="calc-num text-sm">000</button>
                    <button onclick="appendNumber('.')" class="calc-num">.</button>
                    <button onclick="calculate()" class="calc-op bg-slate-950">=</button>
                </div>
            </div>
        </section>

        <section class="app-panel flex min-h-[560px] flex-col p-6 sm:p-8">
            <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="app-eyebrow bg-slate-100 text-slate-600">Receipt View</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Rincian perhitungan</h2>
                </div>
                <button onclick="resetAll()" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                    Reset semua
                </button>
            </div>

            <div id="list-container" class="custom-scrollbar mt-6 flex-1 space-y-3 overflow-y-auto pr-1">
                <div id="empty-state" class="flex h-full min-h-[280px] flex-col items-center justify-center text-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <p class="mt-4 text-lg font-bold text-slate-800">Belum ada item</p>
                    <p class="mt-2 max-w-sm text-sm text-slate-500">Masukkan nominal di kalkulator kiri, lalu kirim sebagai pemasukan atau pengeluaran untuk membentuk simulasi.</p>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-200 pt-6">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-5">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Saldo bersih</p>
                    <p id="grand-total" class="mt-3 font-mono text-4xl font-black tracking-tight text-slate-950">Rp 0</p>
                </div>
                <button onclick="copyResult()" class="mt-4 w-full rounded-2xl bg-indigo-50 px-4 py-3 text-sm font-bold text-indigo-700 transition hover:bg-indigo-100">
                    Salin Hasil Akhir
                </button>
            </div>
        </section>
    </div>
</div>

<style>
    .calc-num,
    .calc-action,
    .calc-op {
        min-height: 56px;
        border-radius: 18px;
        font-weight: 800;
        transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
    }

    .calc-num {
        border: 1px solid rgba(226, 232, 240, 1);
        background: rgba(255, 255, 255, 0.92);
        color: rgb(51 65 85);
        font-size: 1.125rem;
    }

    .calc-num:hover {
        background: rgb(248 250 252);
        box-shadow: 0 10px 20px rgba(148, 163, 184, 0.12);
    }

    .calc-action {
        border: 1px solid rgba(226, 232, 240, 1);
        background: rgb(248 250 252);
        font-size: 1rem;
    }

    .calc-action:hover {
        background: rgb(241 245 249);
    }

    .calc-op {
        border: 1px solid rgba(15, 23, 42, 0.06);
        background: rgb(79 70 229);
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 14px 28px rgba(79, 70, 229, 0.18);
    }

    .calc-op:hover {
        background: rgb(67 56 202);
    }

    .calc-num:active,
    .calc-action:active,
    .calc-op:active {
        transform: scale(0.97);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 999px;
    }

    .dark .calc-num {
        border-color: rgba(51, 65, 85, 0.9);
        background: rgba(15, 23, 42, 0.82);
        color: rgb(226 232 240);
    }

    .dark .calc-num:hover {
        background: rgba(30, 41, 59, 0.92);
    }

    .dark .calc-action {
        border-color: rgba(51, 65, 85, 0.9);
        background: rgba(15, 23, 42, 0.78);
        color: rgb(203 213 225);
    }

    .dark .calc-action:hover {
        background: rgba(30, 41, 59, 0.92);
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.25s ease-out forwards;
    }
</style>

<script>
    let currentInput = '0';
    let items = [];
    let operator = null;
    let previousInput = '';
    let shouldResetScreen = false;

    const display = document.getElementById('calc-display');
    const historyEl = document.getElementById('calc-history');
    const labelInput = document.getElementById('calc-label');
    const listContainer = document.getElementById('list-container');
    const emptyState = document.getElementById('empty-state');
    const grandTotalEl = document.getElementById('grand-total');
    const totalCountBadge = document.getElementById('total-count-badge');

    function updateDisplay() {
        display.innerText = formatNumber(currentInput);

        if (operator !== null) {
            historyEl.innerText = `${formatNumber(previousInput)} ${operator}`;
            historyEl.classList.remove('opacity-0');
        } else {
            historyEl.innerText = '';
            historyEl.classList.add('opacity-0');
        }
    }

    function formatNumber(num) {
        if (num === '' || num === '.') return num;
        if (isNaN(num)) return 'Error';

        const parts = num.toString().split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return parts.join(',');
    }

    function appendNumber(number) {
        if (currentInput === '0' || shouldResetScreen) {
            currentInput = '';
            shouldResetScreen = false;
        }

        if (number === '.' && currentInput.includes('.')) return;

        if (number === '000') {
            if (currentInput === '') return;
            currentInput += '000';
        } else {
            currentInput += number;
        }

        updateDisplay();
    }

    function clearDisplay() {
        currentInput = '0';
        previousInput = '';
        operator = null;
        updateDisplay();
    }

    function deleteDigit() {
        if (shouldResetScreen) return;
        currentInput = currentInput.toString().slice(0, -1);
        if (currentInput === '') currentInput = '0';
        updateDisplay();
    }

    function appendOperator(op) {
        if (operator !== null) calculate();
        previousInput = currentInput;
        operator = op;
        shouldResetScreen = true;
        updateDisplay();
    }

    function calculate() {
        if (operator === null || shouldResetScreen) return;

        const prev = parseFloat(previousInput);
        const current = parseFloat(currentInput);
        if (isNaN(prev) || isNaN(current)) return;

        let result;
        switch (operator) {
            case '+':
                result = prev + current;
                break;
            case '-':
                result = prev - current;
                break;
            case '*':
                result = prev * current;
                break;
            case '/':
                result = prev / current;
                break;
            case '%':
                result = prev % current;
                break;
        }

        currentInput = result.toString();
        operator = null;
        previousInput = '';
        shouldResetScreen = true;
        updateDisplay();
    }

    function addItem(type) {
        if (operator !== null) calculate();

        const amount = parseFloat(currentInput);
        if (amount <= 0 || isNaN(amount)) {
            alert('Masukkan angka nominal terlebih dahulu.');
            return;
        }

        let label = labelInput.value.trim();
        if (!label) {
            label = type === 'in' ? 'Pemasukan lain' : 'Pengeluaran lain';
        }

        items.push({
            id: Date.now(),
            desc: label,
            amount: amount,
            type: type
        });

        currentInput = '0';
        labelInput.value = '';
        updateDisplay();
        renderList();
    }

    function removeItem(id) {
        items = items.filter(item => item.id !== id);
        renderList();
    }

    function resetAll() {
        if (items.length > 0 && !confirm('Hapus semua rincian transaksi?')) return;
        items = [];
        labelInput.value = '';
        clearDisplay();
        renderList();
    }

    function renderList() {
        listContainer.innerHTML = '';
        let total = 0;

        if (items.length === 0) {
            listContainer.appendChild(emptyState);
            grandTotalEl.innerText = 'Rp 0';
            grandTotalEl.className = 'mt-3 font-mono text-4xl font-black tracking-tight text-slate-950';
            totalCountBadge.innerText = '0';
            return;
        }

        items.forEach(item => {
            total += item.type === 'in' ? item.amount : -item.amount;

            const el = document.createElement('div');
            const isPlus = item.type === 'in';
            const colorClass = isPlus ? 'text-emerald-700' : 'text-rose-600';
            const bgIcon = isPlus ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600';
            const icon = isPlus ? '+' : '-';

            el.className = 'animate-slide-in flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-indigo-200 hover:bg-white';
            el.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full ${bgIcon} font-black">${icon}</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">${item.desc}</p>
                        <p class="mt-1 text-xs text-slate-400">ID ${item.id.toString().slice(-4)}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-black ${colorClass}">${isPlus ? '+' : '-'} ${formatNumber(item.amount)}</p>
                    <button onclick="removeItem(${item.id})" class="mt-1 text-xs font-bold text-slate-400 transition hover:text-rose-500">Hapus</button>
                </div>
            `;
            listContainer.appendChild(el);
        });

        totalCountBadge.innerText = items.length;
        grandTotalEl.innerText = 'Rp ' + formatNumber(total);
        grandTotalEl.className = `mt-3 font-mono text-4xl font-black tracking-tight ${total < 0 ? 'text-rose-600' : 'text-emerald-700'}`;
        listContainer.scrollTop = listContainer.scrollHeight;
    }

    function copyResult() {
        const text = grandTotalEl.innerText.replace(/[^\d,-]/g, '').replace(',', '.');
        navigator.clipboard.writeText(text).then(() => {
            alert('Hasil ' + grandTotalEl.innerText + ' berhasil disalin.');
        });
    }

    window.addEventListener('keydown', (e) => {
        if (document.activeElement === labelInput) {
            if (e.key === 'Enter') {
                labelInput.blur();
            }
            return;
        }

        if (e.key >= 0 && e.key <= 9) appendNumber(e.key);
        if (e.key === '.') appendNumber('.');
        if (e.key === '=' || e.key === 'Enter') calculate();
        if (e.key === 'Backspace') deleteDigit();
        if (e.key === 'Escape') clearDisplay();
        if (['+', '-', '*', '/', '%'].includes(e.key)) appendOperator(e.key);
        if (e.key.toLowerCase() === 'k') appendNumber('000');
    });
</script>
@endsection
