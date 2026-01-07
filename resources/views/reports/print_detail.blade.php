<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ config('app.name') }}</title>
    <style>
        /* 1. RESET & STYLE DASAR */
        @page { margin: 2cm 1.5cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.3;
        }

        /* 2. WARNA TEKS */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-bold { font-weight: bold; }
        
        .text-success { color: #16a34a; } /* Hijau */
        .text-danger { color: #dc2626; }  /* Merah */
        .text-primary { color: #4f46e5; } /* Indigo */
        .text-gray { color: #6b7280; }

        /* 3. HEADER (KOP SURAT) */
        .header-table { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .brand-name { font-size: 20pt; font-weight: bold; color: #4f46e5; letter-spacing: 1px; text-transform: uppercase; }
        .report-title { font-size: 12pt; margin-top: 4px; font-weight: bold; }
        
        /* Tabel Info Kanan Atas */
        .meta-table { float: right; font-size: 9pt; }
        .meta-table td { padding-bottom: 4px; }
        .meta-label { text-align: right; color: #6b7280; padding-right: 10px; }
        .meta-value { text-align: left; font-weight: bold; }

        /* 4. RINGKASAN (SUMMARY BOX) */
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .summary-cell { 
            width: 33.33%; 
            padding: 10px; 
            border: 1px solid #e5e7eb; 
            background-color: #f9fafb; 
            text-align: center;
        }
        .summary-label { display: block; font-size: 8pt; text-transform: uppercase; color: #6b7280; margin-bottom: 5px; letter-spacing: 1px; }
        .summary-value { font-size: 13pt; font-weight: bold; }

        /* 5. TABEL DATA TRANSAKSI */
        .data-table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        .data-table th {
            background-color: #eef2ff;
            color: #3730a3;
            padding: 8px;
            text-transform: uppercase;
            font-size: 8pt;
            border-bottom: 1px solid #a5b4fc;
            text-align: left;
        }
        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        /* Zebra Striping */
        .data-table tr:nth-child(even) { background-color: #fcfcfc; }
        
        /* Badge Bank */
        .badge {
            background: #e5e7eb;
            color: #374151;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            display: inline-block;
        }

        /* 6. FOOTER */
        .footer {
            position: fixed; 
            bottom: -30px; 
            left: 0; 
            right: 0; 
            height: 30px; 
            font-size: 8pt; 
            color: #9ca3af; 
            border-top: 1px solid #e5e7eb; 
            padding-top: 10px;
        }
    </style>
</head>
<body>

    @php
        $start = request('start_date') ? \Carbon\Carbon::parse(request('start_date')) : now()->startOfMonth();
        $end   = request('end_date') ? \Carbon\Carbon::parse(request('end_date')) : now();
        
        // Hitung Summary
        $totalIn = $transactions->where('type', 'income')->sum('amount');
        $totalOut = $transactions->where('type', 'expense')->sum('amount');
        $net = $totalIn - $totalOut;
    @endphp

    <table class="header-table">
        <tr>
            <td width="50%" valign="top">
                <div class="brand-name">MyFinance</div>
                <div class="report-title">Laporan Riwayat Transaksi</div>
            </td>
            
            <td width="50%" valign="top">
                <table class="meta-table" align="right">
                    <tr>
                        <td class="meta-label">Pemilik Akun:</td>
                        <td class="meta-value">{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Dicetak Pada:</td>
                        <td class="meta-value">{{ date('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Periode Data:</td>
                        <td class="meta-value" style="color: #4f46e5;">
                            {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <span class="summary-label">Total Pemasukan</span>
                <span class="summary-value text-success">+ Rp {{ number_format($totalIn, 0, ',', '.') }}</span>
            </td>
            <td class="summary-cell">
                <span class="summary-label">Total Pengeluaran</span>
                <span class="summary-value text-danger">- Rp {{ number_format($totalOut, 0, ',', '.') }}</span>
            </td>
            <td class="summary-cell">
                <span class="summary-label">Arus Kas Bersih</span>
                <span class="summary-value {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">
                    Rp {{ number_format($net, 0, ',', '.') }}
                </span>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 10px; font-weight: bold; font-size: 10pt; color: #4b5563;">Rincian Transaksi</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Waktu</th>
                <th width="30%">Keterangan</th>
                <th width="15%">Kategori</th>
                <th width="15%">Bank / Akun</th>
                <th width="25%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $data)
            <tr>
                <td>
                    <div class="text-bold">{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</div>
                    <div class="text-gray" style="font-size: 8pt; margin-top: 2px;">
                        {{ \Carbon\Carbon::parse($data->date)->format('H:i') }} WIB
                    </div>
                </td>

                <td>
                    {{ $data->description }}
                </td>

                <td>
                    {{ $data->category->name ?? 'Transfer' }}
                </td>

                <td>
                    <span class="badge">{{ $data->wallet->bank_name }}</span>
                </td>

                <td class="text-right">
                    @if($data->type == 'income')
                        <span class="text-success text-bold">+ Rp {{ number_format($data->amount, 0, ',', '.') }}</span>
                    @else
                        <span class="text-danger text-bold">- Rp {{ number_format($data->amount, 0, ',', '.') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                    Tidak ada data transaksi yang ditemukan untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="70%" class="text-left">
                    <i>Dokumen ini digenerate otomatis oleh sistem MyFinance.</i>
                </td>
                <td width="30%" class="text-right">
                    Halaman <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Hal {PAGE_NUM} / {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Helvetica");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) - 30;
            $y = $pdf->get_height() - 30;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.6, 0.6, 0.6));
        }
    </script>

</body>
</html>