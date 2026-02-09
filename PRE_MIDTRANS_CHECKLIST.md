# Checklist Sebelum Integrasi Midtrans

Dokumen ini merangkum hal yang sudah dicek, yang sudah diperbaiki, dan yang bisa dikembangkan sebelum lanjut ke integrasi Midtrans.

---

## Sudah diperbaiki (dalam sesi ini)

1. **Tombol Edit di halaman Detail Transaksi (show)**  
   Tombol "Edit" sekarang hanya tampil untuk **admin**. Petugas hanya melihat "Cetak Struk" (jika admin) dan tidak lagi mengklik Edit yang berujung 403.

2. **Redirect setelah bayar via QR (customer/guest)**  
   Saat pengunjung bayar lewat link QR (signed URL), sebelumnya diarahkan ke `payment.success` yang butuh login petugas. Sekarang diarahkan ke halaman publik **Thank You** (`/payment/{id}/thank-you`) sehingga customer bisa melihat konfirmasi "Pembayaran Berhasil" tanpa login.

---

## Sudah berjalan dengan baik

- **Role & middleware**: Admin, Petugas, Owner sesuai SPK; route dilindungi `role:admin`, `role:petugas`, `role:owner`.
- **Alur transaksi**: Check-in → Parkir aktif → Check-out → Pembayaran (manual / QR) → Success; ada transaksi DB + lock untuk hindari double payment.
- **Log aktivitas**: Login/Logout dan event transaksi (create/update/delete) tercatat di `tb_log_aktivitas` via Observer & LoginController.
- **Laporan**: Rekap transaksi & pembayaran (filter tanggal, area, export CSV) untuk Owner.
- **Cetak struk**: Hanya admin; tombol/link cetak disembunyikan untuk petugas.
- **Kendaraan**: `id_user`, `warna`, `pemilik` nullable; kendaraan bisa dipakai untuk check-in tanpa wajib punya user.

---

## Implementasi checklist (rekomendasi yang sudah dikerjakan)

- **Registrasi publik**: Route `/register` (GET/POST) sekarang redirect ke login dengan pesan "Registrasi ditutup. Hubungi administrator untuk membuat akun." User baru hanya dibuat lewat Kelola User (admin).
- **Rate limiting callback Midtrans**: Route `POST /payment/midtrans/notification` ditambah dengan `throttle:60,1`. Handler placeholder `PaymentController::midtransNotification` siap untuk verifikasi signature dan logika idempotensi.
- **Tabel `tb_pembayaran`**: Migration menambah kolom `order_id`, `transaction_id`, `payment_type` (nullable). Kolom `metode` dan `status` di MySQL diubah ke VARCHAR agar bisa nilai Midtrans (manual, qr_scan, midtrans, dll).
- **Idempotensi**: Di `midtransNotification` disiapkan komentar/TODO untuk cek `status_pembayaran` sudah berhasil sebelum update.
- **Pembayaran manual**: Halaman `payment/manual-confirm` memakai `components.form-card`, tema diseragamkan dengan form lain (rounded-xl, focus ring, dll).
- **Peringatan nominal vs biaya_total**: Di halaman manual-confirm, peringatan (warning) tampil jika nominal &lt; biaya total: "Nominal lebih rendah dari total biaya. Pastikan ini disengaja (diskon/koreksi)."
- **Peta parkir**: Route `/parking-map` dan API `parking-slots` (get/bookmark/unbookmark) sekarang dilindungi `role:admin,petugas` saja.
- **Laporan**: Summary (total_nominal, count, avg, total_biaya, dll) dihitung dari clone query sebelum `paginate()` agar filter konsisten. Export CSV: variabel `$filename` di `exportTransaksiCSV` diperbaiki; UTF-8 BOM ditambah agar Excel membuka CSV dengan benar.

---

## Rekomendasi sebelum / saat integrasi Midtrans

### 1. Lingkungan & konfigurasi

- **`.env` / `.env.example`**  
  Tambah placeholder untuk Midtrans (nanti diisi saat integrasi):
  - `MIDTRANS_SERVER_KEY=`
  - `MIDTRANS_CLIENT_KEY=`
  - `MIDTRANS_IS_PRODUCTION=false`

### 2. Keamanan & auth

- ~~**Registrasi publik**~~ (sudah: register ditutup, redirect ke login.)
- ~~**Rate limiting**~~ (sudah: callback Midtrans pakai throttle.)

### 3. Pembayaran (persiapan Midtrans)

- ~~**Tabel `tb_pembayaran`**~~ (sudah: kolom + tipe untuk Midtrans.)
- **Status pembayaran**  
  Saat integrasi: simpan status dari Midtrans (atau map ke status internal); di callback update `tb_pembayaran` dan `status_pembayaran` di transaksi.
- ~~**Idempotensi**~~ (sudah: placeholder di handler; implementasi penuh saat pakai Midtrans.)

### 4. UX & edge case

- ~~**Pembayaran manual**~~ (sudah: tema form diseragamkan.)
- ~~**Validasi nominal vs biaya_total**~~ (sudah: peringatan di view.)
- ~~**Peta parkir & booking**~~ (sudah: akses admin, petugas saja.)

### 5. Laporan & report

- ~~**ReportController**~~ (sudah: summary dari clone query sebelum paginate.)
- ~~**Export CSV**~~ (sudah: UTF-8 BOM, filename diperbaiki.)

### 6. Testing

- Uji alur lengkap per role:
  - **Petugas**: Check-in → Parkir aktif → Check-out → Pilih transaksi → Bayar manual / QR → Lihat riwayat; tidak bisa akses Edit, Hapus, Cetak struk, Master data.
  - **Admin**: Semua itu + Edit/Hapus transaksi, Cetak struk, CRUD User/Tarif/Area/Kendaraan, Log aktivitas.
  - **Owner**: Hanya dashboard + Rekap transaksi & pembayaran + export CSV.
- Uji pembayaran QR dari perangkat lain (atau incognito) tanpa login → harus sampai ke halaman Thank You, tidak redirect ke login.

---

## Ringkasan

- **Harus:** Tidak ada blocker kritikal; aplikasi siap dipersiapkan untuk Midtrans.
- **Sebaiknya:** Tambah env Midtrans, rencanakan kolom/status pembayaran untuk Midtrans, pastikan callback idempotent dan aman.
- **Opsional:** Lock/disable register, standarkan tema halaman manual confirm, validasi nominal, review akses parking-map.

Setelah checklist ini dijalankan (terutama konfigurasi dan desain status pembayaran), integrasi Midtrans bisa dimulai (snap/redirect, callback, update status).
