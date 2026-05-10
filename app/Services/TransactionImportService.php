<?php

namespace App\Services;

use Carbon\Carbon;

class TransactionImportService
{
    public function parse(string $text, ?string $bankSource = null)
    {
        // 1. BERSIHKAN TEKS (Hapus karakter yang mengganggu)
        // Hapus tanda kutip ("), Tab (\t), Return (\r)
        $text = str_replace(['"', "\t", "\r"], " ", $text);

        $bankSource = strtolower((string) $bankSource);

        if ($bankSource === 'bca') {
            return $this->parseBCA($text);
        }

        if ($bankSource === 'mandiri') {
            return $this->parseMandiri($text);
        }

        if ($bankSource === 'bri') {
            return $this->parseBRI($text);
        }
        
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

    public function parseRows(array $rows, ?string $bankSource = null): array
    {
        $results = [];
        $headers = [];

        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $row = array_values($row);

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            if ($headers === [] && $this->looksLikeHeaderRow($row)) {
                $headers = $this->normalizeHeaders($row);
                continue;
            }

            if ($headers !== []) {
                $assoc = $this->mapRowToHeaders($headers, $row);
                $parsed = $this->parseAssociativeRow($assoc, $bankSource);
            } else {
                $parsed = $this->parsePositionalRow($row, $bankSource);
            }

            if ($parsed !== null) {
                $results[] = $parsed;
            }
        }

        return $results;
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
            $upperBlock = strtoupper($block);

            if ($this->isStatementNoise($upperBlock)) {
                continue;
            }

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
                if ($debet > 0 && $kredit == 0) {
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
                    $desc = $this->cleanDescription($desc);
                    
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
                        'description' => $this->truncateDescription($desc),
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
                'description' => $this->truncateDescription($desc),
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
                    'description' => $this->truncateDescription($desc),
                    'amount' => $amount,
                    'type' => $type,
                    'category_guess' => $this->guessCategory($desc)
                ];
            }
        }
        return $results;
    }

    // --- HELPER ---
    private function parseAssociativeRow(array $row, ?string $bankSource = null): ?array
    {
        $dateRaw = $this->valueFrom($row, ['tanggal', 'date', 'transaction_date', 'tgl', 'posting_date', 'waktu']);
        $description = $this->valueFrom($row, ['deskripsi', 'description', 'keterangan', 'uraian', 'remark', 'remarks', 'berita']);
        $debitRaw = $this->valueFrom($row, ['debit', 'debet', 'withdrawal', 'keluar', 'pengeluaran']);
        $creditRaw = $this->valueFrom($row, ['credit', 'kredit', 'deposit', 'masuk', 'pemasukan']);
        $amountRaw = $this->valueFrom($row, ['amount', 'nominal', 'jumlah', 'mutasi', 'transaction_amount']);
        $typeRaw = strtolower($this->valueFrom($row, ['type', 'tipe', 'jenis', 'dk', 'd_k']));

        $debit = $this->parseAmount($debitRaw);
        $credit = $this->parseAmount($creditRaw);
        $amount = $this->parseAmount($amountRaw);
        $type = null;

        if ($credit > 0) {
            $amount = $credit;
            $type = 'income';
        } elseif ($debit > 0) {
            $amount = $debit;
            $type = 'expense';
        } elseif ($amount > 0) {
            $type = $this->typeFromText($typeRaw . ' ' . $amountRaw);
        }

        return $this->makeImportedRow($dateRaw, $description, $amount, $type, $bankSource);
    }

    private function parsePositionalRow(array $row, ?string $bankSource = null): ?array
    {
        $dateRaw = null;
        $descriptionParts = [];
        $moneyValues = [];

        foreach ($row as $value) {
            $value = trim((string) $value);

            if ($value === '') {
                continue;
            }

            if ($dateRaw === null && $this->parseDate($value) !== null) {
                $dateRaw = $value;
                continue;
            }

            $amount = $this->parseAmount($value);
            if ($amount > 0) {
                $moneyValues[] = ['raw' => $value, 'amount' => $amount];
                continue;
            }

            $descriptionParts[] = $value;
        }

        if ($dateRaw === null || $moneyValues === []) {
            return null;
        }

        $selected = $moneyValues[0];
        $type = $this->typeFromText($selected['raw'] . ' ' . implode(' ', $row));

        return $this->makeImportedRow(
            $dateRaw,
            implode(' ', $descriptionParts),
            $selected['amount'],
            $type,
            $bankSource
        );
    }

    private function makeImportedRow($dateRaw, $description, float $amount, ?string $type, ?string $bankSource = null): ?array
    {
        $date = $this->parseDate((string) $dateRaw);
        $description = trim(preg_replace('/\s+/', ' ', (string) $description));
        $description = $this->cleanDescription($description);

        if ($date === null || $amount <= 0 || ! in_array($type, ['income', 'expense'], true)) {
            return null;
        }

        return [
            'bank_detected' => $this->bankLabel($bankSource),
            'date' => $date,
            'description' => $this->truncateDescription($description !== '' ? $description : 'Mutasi bank'),
            'amount' => $amount,
            'type' => $type,
            'category_guess' => $this->guessCategory($description)
        ];
    }

    private function parseDate(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d',
            'd/m/Y H:i:s',
            'd/m/Y',
            'd-m-Y H:i:s',
            'd-m-Y',
            'd/m/y H:i:s',
            'd/m/y',
            'd M Y H:i:s',
            'd M Y',
            'd F Y',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                //
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseAmount($value): float
    {
        $value = trim((string) $value);

        if ($value === '' || preg_match('/[A-Za-z]{3,}/', $value)) {
            return 0.0;
        }

        $negative = str_contains($value, '-') || preg_match('/^\(.*\)$/', $value);
        $value = preg_replace('/[^\d,.\-]/', '', $value);
        $value = trim($value, '-');

        if ($value === '') {
            return 0.0;
        }

        $lastComma = strrpos($value, ',');
        $lastDot = strrpos($value, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                $value = str_replace(',', '', $value);
            }
        } elseif ($lastComma !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace(',', '', $value);
        }

        $amount = (float) $value;

        return $negative ? abs($amount) : $amount;
    }

    private function typeFromText(string $value): string
    {
        $value = strtoupper($value);

        if (preg_match('/(^|\s|\+)(CR|CREDIT|KREDIT|MASUK|INCOME|IN|SETOR|\+)/', $value)) {
            return 'income';
        }

        return 'expense';
    }

    private function looksLikeHeaderRow(array $row): bool
    {
        $text = implode(' ', array_map(fn ($value) => strtolower((string) $value), $row));

        return preg_match('/tanggal|date|deskripsi|description|keterangan|debit|credit|kredit|nominal|amount/', $text) === 1;
    }

    private function normalizeHeaders(array $row): array
    {
        return array_map(function ($value) {
            $value = strtolower(trim((string) $value));
            $value = preg_replace('/[^a-z0-9]+/', '_', $value);
            return trim($value, '_');
        }, $row);
    }

    private function mapRowToHeaders(array $headers, array $row): array
    {
        $mapped = [];

        foreach ($headers as $index => $header) {
            if ($header !== '') {
                $mapped[$header] = $row[$index] ?? null;
            }
        }

        return $mapped;
    }

    private function valueFrom(array $row, array $keys): string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }

        return '';
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function cleanDescription(string $description): string
    {
        $patterns = [
            '/\bHALAMAN\s+\d+\s+DARI\s+\d+\b.*$/iu',
            '/\bPAGE\s+\d+\s+OF\s+\d+\b.*$/iu',
            '/\bLAPORAN\s+TRANSAKSI\s+FINANSIAL\b.*$/iu',
            '/\bSTATEMENT\s+OF\s+FINANCIAL\s+TRANSACTION\b.*$/iu',
            '/\bTERBILANG\s*\/\s*IN\s+WORDS\b.*$/iu',
            '/\bBIAYA\s+MATERAI\s+TELAH\s+DIBAYAR\b.*$/iu',
            '/\bREVENUE\s+STAMP\s+PAID\b.*$/iu',
            '/\bAPABILA\s+TERDAPAT\s+PERBEDAAN\b.*$/iu',
            '/\bIN\s+THE\s+CASE\s+OF\s+ANY\s+DIFFERENCES\b.*$/iu',
            '/\bSALINAN\s+REKENING\s+KORAN\b.*$/iu',
            '/\bTHE\s+COPY\s+OF\s+THIS\s+STATEMENT\b.*$/iu',
            '/\bCREATED\s+BY\s+BRIMO\b.*$/iu',
        ];

        foreach ($patterns as $pattern) {
            $description = preg_replace($pattern, '', $description);
        }

        return trim(preg_replace('/\s+/', ' ', $description));
    }

    private function truncateDescription(string $description): string
    {
        $description = trim(preg_replace('/\s+/', ' ', $description));

        if ($description === '') {
            return 'Mutasi bank';
        }

        return mb_substr($description, 0, 250);
    }

    private function isStatementNoise(string $upperBlock): bool
    {
        return str_contains($upperBlock, 'LAPORAN TRANSAKSI FINANSIAL')
            || str_contains($upperBlock, 'STATEMENT OF FINANCIAL TRANSACTION')
            || str_contains($upperBlock, 'TERBILANG / IN WORDS')
            || str_contains($upperBlock, 'REVENUE STAMP PAID')
            || str_contains($upperBlock, 'SALINAN REKENING KORAN')
            || str_contains($upperBlock, 'CREATED BY BRIMO');
    }

    private function bankLabel(?string $bankSource): string
    {
        return match (strtolower((string) $bankSource)) {
            'bca' => 'BCA',
            'mandiri' => 'Mandiri',
            'bri' => 'BRI',
            default => 'File Mutasi',
        };
    }

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
