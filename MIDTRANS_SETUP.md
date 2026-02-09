# Setup Midtrans

## 1. Install dependency

```bash
composer update
```

(Package `midtrans/midtrans-php` sudah ditambahkan di `composer.json`.)

## 2. Environment (.env)

Pastikan sudah diisi:

- `MIDTRANS_SERVER_KEY` – Server Key dari Midtrans Dashboard
- `MIDTRANS_CLIENT_KEY` – Client Key dari Midtrans Dashboard  
- `MIDTRANS_IS_PRODUCTION=false` – gunakan `true` untuk production

Jangan ada karakter tambahan (misalnya backtick) di akhir nilai.

## 3. Notification URL (wajib)

Agar status pembayaran otomatis terupdate, atur **Notification URL** di Midtrans:

1. Login [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. **Settings** → **Configuration** → **Notification URL**
3. Isi URL (POST):  
   `https://domain-anda.com/payment/midtrans/notification`

Contoh lokal (ngrok):  
`https://abc123.ngrok.io/payment/midtrans/notification`

Midtrans akan mengirim POST ke URL ini setiap ada perubahan status transaksi. Aplikasi memproses hanya status `capture` dan `settlement` untuk menandai transaksi sebagai lunas.

## 4. Alur pembayaran

1. Petugas pilih transaksi → **Proses Pembayaran** → pilih **Bayar dengan Midtrans**.
2. Halaman Snap terbuka (GoPay, VA, kartu, dll).
3. Pelanggan bayar di Snap.
4. Setelah bayar: redirect ke halaman sukses; status di database diupdate via notification callback.

## 5. Riwayat pembayaran

Pembayaran Midtrans tampil di **Riwayat Pembayaran** dengan metode `midtrans` dan kolom `order_id` / `transaction_id` terisi.
