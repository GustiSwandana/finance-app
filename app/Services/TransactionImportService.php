<?php

namespace App\Services;

use Carbon\Carbon;

class TransactionImportService
{
    public function parse(string $text)
    {
        // 1. BERSIHKAN TEKS (Hapus karakter yang mengganggu)
        // Hapus tanda kutip ("), Tab (\t), Return (\r)
        $text = str_replace(['"', "\t", "\r"], " ", $text);
        
        // 2. DETEKSI BANK
        $upperText = strtoupper($text);

        if (str_contains($upperText, 'MANDIRI') || str_contains($text, 'Livin') || str_contains($text, '15000')) {
            return $this->parseMandiri($text);
        }
        elseif (str_contains($upperText, 'TAHAPAN') || str_contains($upperText, 'M-BCA') || str_contains($text, 'DB')) {
            return $this->parseBCA($text);
        }
        // Deteksi BRI (BritAma atau format tanggal khas BRI)
        elseif (str_contains($upperText, 'BRI') || str_contains($upperText, 'BRITAMA') || str_contains($upperText, '2260')) {
            return $this->parseBRI($text);
        }
        
        return [];
    }

    // ============================================================
    // 1. PARSER BRI (LOGIKA "3 KOLOM ANGKA")
    // ============================================================
    private function parseBRI($text)
    {
        $results = [];

        // A. NORMALISASI
        // Ganti semua spasi ganda menjadi spasi tunggal
        $cleanText = preg_replace('/\s+/', ' ', $text);

        // B. PECAH PER TRANSAKSI (Berdasarkan Tanggal dd/mm/yy)
        // Kita tambahkan penanda '###' sebelum setiap tanggal
        $splitText = preg_replace('/(\d{2}\/\d{2}\/\d{2})/', "###$1", $cleanText);
        $blocks = explode('###', $splitText);

        foreach ($blocks as $block) {
            $block = trim($block);

            // Pastikan blok ini dimulai dengan tanggal valid (dd/mm/yy)
            // Contoh: 01/12/24 10:52:57 ...
            if (!preg_match('/^(\d{2}\/\d{2}\/\d{2})/', $block, $dateMatch)) {
                continue;
            }

            $rawDate = $dateMatch[1];

            // C. CARI JAM (Opsional, format HH:mm:ss)
            $timeStr = '00:00:00';
            if (preg_match('/(\d{2}:\d{2}:\d{2})/', $block, $timeMatch)) {
                $timeStr = $timeMatch[1];
            }

            // D. CARI UANG (KUNCI KEBERHASILAN)
            // BRI selalu mengakhiri baris transaksi dengan 3 angka desimal: [Debit] [Kredit] [Saldo]
            // Format angka: 100,000.00 (Koma ribuan, Titik desimal)
            
            // Regex ini mencari angka yang diakhiri .00 atau .xx
            preg_match_all('/([\d,]+\.\d{2})/', $block, $moneyMatches);
            $moneys = $moneyMatches[0];
            $count = count($moneys);

            // Kita butuh minimal 3 angka di akhir blok ini
            if ($count >= 3) {
                // Ambil 3 angka terakhir
                $debetRaw  = $moneys[$count - 3];  // Kolom Debit
                $kreditRaw = $moneys[$count - 2];  // Kolom Kredit
                // $saldoRaw = $moneys[$count - 1]; // Kolom Saldo (tidak dipakai)

                // Bersihkan format angka (Hapus koma)
                $debet = (float) str_replace(',', '', $debetRaw);
                $kredit = (float) str_replace(',', '', $kreditRaw);

                $amount = 0;
                $type = 'expense';

                // Tentukan Tipe Transaksi
                if ($debet > 0 && $credit == 0) {
                    $amount = $debet;
                    $type = 'expense'; // Uang Keluar
                } elseif ($kredit > 0) {
                    $amount = $kredit;
                    $type = 'income'; // Uang Masuk
                }

                // Jika valid ada nominalnya
                if ($amount > 0) {
                    
                    // E. BERSIHKAN DESKRIPSI
                    $desc = $block;
                    $desc = str_replace($rawDate, '', $desc); // Hapus tanggal
                    $desc = str_replace($timeStr, '', $desc); // Hapus jam
                    
                    // Hapus semua angka uang yang ditemukan di blok ini agar deskripsi bersih
                    foreach($moneys as $m) {
                        $desc = str_replace($m, '', $desc);
                    }

                    // Hapus kata-kata sampah header tabel BRI
                    $trash = ['Tanggal Transaksi', 'Uraian Transaksi', 'Teller', 'User ID', 'Debet', 'Kredit', 'Saldo', '0.00', ','];
                    $desc = str_replace($trash, '', $desc);
                    
                    // Hapus ID Teller (Angka 7-9 digit yang berdiri sendiri)
                    $desc = preg_replace('/\b\d{6,9}\b/', '', $desc);
                    
                    // Rapikan spasi
                    $desc = trim(preg_replace('/\s+/', ' ', $desc));

                    // Format Tanggal Database
                    try {
                        $dateTime = Carbon::createFromFormat('d/m/y H:i:s', "$rawDate $timeStr")->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        $dateTime = now()->format('Y-m-d H:i:s');
                    }

                    $results[] = [
                        'bank_detected' => 'BRI (BritAma)',
                        'date' => $dateTime,
                        'description' => $desc,
                        'amount' => $amount,
                        'type' => $type,
                        'category_guess' => $this->guessCategory($desc)
                    ];
                }
            }
        }

        return $results;
    }

