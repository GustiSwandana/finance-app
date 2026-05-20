# Bot WhatsApp Transaksi

Bot ini membaca pesan WhatsApp yang diawali `trx`, mengirimnya ke webhook Laravel, lalu membalas hasil proses transaksi.

## Menjalankan Bot

Pastikan `.env` sudah berisi:

```env
WHATSAPP_TRANSACTION_WEBHOOK_TOKEN=token-yang-sama-dengan-webhook
WHATSAPP_BOT_WEBHOOK_URL=http://finance-app.test/webhooks/whatsapp/transactions
WHATSAPP_BOT_USER_EMAIL=email-user-aplikasi
WHATSAPP_BOT_ALLOWED_NUMBERS=
```

Jalankan:

```powershell
npm run wa:bot
```

Saat QR muncul di terminal, scan dari WhatsApp:

```text
WhatsApp > Perangkat tertaut > Tautkan perangkat
```

## Format Pesan

```text
trx masuk 50000 BCA Gaji Bonus proyek
trx keluar 25000 BCA Makanan Makan siang
trx edit 539 nominal 75000 catatan Direvisi dari WhatsApp
trx bantuan
```

## Membatasi Nomor Pengirim

Isi `WHATSAPP_BOT_ALLOWED_NUMBERS` dengan nomor yang boleh mengirim perintah, dipisah koma.

```env
WHATSAPP_BOT_ALLOWED_NUMBERS=6281234567890,6289876543210
```

Jika kosong, semua nomor yang mengirim pesan ke bot bisa menjalankan perintah `trx`.
