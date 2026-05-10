<?php

use App\Services\ReceiptScanner;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

test('receipt scanner uses configured gemini endpoint and parses json response', function () {
    Config::set('gemini.api_key', 'test-key');
    Config::set('gemini.base_url', 'https://example.test/v1beta');
    Config::set('gemini.request_timeout', 15);

    Http::fake([
        'https://example.test/v1beta/models/gemini-2.0-flash:generateContent?key=test-key' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => '{"merchant":"Warung","date":"09-05-2026","amount":15000,"category":"Makanan"}',
                            ],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $tempFile = tempnam(sys_get_temp_dir(), 'receipt');
    file_put_contents($tempFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXk0AAAAASUVORK5CYII='));

    $result = (new ReceiptScanner())->scan($tempFile);

    expect($result)->toBe([
        'merchant' => 'Warung',
        'date' => '09-05-2026',
        'amount' => 15000,
        'category' => 'Makanan',
    ]);

    Http::assertSentCount(1);

    @unlink($tempFile);
});
