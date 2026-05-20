const axios = require('axios');
const dotenv = require('dotenv');
const qrcode = require('qrcode-terminal');
const { Client, LocalAuth } = require('whatsapp-web.js');

dotenv.config();

const financeWebhookUrl = process.env.WHATSAPP_BOT_WEBHOOK_URL || 'http://finance-app.test/webhooks/whatsapp/transactions';
const webhookToken = process.env.WHATSAPP_TRANSACTION_WEBHOOK_TOKEN;
const userEmail = process.env.WHATSAPP_BOT_USER_EMAIL;
const allowedNumbers = (process.env.WHATSAPP_BOT_ALLOWED_NUMBERS || '')
    .split(',')
    .map((number) => number.trim().replace(/\D/g, ''))
    .filter(Boolean);

if (!webhookToken || !userEmail) {
    console.error('WHATSAPP_TRANSACTION_WEBHOOK_TOKEN dan WHATSAPP_BOT_USER_EMAIL wajib diisi di .env.');
    process.exit(1);
}

const client = new Client({
    authStrategy: new LocalAuth({ clientId: 'finance-app' }),
    puppeteer: {
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    },
});

client.on('qr', (qr) => {
    console.log('Scan QR ini dari WhatsApp > Perangkat tertaut > Tautkan perangkat:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('Bot WhatsApp finance-app siap.');
    console.log(`Webhook: ${financeWebhookUrl}`);
});

client.on('auth_failure', (message) => {
    console.error('Autentikasi WhatsApp gagal:', message);
});

client.on('message', async (message) => {
    const text = (message.body || '').trim();
    const sender = normalizeSender(message.from);

    if (message.fromMe || !text.toLowerCase().startsWith('trx')) {
        return;
    }

    if (allowedNumbers.length > 0 && !allowedNumbers.includes(sender)) {
        await message.reply('Nomor ini tidak diizinkan mengupdate transaksi.');
        return;
    }

    try {
        const response = await axios.post(financeWebhookUrl, {
            token: webhookToken,
            user_email: userEmail,
            message: text,
        }, {
            timeout: 15000,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        await message.reply(response.data.reply || 'Transaksi berhasil diproses.');
    } catch (error) {
        const reply = error.response?.data?.reply
            || error.response?.data?.message
            || error.message
            || 'Gagal memproses transaksi.';

        await message.reply(reply);
    }
});

client.initialize();

function normalizeSender(sender) {
    return String(sender).split('@')[0].replace(/\D/g, '');
}
