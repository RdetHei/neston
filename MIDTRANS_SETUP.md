# Setup Midtrans

## Checklist lengkap (lakukan berurutan)

| # | Langkah | Status |
|---|---------|--------|
| 1 | Isi `.env` dengan Server Key & Client Key dari [Midtrans Dashboard](https://dashboard.midtrans.com/) → Settings → Access Keys | ☐ |
| 2 | Jalankan `php artisan midtrans:check` untuk cek konfigurasi | ☐ |
| 3 | Atur **Notification URL** di Dashboard → Settings → Configuration (lihat bagian 3 di bawah) | ☐ |
| 4 | Jalankan `php artisan migrate` (jika belum) | ☐ |
| 5 | Uji alur: Login → Proses Pembayaran → Bayar dengan Midtrans → Snap → bayar (lihat bagian 9) | ☐ |

---

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
2. Buka **Settings** (ikon gerigi) → **Configuration**
3. Di bagian **Notification URL**, isi:
   - **Production:** `https://domain-anda.com/payment/midtrans/notification`
   - **Lokal (uji dengan ngrok):** jalankan `ngrok http 80` (atau port aplikasi), lalu isi URL yang diberikan + path, contoh:  
     `https://abc123.ngrok.io/payment/midtrans/notification`
4. Simpan. Midtrans mengirim POST ke URL ini setiap ada perubahan status transaksi. Aplikasi hanya memproses status `capture` dan `settlement` untuk menandai transaksi lunas.

## 4. Alur pembayaran

1. Petugas pilih transaksi → **Proses Pembayaran** → pilih **Bayar dengan Midtrans**.
2. Halaman Snap terbuka (GoPay, VA, kartu, dll).
3. Pelanggan bayar di Snap.
4. Setelah bayar: redirect ke halaman sukses; status di database diupdate via notification callback.

## 5. Riwayat pembayaran

Pembayaran Midtrans tampil di **Riwayat Pembayaran** dengan metode `midtrans` dan kolom `order_id` / `transaction_id` terisi.

## 6. Keamanan notifikasi

Handler notifikasi (`/payment/midtrans/notification`) tidak hanya membaca body POST. Aplikasi memverifikasi dengan memanggil **Midtrans API** (`Transaction::status(order_id)`) dan memakai data dari API untuk update database, sehingga notifikasi palsu tidak akan mengubah status pembayaran.

## 7. Format kunci di .env

- **Sandbox:** Server Key biasanya berformat `SB-Mid-server-xxxx...`, Client Key `SB-Mid-client-xxxx...`
- **Production:** Format `Mid-server-...` dan `Mid-client-...`
- Ambil dari [Midtrans Dashboard](https://dashboard.midtrans.com/) → **Settings** → **Access Keys**
- Pastikan tidak ada spasi/backtick di akhir nilai.

## 8. Cek konfigurasi (sebelum uji)

Jalankan perintah berikut untuk memastikan env terisi dan Server Key valid:

```bash
php artisan midtrans:check
```

Jika ada yang kosong atau key ditolak, perbaiki `.env` lalu jalankan lagi.

## 9. Langkah uji coba

1. Pastikan migration sudah dijalankan: `php artisan migrate`, dan `php artisan midtrans:check` sukses.
2. Login sebagai **petugas** atau **admin** → Transaksi → pilih transaksi yang status keluar & belum bayar.
3. Klik **Proses Pembayaran** → **Bayar dengan Midtrans**.
4. Di halaman Snap, pilih metode (contoh: GoPay sandbox, atau Virtual Account).
5. Selesaikan pembayaran di sandbox (ikuti petunjuk di dashboard Midtrans untuk kartu/VA test).
6. Setelah bayar: redirect ke halaman sukses; status transaksi berubah via notifikasi.
7. Cek **Riwayat Pembayaran**: harus ada record dengan metode `midtrans` dan `order_id` terisi.

---

**Ringkasan:** Isi `.env` → jalankan `php artisan midtrans:check` → atur Notification URL di Dashboard → uji bayar via Midtrans di aplikasi.
