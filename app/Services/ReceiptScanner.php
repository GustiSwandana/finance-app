<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReceiptScanner
{
    public function scan(string $imagePath)
    {
        $apiKey = trim(env('GEMINI_API_KEY'));

        if (!$apiKey) {
            Log::error("ReceiptScanner: API Key kosong. Cek .env");
            return null;
        }

        // MODEL TERBARU & PALING STABIL (2025)
        $model = 'gemini-2.0-flash';

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        if (!file_exists($imagePath)) {
            Log::error("ReceiptScanner: File tidak ditemukan: {$imagePath}");
            return null;
        }

        // Encode gambar
        $mime = mime_content_type($imagePath);
        $base64 = base64_encode(file_get_contents($imagePath));

        $prompt = "
Anda adalah mesin OCR JSON untuk aplikasi keuangan.
Tugas: Baca struk belanja ini dan kembalikan JSON murni tanpa markdown, tanpa ```.

Format Output:
{
   \"merchant\": \"Nama Toko\",
   \"date\": \"DD-MM-YYYY\", 
   \"amount\": 50000,
   \"category\": \"Makanan|Transportasi|Belanja|Tagihan|Lainnya\"
}

Jika tanggal tidak terbaca, gunakan tanggal hari ini: " . Carbon::now()->toDateString() . ".
Pastikan JSON valid dan tidak berisi teks lain.
        ";

        try {

            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $prompt],
                                [
                                    "inline_data" => [
                                        "mime_type" => $mime,
                                        "data" => $base64
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);

            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body());
                return null;
            }

            $data = $response->json();
            $raw = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Ambil blok JSON
            if (!preg_match('/\{[\s\S]*\}/', $raw, $match)) {
                Log::error("ReceiptScanner: AI tidak mengembalikan JSON. Output: {$raw}");
                return null;
            }

            $json = json_decode($match[0], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("ReceiptScanner: JSON invalid. Error: " . json_last_error_msg());
                return null;
            }

            return $json;
        } catch (\Throwable $e) {
            Log::error("ReceiptScanner Exception: " . $e->getMessage());
            return null;
        }
    }
}
