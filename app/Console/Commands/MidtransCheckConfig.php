<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MidtransCheckConfig extends Command
{
    protected $signature = 'midtrans:check';
    protected $description = 'Cek konfigurasi Midtrans (env & koneksi ke API)';

    public function handle(): int
    {
        $this->info('Cek konfigurasi Midtrans');
        $this->newLine();

        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production');

        $ok = true;

        if (empty($serverKey) || $serverKey === '') {
            $this->error('MIDTRANS_SERVER_KEY kosong. Isi di .env dari Midtrans Dashboard → Settings → Access Keys.');
            $ok = false;
        } else {
            $prefix = substr($serverKey, 0, 12);
            $this->info('MIDTRANS_SERVER_KEY: ' . $prefix . '... (panjang ' . strlen($serverKey) . ' karakter)');
        }

        if (empty($clientKey) || $clientKey === '') {
            $this->error('MIDTRANS_CLIENT_KEY kosong. Isi di .env dari Midtrans Dashboard.');
            $ok = false;
        } else {
            $prefix = substr($clientKey, 0, 14);
            $this->info('MIDTRANS_CLIENT_KEY: ' . $prefix . '... (panjang ' . strlen($clientKey) . ' karakter)');
        }

        $this->info('MIDTRANS_IS_PRODUCTION: ' . ($isProduction ? 'true' : 'false'));

        if (!$ok) {
            $this->newLine();
            $this->warn('Isi .env lalu jalankan lagi: php artisan midtrans:check');
            return self::FAILURE;
        }

        // Opsional: cek koneksi ke API (tanpa order_id nyata, hanya validasi key)
        $this->newLine();
        $this->info('Memeriksa koneksi ke API Midtrans...');
        try {
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = $isProduction;
            // Panggil status dengan order_id dummy; Midtrans mengembalikan 404 tapi request ter-autentikasi
            \Midtrans\Transaction::status('PARKIR-0-' . time());
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            if ($code === 404 || str_contains($msg, '404') || str_contains(strtolower($msg), 'not found')) {
                $this->info('Koneksi API OK (key valid). Order dummy memang tidak ada.');
            } elseif ($code === 401 || str_contains($msg, '401') || str_contains(strtolower($msg), 'unauthorized')) {
                $this->error('Server Key ditolak. Pastikan MIDTRANS_SERVER_KEY dari Dashboard sesuai lingkungan (Sandbox/Production).');
                return self::FAILURE;
            } else {
                $this->warn('API: ' . $msg);
            }
        }

        $this->newLine();
        $this->info('Konfigurasi Midtrans siap.');
        $this->line('Langkah berikut: atur Notification URL di Dashboard → Settings → Configuration.');
        return self::SUCCESS;
    }
}