    // ============================================================
    // 2. PARSER MANDIRI (LIVIN)
    // ============================================================
    private function parseMandiri($text)
    {
        // Bersihkan spasi ganda dulu
        $text = preg_replace('/\s+/', ' ', $text);
        
        $results = [];
        // Pola: (+/-) (Nominal) (Jam) (WIB) (Tanggal)
        // Contoh: -70.000,0015:27:17 WIB20 Aug 2025
        $pattern = '/([+-])([\d\.]+,\d{2})\s*(\d{2}:\d{2}:\d{2})\s*WIB\s*(\d{2}\s+[A-Za-z]{3}\s+\d{4})/';
        
        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) return [];

        foreach ($matches[0] as $index => $fullMatch) {
            $sign = $matches[1][$index][0];
            $amountRaw = $matches[2][$index][0];
            $timeRaw = $matches[3][$index][0];
            $dateRaw = $matches[4][$index][0];
            $matchPos = $fullMatch[1];

            $amount = (float) str_replace(['.', ','], ['', '.'], $amountRaw);
            $type = ($sign === '+') ? 'income' : 'expense';

            try {
                $date = Carbon::createFromFormat('d M Y H:i:s', "$dateRaw $timeRaw")->format('Y-m-d H:i:s');
            } catch (\Exception $e) { $date = now()->format('Y-m-d H:i:s'); }

            // Ambil deskripsi (mundur ke belakang)
            $prevEnd = ($index > 0) ? $matches[0][$index-1][1] + strlen($matches[0][$index-1][0]) : 0;
            $rawDesc = substr($text, $prevEnd, $matchPos - $prevEnd);
            
            // Bersihkan deskripsi Mandiri
            $desc = preg_replace('/[\d\.]+,\d{2}\s*\d*$/', '', trim($rawDesc)); // Hapus saldo
            $desc = str_replace(['Tanggal', 'Date', 'Nominal', 'Keterangan'], '', $desc);
            $desc = trim(preg_replace('/\s+/', ' ', $desc));
            
            if (strlen($desc) < 2) $desc = "Transaksi Mandiri";

            $results[] = [
                'bank_detected' => 'Mandiri (Livin)',
                'date' => $date,
                'description' => $desc,
                'amount' => $amount,
                'type' => $type,
                'category_guess' => $this->guessCategory($desc)
            ];
        }
        return $results;
    }

    // ============================================================
    // 3. PARSER BCA
    // ============================================================
    private function parseBCA($text)
    {
        $text = preg_replace('/(\.00)(\d{2}\/\d{2})/', "$1\n$2", $text);
        preg_match('/PERIODE\s*:\s*[A-Z]+\s+(\d{4})/', $text, $yearMatch);
        $year = $yearMatch[1] ?? date('Y');

        $lines = explode("\n", $text);
        $results = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) < 10 || stripos($line, 'SALDO') !== false) continue;

            if (preg_match('/^(\d{2}\/\d{2})\s+(.*?)\s+([\d,]+\.\d{2})\s*(DB|CR)?/i', $line, $matches)) {
                $amount = (float) str_replace(',', '', $matches[3]);
                $type = (isset($matches[4]) && strtoupper($matches[4]) == 'DB') ? 'expense' : 'income';
                $desc = trim(preg_replace('/\d{4,}/', '', $matches[2]));
                
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $matches[1] . '/' . $year)->format('Y-m-d');
                } catch (\Exception $e) { $date = now()->format('Y-m-d'); }

                $results[] = [
                    'bank_detected' => 'BCA',
                    'date' => $date . ' ' . now()->format('H:i:s'),
                    'description' => $desc,
                    'amount' => $amount,
                    'type' => $type,
                    'category_guess' => $this->guessCategory($desc)
                ];
            }
        }
        return $results;
    }

    // --- HELPER ---
    private function guessCategory($desc)
    {
        $desc = strtoupper($desc);
        if (preg_match('/(MCD|KFC|STARBUCKS|KOPI|RESTO|GOFOOD|GRABFOOD|SATE|NASI|WARUNG|MIE)/', $desc)) return 'Makanan';
        if (preg_match('/(PLN|TOKEN|LISTRIK|PDAM|AIR|WIFI|INDIHOME|TELKOM|MYTELKOMSEL)/', $desc)) return 'Tagihan Rutin';
        if (preg_match('/(PULSA|TELKOMSEL|INDOSAT|XL|DATA|PACKET)/', $desc)) return 'Pulsa & Data';
        if (preg_match('/(INDOMARET|ALFAMART|SUPERINDO|HYPERMART|GROCERY|SHOPPING|JUMBO|TOKOPEDIA|SHOPEE|TIKTOK)/', $desc)) return 'Belanja Harian';
        if (preg_match('/(TRSF|TRANSFER|KIRIM|LLG|SKN|RTGS|SWITCHING|M-BCA|BI FAST|BRIMO|DANA|GOPAY|OVO)/', $desc)) return 'Transfer';
        if (preg_match('/(BUNGA|BAGI HASIL)/', $desc)) return 'Bunga Bank';
        if (preg_match('/(BIAYA ADM|ADMIN|MATERAI|KARTU|FEE|MONTHLY)/', $desc)) return 'Biaya Admin';
        if (preg_match('/(GAJI|SALARY|PAYROLL|THR)/', $desc)) return 'Gaji';
        if (preg_match('/(TARIK|ATM|CASH|WD|PENARIKAN)/', $desc)) return 'Tarik Tunai';
        if (preg_match('/(SPBU|PERTAMINA|SHELL)/', $desc)) return 'Transportasi';
        
        return 'Lainnya';
    }
}