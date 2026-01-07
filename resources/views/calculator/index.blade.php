@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[85vh] flex flex-col justify-center">

    {{-- Header Sederhana --}}
    <div class="mb-8 text-center md:text-left flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Kalkulator Pintar</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hitung simulasi anggaran dengan cepat sebelum dicatat.</p>
        </div>
        <div class="text-right hidden md:block">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Item</span>
            <div id="total-count-badge" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">0</div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

        {{-- KOLOM KIRI: KALKULATOR (Lebar 5/12) --}}
        <div class="xl:col-span-5 w-full">
            <div class="bg-white dark:bg-darkblack-600 rounded-[2rem] shadow-2xl shadow-indigo-100 dark:shadow-none border border-gray-100 dark:border-darkblack-500 overflow-hidden relative">

                {{-- Bagian Layar & Input --}}
                <div class="bg-slate-900 p-6 pt-8 pb-10 relative overflow-hidden">
                    {{-- Dekorasi Background --}}
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>

                    {{-- Input Label Keterangan --}}
                    <div class="relative z-10 mb-6">
                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Keterangan Transaksi</label>
                        <input type="text" id="calc-label" placeholder="Ketiga: Gaji, Jualan, Listrik..." class="w-full bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 text-sm px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    {{-- Layar Angka --}}
                    <div class="relative z-10 text-right">
                        <div id="calc-history" class="text-slate-400 text-sm h-5 mb-1 font-mono opacity-0 transition-opacity">0</div>
                        <div id="calc-display" class="text-white text-5xl font-mono font-bold tracking-tight break-all">0</div>
                    </div>
                </div>

                {{-- Bagian Keypad --}}
                <div class="p-5 bg-white dark:bg-darkblack-600">

                    {{-- Tombol Aksi Cepat (Masuk/Keluar) --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <button onclick="addItem('in')" class="group relative py-3.5 px-4 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold rounded-2xl flex items-center justify-center gap-2 transition-all active:scale-95 shadow-sm border border-emerald-100 dark:border-emerald-800">
                            <div class="bg-emerald-200 dark:bg-emerald-800 rounded-full p-1 text-emerald-700 dark:text-emerald-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <span class="text-sm">PEMASUKAN</span>
                        </button>
                        <button onclick="addItem('out')" class="group relative py-3.5 px-4 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-bold rounded-2xl flex items-center justify-center gap-2 transition-all active:scale-95 shadow-sm border border-rose-100 dark:border-rose-800">
                            <div class="bg-rose-200 dark:bg-rose-800 rounded-full p-1 text-rose-700 dark:text-rose-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                            </div>
                            <span class="text-sm">PENGELUARAN</span>
                        </button>
                    </div>

                    {{-- Grid Angka --}}
                    <div class="grid grid-cols-4 gap-3">
                        <button onclick="clearDisplay()" class="btn-calc text-rose-500 bg-rose-50 hover:bg-rose-100">AC</button>
                        <button onclick="deleteDigit()" class="btn-calc text-slate-600 bg-slate-100 hover:bg-slate-200">⌫</button>
                        <button onclick="appendOperator('%')" class="btn-calc text-indigo-600 bg-indigo-50 hover:bg-indigo-100">%</button>
                        <button onclick="appendOperator('/')" class="btn-calc text-white bg-indigo-500 hover:bg-indigo-600 text-xl">÷</button>

                        <button onclick="appendNumber('7')" class="btn-calc-num">7</button>
                        <button onclick="appendNumber('8')" class="btn-calc-num">8</button>
                        <button onclick="appendNumber('9')" class="btn-calc-num">9</button>
                        <button onclick="appendOperator('*')" class="btn-calc text-white bg-indigo-500 hover:bg-indigo-600 text-xl">×</button>

                        <button onclick="appendNumber('4')" class="btn-calc-num">4</button>
                        <button onclick="appendNumber('5')" class="btn-calc-num">5</button>
                        <button onclick="appendNumber('6')" class="btn-calc-num">6</button>
                        <button onclick="appendOperator('-')" class="btn-calc text-white bg-indigo-500 hover:bg-indigo-600 text-xl">−</button>

                        <button onclick="appendNumber('1')" class="btn-calc-num">1</button>
                        <button onclick="appendNumber('2')" class="btn-calc-num">2</button>
                        <button onclick="appendNumber('3')" class="btn-calc-num">3</button>
                        <button onclick="appendOperator('+')" class="btn-calc text-white bg-indigo-500 hover:bg-indigo-600 text-xl">+</button>

                        <button onclick="appendNumber('0')" class="btn-calc-num">0</button>
                        <button onclick="appendNumber('000')" class="btn-calc-num text-sm font-bold text-indigo-600 dark:text-indigo-400">000</button>
                        <button onclick="appendNumber('.')" class="btn-calc-num">.</button>
                        <button onclick="calculate()" class="btn-calc bg-slate-800 text-white hover:bg-slate-900 text-xl">=</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STRUK / RINCIAN (Lebar 7/12) --}}
        <div class="xl:col-span-7 w-full h-full">
            <div class="bg-gray-50 dark:bg-darkblack-600 rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-darkblack-400 p-6 md:p-8 h-full flex flex-col min-h-[500px]">

                {{-- Judul Struk --}}
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-darkblack-500">
                    <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        Rincian Perhitungan
                    </h3>
                    <button onclick="resetAll()" class="text-xs font-bold text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                        RESET SEMUA
                    </button>
                </div>

                {{-- List Container --}}
                <div id="list-container" class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                    {{-- Empty State --}}
                    <div id="empty-state" class="flex flex-col items-center justify-center h-full py-10 opacity-50">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" class="w-20 h-20 mb-3 grayscale opacity-30" alt="Empty">
                        <p class="text-gray-400 text-sm text-center">Belum ada transaksi.<br>Gunakan tombol di kiri untuk mulai.</p>
                    </div>
                </div>

                {{-- Footer Total --}}
                <div class="mt-6 pt-6 border-t-2 border-gray-200 dark:border-darkblack-400">
                    <div class="flex justify-between items-center bg-white dark:bg-darkblack-500 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-darkblack-400">
                        <span class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Sisa Bersih</span>
                        <span id="grand-total" class="font-mono font-bold text-3xl md:text-4xl text-gray-900 dark:text-white">Rp 0</span>
                    </div>

                    {{-- Tombol Copy --}}
                    <button onclick="copyResult()" class="w-full mt-3 py-3 text-sm font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        Salin Hasil Akhir
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- CSS KHUSUS --}}
<style>
    /* Button Styles */
    .btn-calc {
        @apply h-14 rounded-2xl font-bold text-lg transition-all active: scale-95 flex items-center justify-center shadow-sm border border-transparent dark:border-darkblack-500;
    }

    .btn-calc-num {
        @apply h-14 rounded-2xl font-bold text-xl bg-white text-gray-700 hover: bg-gray-50 shadow-sm border border-gray-200 dark:bg-darkblack-500 dark:border-darkblack-400 dark:text-gray-200 dark:hover:bg-darkblack-400 transition-all active:scale-95;
    }

    /* Scrollbar Halus */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #334155;
    }

    /* Animasi Masuk */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }

