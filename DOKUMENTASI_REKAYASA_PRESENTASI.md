# Dokumentasi Rekayasa Proyek NESTON
## Periode: 15 Januari 2026 – 13 Februari 2026

Dokumentasi ini disusun untuk keperluan presentasi dan memuat ringkasan perkembangan teknis proyek sistem parkir NESTON, dilengkapi penjelasan detail kode untuk setiap fitur.

---

## Daftar Isi
1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Timeline Pengembangan](#2-timeline-pengembangan)
3. [Fase 1: Fondasi Sistem (14–15 Januari)](#3-fase-1-fondasi-sistem-14-15-januari)
4. [Fase 2: Sistem Pembayaran (18–19 Januari)](#4-fase-2-sistem-pembayaran-18-19-januari)
5. [Fase 3: Peningkatan Arsitektur (22 Januari)](#5-fase-3-peningkatan-arsitektur-22-januari)
6. [Fase 4: Integrasi Midtrans & Fitur Lanjutan (29 Januari – 11 Februari)](#6-fase-4-integrasi-midtrans--fitur-lanjutan-29-januari--11-februari)
7. [Fase 5: Plate Recognizer & Peta Parkir (Februari 2026)](#7-fase-5-plate-recognizer--peta-parkir-februari-2026)
8. [Penjelasan Kode Utama](#8-penjelasan-kode-utama)

---

## 1. Ringkasan Proyek

**NESTON** adalah sistem manajemen parkir berbasis web yang dibangun dengan Laravel 12. Fitur utama meliputi:

- **Check-in/Check-out** kendaraan dengan manajemen kapasitas area parkir
- **Pembayaran** manual, QR scan, dan online via Midtrans
- **Role-Based Access Control** (Admin, Petugas, Owner, User)
- **Scan plat nomor** otomatis dengan Plate Recognizer API
- **Peta parkir** dengan fitur bookmark slot
- **Laporan** transaksi dan pembayaran
- **Log aktivitas** untuk audit

---

## 2. Timeline Pengembangan

| Tanggal | Fase | Fitur/Perubahan |
|---------|------|-----------------|
| 14 Jan 2026 | Fondasi | Tabel User, Kendaraan, Area, Tarif, Transaksi, Log |
| 15 Jan 2026 | RBAC | Kolom `role` pada User |
| 18 Jan 2026 | Pembayaran | Tabel Pembayaran, relasi ke Transaksi |
| 19 Jan 2026 | Refactor | Cleanup tabel pembayaran |
| 22 Jan 2026 | Arsitektur | Soft Deletes, kolom Catatan |
| 29 Jan 2026 | Midtrans & UX | Kolom Midtrans, Bookmark slot, Kendaraan nullable |
| 11 Feb 2026 | Midtrans | `midtrans_order_id` pada Transaksi |
| Feb 2026 | Integrasi | Plate Recognizer, Peta Parkir |

---

## 3. Fase 1: Fondasi Sistem (14–15 Januari)

### 3.1 Struktur Database Awal

**File:** `database/migrations/2026_01_14_011328_create_tb_user_table.php`

```php
Schema::create('tb_user', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

**Penjelasan untuk presentasi:**
- Tabel `tb_user` menyimpan data pengguna sistem.
- `email` bersifat `unique` untuk mencegah duplikasi akun.
- `rememberToken` dipakai Laravel untuk fitur “Ingat Saya”.
- `timestamps` mencatat `created_at` dan `updated_at` otomatis.

---

### 3.2 Role-Based Access Control (RBAC)

**File:** `database/migrations/2026_01_15_000001_add_role_to_tb_user.php`

```php
Schema::table('tb_user', function (Blueprint $table) {
    $table->string('role')->default('user')->after('password');
});
```

**Penjelasan untuk presentasi:**
- Kolom `role` menjadi dasar RBAC.
- Nilai default `'user'` untuk pengguna baru.
- Role yang digunakan: `admin`, `petugas`, `owner`, `user`.

**Implementasi Middleware:**

**File:** `app/Http/Middleware/RoleMiddleware.php`

```php
public function handle(Request $request, Closure $next, string ...$roles)
{
    if (! Auth::check()) {
        return redirect()->route('login.create');
    }

    $user = Auth::user();
    $allowedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);
    $userRole = strtolower(trim($user->role ?? ''));

    if (!in_array($userRole, $allowedRoles)) {
        abort(403, 'Unauthorized - Insufficient permissions');
    }

    return $next($request);
}
```

**Penjelasan untuk presentasi:**
- Middleware memastikan user sudah login.
- Mengecek apakah `role` user ada dalam daftar role yang diizinkan.
- Jika tidak, mengembalikan HTTP 403.
- Penggunaan: `->middleware(['role:admin,petugas'])`.

---

## 4. Fase 2: Sistem Pembayaran (18–19 Januari)

### 4.1 Tabel Pembayaran

**File:** `database/migrations/2026_01_18_123704_create_tb_pembayaran_table.php`

```php
Schema::create('tb_pembayaran', function (Blueprint $table) {
    $table->id('id_pembayaran');
    $table->unsignedBigInteger('id_parkir');
    $table->decimal('nominal', 10, 0);
    $table->enum('metode', ['manual', 'qr_scan'])->default('manual');
    $table->enum('status', ['pending', 'berhasil', 'gagal'])->default('pending');
    $table->text('keterangan')->nullable();
    $table->unsignedBigInteger('id_user')->nullable();
    $table->dateTime('waktu_pembayaran')->nullable();
    $table->timestamps();

    $table->foreign('id_parkir')->references('id_parkir')->on('tb_transaksi')->onDelete('cascade');
    $table->foreign('id_user')->references('id')->on('tb_user')->onDelete('set null');
});
```

**Penjelasan untuk presentasi:**
- `id_parkir` menghubungkan pembayaran ke transaksi parkir.
- `nominal` menyimpan jumlah pembayaran.
- `metode`: manual atau qr_scan (kemudian ditambah midtrans).
- `status`: pending, berhasil, gagal.
- `id_user` opsional untuk mencatat petugas yang memproses.

---

### 4.2 Relasi Transaksi–Pembayaran

**File:** `database/migrations/2026_01_18_124124_add_pembayaran_to_tb_transaksi_table.php`

```php
$table->enum('status_pembayaran', ['pending', 'berhasil', 'gagal'])->default('pending');
$table->unsignedBigInteger('id_pembayaran')->nullable();
$table->foreign('id_pembayaran')->references('id_pembayaran')->on('tb_pembayaran')->onDelete('set null');
```

**Penjelasan untuk presentasi:**
- `status_pembayaran` di transaksi memudahkan filter transaksi belum/sudah dibayar.
- `id_pembayaran` menyimpan referensi ke record pembayaran.
- `onDelete('set null')` agar transaksi tetap ada jika pembayaran dihapus.

---

## 5. Fase 3: Peningkatan Arsitektur (22 Januari)

### 5.1 Soft Deletes

**File:** `database/migrations/2026_01_22_000001_add_soft_delete_to_tables.php`

```php
if (Schema::hasTable('tb_user') && !Schema::hasColumn('tb_user', 'deleted_at')) {
    Schema::table('tb_user', function (Blueprint $table) {
        $table->softDeletes();
    });
}
// Sama untuk tb_kendaraan, tb_transaksi, tb_pembayaran
```

**Penjelasan untuk presentasi:**
- Soft delete menambah kolom `deleted_at`.
- Data tidak dihapus fisik, hanya ditandai.
- Berguna untuk audit, recovery, dan compliance.
- Di Model: `use SoftDeletes;`.

---

### 5.2 Kolom Catatan Transaksi

**File:** `database/migrations/2026_01_22_000002_add_catatan_to_tb_transaksi_table.php`

Kolom `catatan` memungkinkan petugas menambah informasi tambahan per transaksi (misalnya kondisi kendaraan, karcis manual, dll.).

---

### 5.3 TransaksiObserver untuk Log Aktivitas

**File:** `app/Observers/TransaksiObserver.php`

```php
public function created(Transaksi $transaksi): void
{
    if (Auth::check()) {
        LogAktifitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Membuat transaksi parkir #' . str_pad($transaksi->id_parkir, 8, '0', STR_PAD_LEFT),
            'waktu_aktivitas' => Carbon::now(),
        ]);
    }
}

public function updated(Transaksi $transaksi): void
{
    if ($transaksi->isDirty('status') && $transaksi->status === 'keluar') {
        $activity = 'Mencatat kendaraan keluar parkir #' . str_pad($transaksi->id_parkir, 8, '0', STR_PAD_LEFT);
    }
    // ... LogAktifitas::create
}
```

**Penjelasan untuk presentasi:**
- Observer otomatis dipanggil saat transaksi dibuat/diupdate/dihapus.
- Setiap aksi dicatat di `tb_log_aktivitas`.
- Berguna untuk audit dan pelacakan siapa melakukan apa.

---

## 6. Fase 4: Integrasi Midtrans & Fitur Lanjutan (29 Januari – 11 Februari)

### 6.1 Kolom Midtrans di Pembayaran

**File:** `database/migrations/2026_01_29_100000_add_midtrans_fields_to_tb_pembayaran.php`

```php
$table->string('order_id', 64)->nullable()->after('id_parkir');
$table->string('transaction_id', 64)->nullable()->after('order_id');
$table->string('payment_type', 32)->nullable()->after('transaction_id');

// Ubah enum ke VARCHAR agar bisa: manual, qr_scan, midtrans
DB::statement('ALTER TABLE tb_pembayaran MODIFY metode VARCHAR(50)');
DB::statement('ALTER TABLE tb_pembayaran MODIFY status VARCHAR(50)');
```

**Penjelasan untuk presentasi:**
- `order_id`: ID order di Midtrans.
- `transaction_id`: ID transaksi pembayaran Midtrans.
- `payment_type`: tipe pembayaran (bank transfer, e-wallet, dll.).
- Metode dan status diubah ke VARCHAR agar bisa menampung nilai dari Midtrans.

---

### 6.2 Midtrans Order ID di Transaksi

**File:** `database/migrations/2026_02_11_000001_add_midtrans_order_id_to_tb_transaksi.php`

```php
$table->string('midtrans_order_id', 100)->nullable()->after('id_pembayaran');
```

**Penjelasan untuk presentasi:**
- Menyimpan `order_id` Midtrans di transaksi.
- Digunakan untuk sinkronisasi status jika webhook tidak sampai (misalnya di localhost).
- Saat user membuka halaman success, sistem bisa cek status ke API Midtrans.

---

### 6.3 Fitur Bookmark Slot Parkir

**File:** `database/migrations/2026_01_29_023655_add_bookmarked_status_to_transaksis_table.php`

```php
$table->enum('status', ['masuk', 'keluar', 'bookmarked'])->change();
$table->dateTime('bookmarked_at')->nullable()->after('status');
```

**Penjelasan untuk presentasi:**
- Status `bookmarked` untuk slot yang dipesan sementara.
- `bookmarked_at` untuk timer (misalnya 10 menit).
- Slot yang dibookmark tidak bisa dipakai orang lain sampai waktu habis.

---

### 6.4 Kendaraan Fleksibel (Nullable)

**File:** `database/migrations/2026_01_29_000001_make_tb_kendaraan_fields_nullable.php`

Kolom `id_user`, `warna`, `pemilik` di `tb_kendaraan` diubah menjadi nullable agar check-in tetap bisa dilakukan meskipun data kendaraan belum lengkap.

---

### 6.5 Alur Pembayaran Midtrans di Controller

**File:** `app/Http/Controllers/PaymentController.php`

**a) Generate Snap Token**

```php
$order_id = 'PARKIR-' . $id_parkir . '-' . time();
$transaksi->update(['midtrans_order_id' => $order_id]);

$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $gross_amount,
    ],
    'item_details' => [...],
    'customer_details' => [...],
    'callbacks' => ['finish' => $finishUrl, 'unfinish' => $unfinishUrl, 'error' => $errorUrl],
];

$snapToken = \Midtrans\Snap::getSnapToken($params);
```

**Penjelasan untuk presentasi:**
- `order_id` unik per transaksi.
- `midtrans_order_id` disimpan untuk sinkronisasi nanti.
- Snap Token dipakai untuk menampilkan halaman pembayaran Midtrans.

**b) Verifikasi Notifikasi**

```php
// Verifikasi dengan API Midtrans, bukan hanya dari body POST
$statusResponse = \Midtrans\Transaction::status($order_id);
$transaction_status = $statusResponse->transaction_status;

if (in_array($transaction_status, ['capture', 'settlement'])) {
    $this->applyMidtransSuccess($id_parkir, ...);
}
```

**Penjelasan untuk presentasi:**
- Status pembayaran dicek langsung ke API Midtrans.
- Mencegah manipulasi notifikasi palsu.
- Hanya `capture` dan `settlement` yang dianggap berhasil.

**c) Sinkronisasi Status (Fallback)**

```php
private function syncMidtransPaymentStatus(int $id_parkir): bool
{
    // Jika status_pembayaran belum 'berhasil' dan ada midtrans_order_id
    // Panggil API Midtrans untuk cek status
    // Jika settlement/capture → applyMidtransSuccess
}
```

**Penjelasan untuk presentasi:**
- Dipakai saat user membuka halaman success setelah bayar.
- Berguna jika webhook Midtrans tidak sampai (misalnya di localhost).
- Memastikan pembayaran tetap tercatat.

---

## 7. Fase 5: Plate Recognizer & Peta Parkir (Februari 2026)

### 7.1 Plate Recognizer Service

**File:** `app/Services/PlateRecognizerService.php`

```php
public function scanPlate($image, bool $includeRawResponse = false): array
{
    $response = Http::timeout(30)
        ->withHeaders(['Authorization' => 'Token ' . $this->apiKey])
        ->attach('upload', file_get_contents($image->getRealPath()), $image->getClientOriginalName())
        ->post($this->apiUrl);

    if ($response->failed()) {
        throw new \Exception("Plate Recognizer API error: ...");
    }

    $data = $response->json();
    if (empty($data['results'])) {
        return ['plate_number' => null, 'confidence' => 0, 'valid' => false, ...];
    }

    $firstResult = $data['results'][0];
    $plateNumber = $firstResult['plate'] ?? null;
    $confidence = floatval($firstResult['score'] ?? 0);
    $isValid = $confidence >= 0.80; // Threshold 80%

    return [
        'plate_number' => $plateNumber,
        'confidence' => $confidence,
        'valid' => $isValid,
        'message' => $isValid ? 'Plat nomor berhasil dideteksi' : 'Plat tidak valid (confidence di bawah 80%)',
    ];
}
```

**Penjelasan untuk presentasi:**
- Menggunakan Laravel HTTP Client.
- API key disimpan di `.env`, tidak di frontend.
- Threshold 80% untuk validasi hasil.
- Error handling untuk kegagalan API dan hasil kosong.

---

### 7.2 Plate Recognizer Controller

**File:** `app/Http/Controllers/Api/PlateRecognizerController.php`

```php
public function scanPlate(Request $request): JsonResponse
{
    $request->validate([
        'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB
    ]);

    $image = $request->file('image');
    $result = $this->plateRecognizerService->scanPlate($image, $request->boolean('debug'));

    return response()->json([
        'success' => true,
        'plate_number' => $result['plate_number'],
        'confidence' => $result['confidence'],
        'valid' => $result['valid'],
        'message' => $result['message'],
    ]);
}
```

**Penjelasan untuk presentasi:**
- Validasi file: max 5MB, format JPG/PNG.
- Controller hanya menerima request dan memanggil service.
- Logic bisnis ada di service (separation of concerns).

---

### 7.3 Komponen Kamera Frontend

**File:** `resources/views/components/plate-scanner.blade.php`

Fitur utama:
- `getUserMedia` dengan `facingMode: 'environment'` (kamera belakang).
- Tombol Buka Kamera, Ambil Foto, Scan Plat, Ambil Ulang.
- Upload via `fetch()` ke `/scan-plate`.
- Auto-fill select kendaraan jika plat terdeteksi dan valid.
- Loading indicator dan pesan error/sukses.

---

### 7.4 Peta Parkir (ParkingMapController)

**File:** `app/Http/Controllers/Api/ParkingMapController.php`

```php
$parkingAreas = AreaParkir::with(['transaksis' => function($query) {
    $query->where(function($q) {
        $q->whereNull('waktu_keluar')->where('status', 'masuk');
    })->orWhere(function($q) {
        $q->where('status', 'bookmarked')
          ->where('bookmarked_at', '>', Carbon::now()->subMinutes(10));
    });
}, 'transaksis.kendaraan', 'transaksis.user'])->get();

$mapData = $parkingAreas->map(function($area) {
    $status = 'empty';
    if ($occupiedTransaction) $status = 'occupied';
    elseif ($bookmarkedTransaction) $status = 'bookmarked';
    return ['id' => $area->id_area, 'name' => $area->nama_area, 'status' => $status, ...];
});
```

**Penjelasan untuk presentasi:**
- Mengambil area parkir beserta transaksi aktif dan bookmark.
- Status slot: `empty`, `occupied`, `bookmarked`.
- Bookmark berlaku 10 menit.
- Data dikembalikan sebagai JSON untuk frontend.

---

## 8. Penjelasan Kode Utama

### 8.1 Check-in dengan Database Transaction & Lock

**File:** `app/Http/Controllers/TransaksiController.php`

```php
public function checkIn(Request $request)
{
    $transaksi = DB::transaction(function () use ($request) {
        // Lock area untuk mencegah race condition
        $area = AreaParkir::lockForUpdate()->findOrFail($request->id_area);

        if ($area->terisi >= $area->kapasitas) {
            throw new \Exception('Kapasitas area parkir sudah penuh');
        }

        $transaksi = Transaksi::create([...]);
        $area->increment('terisi');

        return $transaksi;
    });
}
```

**Penjelasan untuk presentasi:**
- `DB::transaction()` memastikan semua operasi berhasil atau di-rollback.
- `lockForUpdate()` mencegah dua petugas memakai slot yang sama bersamaan.
- `increment('terisi')` mengupdate kapasitas secara atomik.

---

### 8.2 Check-out dengan Perhitungan Biaya

```php
$waktu_keluar = Carbon::now();
$durasi_jam = ceil($waktu_keluar->diffInMinutes($transaksi->waktu_masuk) / 60);
$biaya_total = $durasi_jam * $transaksi->tarif->tarif_perjam;

$transaksi->update([
    'waktu_keluar' => $waktu_keluar,
    'durasi_jam' => $durasi_jam,
    'biaya_total' => $biaya_total,
    'status' => 'keluar',
    'status_pembayaran' => 'pending',
]);

$area->decrement('terisi');
```

**Penjelasan untuk presentasi:**
- Durasi dihitung dalam jam (dibulatkan ke atas).
- Biaya = durasi × tarif per jam.
- Status diubah ke `keluar`, pembayaran ke `pending`.
- Kapasitas area dikurangi.

---

### 8.3 Model Transaksi dengan Accessor

**File:** `app/Models/Transaksi.php`

```php
public function getBiayaTotalAttribute()
{
    if ($this->attributes['biaya_total'] ?? null) {
        return $this->attributes['biaya_total'];
    }
    if ($this->waktu_masuk && $this->waktu_keluar && $this->tarif) {
        $durasi = $this->getDurasiJamAttribute();
        return $durasi * $this->tarif->tarif_perjam;
    }
    return 0;
}
```

**Penjelasan untuk presentasi:**
- Accessor memungkinkan `$transaksi->biaya_total` dihitung otomatis jika belum disimpan.
- Berguna untuk preview biaya sebelum checkout.

---

## Ringkasan untuk Presentasi

1. **Fondasi (14–15 Jan):** Database, RBAC, middleware role.
2. **Pembayaran (18–19 Jan):** Tabel pembayaran, relasi ke transaksi.
3. **Arsitektur (22 Jan):** Soft deletes, catatan, observer log aktivitas.
4. **Midtrans (29 Jan – 11 Feb):** Kolom Midtrans, webhook, sinkronisasi status.
5. **UX & Integrasi (Feb):** Bookmark slot, kendaraan nullable, Plate Recognizer, peta parkir.

**Poin penting:**
- Database transaction dan row lock untuk konsistensi data.
- Verifikasi pembayaran via API Midtrans, bukan hanya dari webhook.
- Service layer untuk Plate Recognizer (modular, mudah diuji).
- RBAC untuk keamanan akses.
- Soft deletes untuk audit dan recovery.

---

## Lampiran A: Diagram Entity-Relationship (ERD)

```
User (1) ----< Kendaraan (M)     [id_user nullable]
User (1) ----< Transaksi (M)    [id_user = operator]
User (1) ----< Pembayaran (M)   [id_user = petugas]
User (1) ----< LogAktifitas (M)

Kendaraan (1) ----< Transaksi (M)
AreaParkir (1) ----< Transaksi (M)
Tarif (1) ----< Transaksi (M)

Transaksi (1) ---- Pembayaran (1)  [id_pembayaran, status_pembayaran]
```

**Tabel utama:**
- `tb_user` - Pengguna (admin, petugas, owner, user)
- `tb_kendaraan` - Data kendaraan (plat_nomor, jenis, warna, pemilik)
- `tb_area_parkir` - Area parkir (nama, kapasitas, terisi)
- `tb_tarif` - Tarif per jam per jenis kendaraan
- `tb_transaksi` - Transaksi parkir (check-in/check-out)
- `tb_pembayaran` - Record pembayaran (manual, qr_scan, midtrans)
- `tb_log_aktivitas` - Audit trail aktivitas user

---

## Lampiran B: Alur Check-in & Check-out (Activity Diagram)

**Check-in:**
1. Petugas buka form "Catat Kendaraan Masuk"
2. Pilih kendaraan, tarif, area (bisa pakai scan plat)
3. Sistem validasi → DB Transaction + Lock Area
4. Cek kapasitas (terisi < kapasitas)
5. Buat Transaksi, increment terisi
6. Commit → Redirect ke Parkir Aktif

**Check-out:**
1. Petugas pilih transaksi dari Parkir Aktif
2. Konfirmasi checkout
3. DB Transaction + Lock Transaksi
4. Hitung durasi & biaya
5. Update Transaksi (status keluar), decrement terisi
6. Redirect ke Pilih Transaksi Pembayaran

**Pembayaran:**
- Manual: Petugas konfirmasi → Buat Pembayaran → Update Transaksi
- QR: Customer scan → Signed URL → Buat Pembayaran → Update Transaksi
- Midtrans: Customer bayar online → Webhook/API → Buat Pembayaran → Update Transaksi

---

## Lampiran C: Lokasi File Penting

| Kategori | File |
|----------|------|
| **Migrations** | `database/migrations/` |
| **Models** | `app/Models/` |
| **Controllers** | `app/Http/Controllers/` |
| **Services** | `app/Services/PlateRecognizerService.php` |
| **Middleware** | `app/Http/Middleware/RoleMiddleware.php` |
| **Observers** | `app/Observers/TransaksiObserver.php` |
| **Routes** | `routes/web.php` |
| **Views** | `resources/views/` |
| **Components** | `resources/views/components/plate-scanner.blade.php` |
| **Config** | `config/services.php`, `config/midtrans.php` |

---

## Lampiran D: Tips Presentasi

1. **Mulai dengan overview:** Tunjukkan ERD dan alur bisnis check-in → check-out → pembayaran.
2. **Demo live:** Check-in dengan scan plat → Check-out → Pembayaran Midtrans.
3. **Soroti keamanan:** RBAC, verifikasi Midtrans via API, API key di backend.
4. **Soroti konsistensi data:** DB transaction, row lock, soft delete.
5. **Jelaskan arsitektur:** Service layer, observer, separation of concerns.
6. **Siapkan jawaban:** "Bagaimana jika webhook Midtrans tidak sampai?" → Sinkronisasi saat buka halaman success.

---

*Dokumentasi ini dibuat untuk mendukung presentasi proyek NESTON.*

