# Glosarium Istilah Teknis — Proyek NESTON

Dokumen ini menjelaskan istilah-istilah teknis yang digunakan dalam dokumentasi rekayasa proyek NESTON.

---

## Daftar Isi

1. [Framework & Bahasa Pemrograman](#1-framework--bahasa-pemrograman)
2. [ORM & Database](#2-orm--database)
3. [Arsitektur & Pola Desain](#3-arsitektur--pola-desain)
4. [Keamanan & Akses](#4-keamanan--akses)
5. [Pembayaran & Integrasi API](#5-pembayaran--integrasi-api)
6. [Frontend](#6-frontend)
7. [Konsep Laravel Spesifik](#7-konsep-laravel-spesifik)
8. [Konsep Database](#8-konsep-database)

---

## 1. Framework & Bahasa Pemrograman

### Laravel
Laravel adalah framework PHP open-source yang digunakan untuk membangun aplikasi web. Laravel menyediakan berbagai fitur bawaan seperti routing, autentikasi, ORM (Eloquent), migrasi database, dan banyak lagi. Proyek NESTON menggunakan **Laravel 12**.

### PHP
PHP (PHP: Hypertext Preprocessor) adalah bahasa pemrograman server-side yang digunakan untuk membuat aplikasi web dinamis. Laravel dibangun di atas PHP. Proyek NESTON menggunakan **PHP 8.2+**.

### Artisan
Artisan adalah antarmuka command-line (CLI) bawaan Laravel. Digunakan untuk menjalankan perintah seperti membuat migration, menjalankan seeder, membuat controller, dan sebagainya. Contoh: `php artisan migrate`.

### Composer
Composer adalah package manager untuk PHP. Digunakan untuk menginstal dan mengelola dependency (pustaka) yang dibutuhkan proyek, termasuk Laravel itu sendiri dan SDK Midtrans.

### NPM (Node Package Manager)
NPM adalah package manager untuk JavaScript/Node.js. Digunakan untuk mengelola dependency di sisi frontend, seperti Tailwind CSS dan Vite.

### Vite
Vite adalah build tool modern untuk frontend. Digunakan untuk mengompilasi dan mem-bundle file CSS dan JavaScript agar siap digunakan di browser secara cepat dan efisien.

---

## 2. ORM & Database

### Eloquent
Eloquent adalah ORM (Object-Relational Mapper) bawaan Laravel. ORM memungkinkan developer berinteraksi dengan database menggunakan kode PHP (berbasis objek) tanpa perlu menulis query SQL secara manual.

Contoh penggunaan Eloquent:
```php
// Tanpa Eloquent (SQL mentah):
SELECT * FROM tb_kendaraan WHERE id_kendaraan = 1;

// Dengan Eloquent:
Kendaraan::find(1);
```

Setiap tabel database direpresentasikan oleh sebuah **Model** Eloquent. Contoh: tabel `tb_kendaraan` → Model `Kendaraan`.

### Migration
Migration adalah mekanisme Laravel untuk mendefinisikan dan mengelola struktur database menggunakan kode PHP. Setiap perubahan pada tabel (tambah kolom, ubah tipe data, buat tabel baru) dicatat dalam file migration.

Keuntungan migration:
- Perubahan database bisa dilacak via version control (Git).
- Bisa dijalankan ulang di lingkungan baru (`php artisan migrate`).
- Bisa di-rollback jika terjadi kesalahan (`php artisan migrate:rollback`).

### Seeder
Seeder adalah file PHP di Laravel yang digunakan untuk mengisi database dengan data awal (dummy/contoh). Berguna saat pertama kali setup proyek atau pengujian.

### Soft Deletes
Soft delete adalah teknik "menghapus" data tanpa benar-benar menghapusnya dari database. Saat data di-soft-delete, kolom `deleted_at` diisi dengan timestamp waktu penghapusan. Query normal secara otomatis mengabaikan data yang sudah di-soft-delete.

Ini berguna untuk keperluan audit (data masih ada), pemulihan data, dan compliance.

### Eager Loading
Eager loading adalah teknik untuk memuat relasi antar tabel secara sekaligus dalam satu query, bukan satu per satu. Ini mencegah masalah **N+1 Query** yang dapat memperlambat aplikasi.

Contoh:
```php
// Tanpa eager loading (N+1 problem — banyak query):
$areas = AreaParkir::all();
foreach ($areas as $area) {
    echo $area->transaksis; // Query baru untuk setiap area
}

// Dengan eager loading (1 query):
$areas = AreaParkir::with('transaksis')->get();
```

### Foreign Key
Foreign key adalah kolom yang menghubungkan satu tabel ke tabel lain berdasarkan nilai primary key. Digunakan untuk menjaga integritas relasi antar data. Contoh: kolom `id_kendaraan` di `tb_transaksi` mengacu ke `id_kendaraan` di `tb_kendaraan`.

### Accessor (Model Accessor)
Accessor adalah method khusus di Eloquent Model yang memungkinkan transformasi nilai atribut saat diakses. Contoh: `getBiayaTotalAttribute()` menghitung biaya secara otomatis jika nilainya belum tersimpan di database.

---

## 3. Arsitektur & Pola Desain

### RBAC (Role-Based Access Control)
RBAC adalah sistem kontrol akses di mana hak akses seorang user ditentukan oleh **role** (peran) yang dimilikinya, bukan oleh identitas user secara individual.

Di NESTON, terdapat 4 role:
- `admin`: Akses penuh ke semua fitur sistem.
- `petugas`: Akses operasional (check-in, check-out, pembayaran).
- `owner`: Akses ke laporan dan analitik.
- `user`: Akses default yang terbatas.

### Middleware
Middleware adalah lapisan pemrosesan yang berada di antara request HTTP dan response. Middleware berjalan sebelum request sampai ke controller. Digunakan untuk autentikasi, otorisasi, logging, dan sebagainya.

Di NESTON, `RoleMiddleware` memeriksa role user sebelum mengizinkan akses ke suatu halaman.

### Service Layer Pattern
Pola arsitektur di mana logika bisnis (business logic) dipisahkan ke dalam kelas khusus yang disebut **Service**. Controller hanya bertanggung jawab menerima request dan mengembalikan response. Ini membuat kode lebih terstruktur, mudah diuji, dan dapat digunakan kembali.

Contoh di NESTON: `PlateRecognizerService` menangani seluruh logika komunikasi dengan API Plate Recognizer, sementara `PlateRecognizerController` hanya menerima request dan memanggil service tersebut.

### Observer Pattern
Observer pattern adalah pola desain di mana sebuah objek (subject) secara otomatis memberi tahu objek lain (observer) saat terjadi perubahan state. Di Laravel, Observer digunakan untuk mendengarkan event model (created, updated, deleted).

Di NESTON, `TransaksiObserver` secara otomatis mencatat log aktivitas setiap kali transaksi dibuat atau diperbarui, tanpa perlu menulis kode logging secara manual di setiap controller.

### Repository Pattern
Pola desain yang memisahkan logika akses data dari logika bisnis. Di NESTON, pola ini diterapkan secara implisit melalui Eloquent Model — model berfungsi sebagai repository yang menyediakan metode untuk query database.

### Separation of Concerns (SoC)
Prinsip desain yang menyatakan bahwa setiap bagian kode harus memiliki tanggung jawab yang jelas dan terpisah. Di NESTON: Controller menangani HTTP, Service menangani logika bisnis, Model menangani akses data, dan Observer menangani side effects (logging).

### Idempotent
Operasi disebut idempotent jika dapat dijalankan berkali-kali dengan hasil yang sama, tanpa menyebabkan efek samping yang tidak diinginkan (seperti duplikasi data).

Di NESTON, fungsi `applyMidtransSuccess()` bersifat idempotent — meskipun dipanggil berkali-kali (misalnya webhook dikirim ulang oleh Midtrans), pembayaran hanya akan tercatat satu kali karena ada pengecekan status sebelum proses.

### Atomic Operation
Operasi disebut atomic jika semua langkah di dalamnya berhasil seluruhnya atau tidak ada yang berhasil (all-or-nothing). Di NESTON, proses check-in menggunakan `DB::transaction()` untuk memastikan: pembuatan kendaraan, pembuatan transaksi, dan update kapasitas area semuanya berhasil — atau semuanya dibatalkan jika ada yang gagal.

---

## 4. Keamanan & Akses

### Authentication (Autentikasi)
Proses memverifikasi **identitas** pengguna — memastikan bahwa user yang login memang siapa yang mereka klaim. Di NESTON menggunakan sistem login email + password bawaan Laravel.

### Authorization (Otorisasi)
Proses memverifikasi **hak akses** pengguna — memastikan bahwa user yang sudah login memiliki izin untuk melakukan tindakan tertentu. Di NESTON, otorisasi dilakukan melalui `RoleMiddleware`.

### CSRF Token (Cross-Site Request Forgery Token)
Token keamanan yang disertakan dalam setiap form di Laravel untuk mencegah serangan CSRF. Serangan CSRF terjadi ketika situs web jahat mencoba mengirim request ke aplikasi target atas nama pengguna yang sudah login. Laravel secara otomatis memvalidasi token ini di setiap request POST, PUT, atau DELETE.

### HTTP 403
Kode status HTTP yang berarti "Forbidden" — server memahami request, tetapi menolak untuk memenuhinya karena user tidak memiliki izin yang cukup. Di NESTON, kode ini dikembalikan saat user mencoba mengakses halaman yang membutuhkan role tertentu.

### Row Lock (`lockForUpdate`)
Mekanisme database untuk mengunci baris data selama transaksi berlangsung, mencegah proses lain membaca atau mengubah baris yang sama secara bersamaan. Di NESTON digunakan saat check-in untuk mencegah race condition pada kapasitas area, dan saat proses pembayaran untuk mencegah double-payment.

### Race Condition
Kondisi di mana dua atau lebih proses berjalan secara bersamaan dan hasilnya bergantung pada urutan eksekusi yang tidak dapat diprediksi. Contoh: dua petugas check-in kendaraan ke area yang kapasitasnya tersisa satu slot secara bersamaan — tanpa row lock, keduanya bisa berhasil padahal seharusnya salah satu ditolak.

### API Key
Kunci unik yang digunakan untuk mengotentikasi request ke layanan API eksternal. Di NESTON, API key Plate Recognizer dan Midtrans disimpan di file `.env` (environment variables) di server, tidak pernah dikirim ke frontend untuk menjaga keamanan.

---

## 5. Pembayaran & Integrasi API

### Midtrans
Midtrans adalah payment gateway Indonesia yang menyediakan berbagai metode pembayaran seperti GoPay, OVO, DANA, transfer bank, dan kartu kredit. Di NESTON digunakan sebagai satu-satunya metode pembayaran online setelah simplifikasi di Fase 7.

### Snap Token
Token sementara yang dihasilkan oleh Midtrans untuk membuka halaman pembayaran (Snap popup/modal) di frontend. Token ini di-generate di backend menggunakan `\Midtrans\Snap::getSnapToken()` dan dikirimkan ke browser untuk digunakan oleh JavaScript.

### Webhook
Mekanisme di mana server Midtrans secara otomatis mengirimkan notifikasi HTTP POST ke URL endpoint tertentu di aplikasi NESTON setiap kali terjadi perubahan status pembayaran. Ini memungkinkan NESTON mengetahui hasil pembayaran secara real-time tanpa harus terus-menerus menanyakan ke Midtrans (polling).

### Payment Gateway
Platform perantara yang memproses transaksi pembayaran antara pelanggan dan merchant. Payment gateway menghubungkan berbagai metode pembayaran (e-wallet, bank, kartu kredit) ke dalam satu antarmuka yang terintegrasi.

### Plate Recognizer API
Layanan cloud API yang menggunakan kecerdasan buatan (AI/ML) untuk mendeteksi dan membaca teks plat nomor kendaraan dari foto/gambar. Di NESTON digunakan untuk fitur scan plat otomatis saat proses check-in.

### Confidence Score
Nilai persentase yang menunjukkan seberapa yakin API Plate Recognizer terhadap hasil deteksinya. Di NESTON, hasil deteksi hanya diterima jika confidence score mencapai minimal **80%** (0.80). Di bawah nilai tersebut, hasil dianggap tidak valid.

### REST API / JSON API
REST (Representational State Transfer) adalah gaya arsitektur untuk membangun web service. Di NESTON, beberapa endpoint API mengembalikan data dalam format JSON (JavaScript Object Notation) untuk dikonsumsi oleh frontend secara asinkron (tanpa reload halaman).

### JsonResponse
Tipe respons di Laravel yang secara otomatis mengubah data PHP (array, objek) menjadi format JSON dan mengatur header `Content-Type: application/json`. Digunakan di semua API controller di NESTON.

---

## 6. Frontend

### Tailwind CSS
Framework CSS utility-first yang menyediakan class-class kecil dan spesifik untuk styling. Alih-alih menulis CSS kustom, developer menggabungkan class-class Tailwind langsung di HTML. NESTON menggunakan **Tailwind CSS 4.1.18**.

### Alpine.js
Framework JavaScript ringan untuk menambahkan interaktivitas ke halaman web langsung di dalam HTML. Alpine.js cocok untuk interaksi sederhana hingga menengah tanpa memerlukan framework besar seperti React atau Vue. Di NESTON digunakan untuk form check-in dual mode, autocomplete plat nomor, dan komponen interaktif lainnya.

### Blade (Template Engine)
Blade adalah template engine bawaan Laravel untuk membuat tampilan HTML. Blade memungkinkan penggunaan sintaks PHP di dalam file HTML dengan cara yang lebih bersih, serta mendukung komponen, layout, dan direktif khusus.

### getUserMedia
API JavaScript browser yang memungkinkan akses ke perangkat kamera dan mikrofon pengguna. Di NESTON digunakan oleh komponen plate-scanner untuk membuka kamera belakang perangkat mobile (`facingMode: 'environment'`).

### Debounce
Teknik untuk menunda eksekusi fungsi hingga selang waktu tertentu berlalu sejak terakhir kali fungsi tersebut dipanggil. Di NESTON, pengecekan plat nomor di form check-in menggunakan debounce 300ms — artinya API baru dipanggil setelah user berhenti mengetik selama 300 milidetik. Ini mengurangi jumlah request API yang tidak perlu.

### AJAX (Asynchronous JavaScript and XML)
Teknik untuk mengirim dan menerima data dari server di latar belakang tanpa harus me-reload seluruh halaman. Di NESTON digunakan untuk pengecekan plat nomor real-time dan pengiriman gambar ke API scan plat. Implementasi modern menggunakan `fetch()` API.

---

## 7. Konsep Laravel Spesifik

### Route
Route mendefinisikan bagaimana aplikasi merespons request HTTP ke URL tertentu. Di NESTON, semua route didefinisikan di file `routes/web.php`.

### Controller
Controller adalah kelas PHP yang mengelompokkan logika penanganan request terkait. Controller menerima request, memproses data (biasanya dengan memanggil Model atau Service), dan mengembalikan respons (view atau JSON).

### Model
Model adalah representasi dari sebuah tabel database dalam bentuk kelas PHP. Dengan Eloquent, Model menyediakan metode untuk membaca, membuat, mengubah, dan menghapus data di tabel yang bersangkutan.

### AppServiceProvider
File di Laravel yang berfungsi sebagai tempat mendaftarkan berbagai service dan binding pada saat aplikasi pertama kali dijalankan. Di NESTON, `AppServiceProvider` digunakan untuk mendaftarkan `TransaksiObserver`.

### Facade
Facade di Laravel menyediakan antarmuka statis (static interface) yang mudah digunakan untuk mengakses service di dalam service container. Contoh: `Auth::check()`, `DB::transaction()`, `Carbon::now()`.

### Carbon
Library PHP untuk memanipulasi tanggal dan waktu. Carbon adalah ekstensi dari class `DateTime` bawaan PHP dan sangat sering digunakan di Laravel. Di NESTON digunakan untuk menghitung durasi parkir, waktu bookmark, dan mencatat waktu aktivitas.

### `.env` (Environment Variables)
File konfigurasi khusus yang menyimpan nilai-nilai sensitif dan spesifik lingkungan (development, production) seperti koneksi database, API key, dan kredensial lainnya. File `.env` tidak boleh di-commit ke repository Git.

### `fillable` (Mass Assignment)
Property di Eloquent Model yang mendefinisikan kolom mana saja yang boleh diisi secara massal melalui `Model::create()` atau `$model->fill()`. Ini adalah mekanisme keamanan untuk mencegah mass assignment vulnerability.

### `isDirty()`
Method Eloquent yang mengembalikan `true` jika nilai atribut tertentu telah berubah sejak model terakhir dimuat dari database atau disimpan. Di NESTON, digunakan dalam Observer untuk mendeteksi apakah status transaksi berubah menjadi `keluar`.

---

## 8. Konsep Database

### Primary Key
Kolom (atau kombinasi kolom) yang secara unik mengidentifikasi setiap baris dalam sebuah tabel. Di NESTON, contohnya adalah `id_parkir` pada `tb_transaksi` dan `id_pembayaran` pada `tb_pembayaran`.

### VARCHAR
Tipe data kolom database untuk menyimpan teks dengan panjang variabel (hingga batas maksimum tertentu). Lebih fleksibel dari `CHAR` karena hanya menggunakan ruang penyimpanan sebesar teks yang disimpan.

### ENUM
Tipe data kolom database yang membatasi nilai yang dapat disimpan hanya pada daftar pilihan yang telah ditentukan. Contoh: `enum('masuk', 'keluar', 'bookmarked')`. Di NESTON, beberapa kolom enum diubah menjadi VARCHAR saat integrasi Midtrans untuk mendukung nilai-nilai tambahan yang dinamis.

### DECIMAL
Tipe data numerik dengan presisi yang dapat ditentukan, cocok untuk menyimpan nilai mata uang. Berbeda dengan FLOAT yang bisa menghasilkan error pembulatan, DECIMAL menyimpan nilai secara eksak.

### Cascade Delete (`onDelete('cascade')`)
Perilaku foreign key di mana penghapusan data di tabel induk secara otomatis menghapus data terkait di tabel anak. Contoh: menghapus user akan otomatis menghapus semua log aktivitas user tersebut.

### Set Null (`onDelete('set null')`)
Perilaku foreign key di mana penghapusan data di tabel induk menyebabkan nilai foreign key di tabel anak diubah menjadi `NULL`, bukan dihapus. Di NESTON digunakan agar transaksi tidak ikut terhapus saat record pembayaran dihapus.

### DB Transaction
Mekanisme database yang mengelompokkan beberapa operasi SQL menjadi satu unit kerja yang bersifat atomic. Jika salah satu operasi gagal, semua operasi dalam transaction akan dibatalkan (rollback), menjaga konsistensi data.

### Nullable
Kolom database yang diizinkan untuk menyimpan nilai `NULL` (tidak ada nilai). Di NESTON, banyak kolom bersifat nullable karena datanya baru tersedia di tahap tertentu — misalnya `waktu_keluar` yang diisi saat checkout, bukan saat check-in.

### Index
Struktur data tambahan pada database yang mempercepat pencarian dan query pada kolom tertentu. Kolom yang sering digunakan dalam filter atau join sebaiknya diberi index.