</style>

{{-- JAVASCRIPT LOGIC --}}
<script>
    let currentInput = '0';
    let items = [];
    let operator = null;
    let previousInput = '';
    let shouldResetScreen = false;

    // Elements
    const display = document.getElementById('calc-display');
    const historyEl = document.getElementById('calc-history');
    const labelInput = document.getElementById('calc-label');
    const listContainer = document.getElementById('list-container');
    const emptyState = document.getElementById('empty-state');
    const grandTotalEl = document.getElementById('grand-total');
    const totalCountBadge = document.getElementById('total-count-badge');

    // --- LOGIKA KALKULATOR ---

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
        let parts = num.toString().split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return parts.join(',');
    }

    function appendNumber(number) {
        if (currentInput === '0' || shouldResetScreen) {
            currentInput = '';
            shouldResetScreen = false;
        }
        if (number === '.' && currentInput.includes('.')) return;

        // Handle tombol 000
        if (number === '000') {
            if (currentInput === '') return; // Jangan mulai dengan 000
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
        let result;
        const prev = parseFloat(previousInput);
        const current = parseFloat(currentInput);
        if (isNaN(prev) || isNaN(current)) return;

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
        updateDisplay();
        shouldResetScreen = true;
    }

    // --- LOGIKA LIST / STRUK ---

    function addItem(type) {
        if (operator !== null) calculate();

        const amount = parseFloat(currentInput);
        if (amount <= 0 || isNaN(amount)) {
            alert("Masukkan angka nominal terlebih dahulu.");
            return;
        }

        // Tentukan Label
        let label = labelInput.value.trim();
        if (!label) {
            label = type === 'in' ? 'Pemasukan Lain' : 'Pengeluaran Lain';
        }

        items.push({
            id: Date.now()
            , desc: label
            , amount: amount
            , type: type
        });

        // Reset
        currentInput = '0';
        labelInput.value = '';
        labelInput.placeholder = "Item selanjutnya...";
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
        labelInput.placeholder = "Ketiga: Gaji, Jualan, Listrik...";
        clearDisplay();
        renderList();
    }

    function renderList() {
        listContainer.innerHTML = '';
        let total = 0;

        if (items.length === 0) {
            listContainer.appendChild(emptyState);
            grandTotalEl.innerText = 'Rp 0';
            grandTotalEl.className = "font-mono font-bold text-3xl md:text-4xl text-gray-900 dark:text-white";
            totalCountBadge.innerText = '0';
            return;
        }

        items.forEach(item => {
            if (item.type === 'in') total += item.amount;
            else total -= item.amount;

            const el = document.createElement('div');
            const isPlus = item.type === 'in';
            const colorClass = isPlus ? 'text-emerald-600' : 'text-rose-600';
            const bgIcon = isPlus ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-rose-100 dark:bg-rose-900/30';
            const iconPath = isPlus ?
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>';

            el.className = "flex justify-between items-center bg-white dark:bg-darkblack-500 p-4 rounded-xl border border-gray-100 dark:border-darkblack-400 shadow-sm animate-slide-in group hover:border-indigo-200 transition-colors";

            el.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="${bgIcon} w-10 h-10 rounded-full flex items-center justify-center ${colorClass}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">${iconPath}</svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-white text-sm md:text-base line-clamp-1">${item.desc}</p>
                        <p class="text-xs text-gray-400 font-mono">ID: ${item.id.toString().slice(-4)}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block font-mono font-bold ${colorClass} text-lg">
                        ${isPlus ? '+' : '-'} ${formatNumber(item.amount)}
                    </span>
                    <button onclick="removeItem(${item.id})" class="text-xs text-gray-300 hover:text-rose-500 underline decoration-dashed opacity-0 group-hover:opacity-100 transition-opacity">Hapus</button>
                </div>
            `;
            listContainer.appendChild(el);
        });

        // Update Total
        totalCountBadge.innerText = items.length;
        grandTotalEl.innerText = 'Rp ' + formatNumber(total);
        if (total < 0) grandTotalEl.className = "font-mono font-bold text-3xl md:text-4xl text-rose-600";
        else grandTotalEl.className = "font-mono font-bold text-3xl md:text-4xl text-emerald-600";

        // Auto scroll
        listContainer.scrollTop = listContainer.scrollHeight;
    }

    function copyResult() {
        let text = grandTotalEl.innerText.replace(/[^\d,-]/g, '').replace(',', '.'); // Bersihkan Rp dan titik
        navigator.clipboard.writeText(text).then(() => {
            alert('Hasil ' + grandTotalEl.innerText + ' berhasil disalin!');
        });
    }

    // --- PERBAIKAN UTAMA: KEYBOARD LISTENER YANG AMAN ---
    window.addEventListener('keydown', (e) => {
        // PERBAIKAN: Jika user sedang mengetik di Input Label, JANGAN jalankan kalkulator
        if (document.activeElement === labelInput) {
            if (e.key === 'Enter') {
                // Opsional: Jika enter di input text, pindah fokus ke kalkulator atau submit
                labelInput.blur(); // Lepas fokus agar bisa hitung
            }
            return; // Stop fungsi di bawah ini
        }

        // Shortcut Kalkulator
        if (e.key >= 0 && e.key <= 9) appendNumber(e.key);
        if (e.key === '.') appendNumber('.');
        if (e.key === '=' || e.key === 'Enter') calculate();
        if (e.key === 'Backspace') deleteDigit();
        if (e.key === 'Escape') clearDisplay();
        if (['+', '-', '*', '/', '%'].includes(e.key)) appendOperator(e.key);

        // Shortcut k = 000 (Fitur Tambahan)
        if (e.key.toLowerCase() === 'k') appendNumber('000');
    });

</script>
@endsection
