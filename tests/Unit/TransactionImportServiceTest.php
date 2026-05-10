<?php

use App\Services\TransactionImportService;

test('it parses generic bank mutation rows with debit and credit columns', function () {
    $service = new TransactionImportService();

    $rows = [
        ['Tanggal', 'Keterangan', 'Debit', 'Kredit', 'Saldo'],
        ['2026-05-01', 'Transfer masuk client', '', '150.000,00', '1.150.000,00'],
        ['2026-05-02', 'Bayar listrik', '75.000,00', '', '1.075.000,00'],
    ];

    $transactions = $service->parseRows($rows, 'generic');

    expect($transactions)->toHaveCount(2)
        ->and($transactions[0]['type'])->toBe('income')
        ->and($transactions[0]['amount'])->toBe(150000.0)
        ->and($transactions[1]['type'])->toBe('expense')
        ->and($transactions[1]['amount'])->toBe(75000.0);
});

test('it can force bca pdf parsing from selected bank source', function () {
    $service = new TransactionImportService();

    $text = "PERIODE : MEI 2026\n01/05 TRSF E-BANKING 100,000.00 CR";

    $transactions = $service->parse($text, 'bca');

    expect($transactions)->not->toBeEmpty()
        ->and($transactions[0]['bank_detected'])->toBe('BCA');
});

test('it ignores bri statement footer noise and limits descriptions', function () {
    $service = new TransactionImportService();

    $text = '12/04/20 00:00:00 25 GQ 85838 940846001388 e-StatementBRImo 226001016563503 Sep 2024 Halaman 2 dari 2 Page 2 of 2 LAPORAN TRANSAKSI FINANSIAL STATEMENT OF FINANCIAL TRANSACTION 0.00 2,292,500.00 10,000,000.00 Created By BRIMO';

    $transactions = $service->parse($text, 'bri');

    expect($transactions)->toBeEmpty();
});

test('it limits generic imported descriptions to fit transaction column', function () {
    $service = new TransactionImportService();
    $longDescription = str_repeat('Deskripsi panjang ', 30);

    $transactions = $service->parseRows([
        ['Tanggal', 'Keterangan', 'Debit', 'Kredit'],
        ['2026-05-01', $longDescription, '', '100000'],
    ], 'generic');

    expect($transactions)->toHaveCount(1)
        ->and(strlen($transactions[0]['description']))->toBeLessThanOrEqual(250);
});
