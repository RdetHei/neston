# Optimisasi Neston - Panduan Lengkap

## 1. Frontend Optimizations

### A. Frontend Changes Applied:
- ✅ Google Fonts & Font Awesome dimuat dengan preload dan non-blocking
- ✅ Chart.js dan SweetAlert2 diimport via npm (bukan CDN)
- ✅ Aria labels ditambahkan ke elemen interaktif
- ✅ SVG icons dengan aria-hidden="true"

### B. Vite Build:
Jalankan build untuk produksi:
```bash
npm run build
```

---

## 2. Laravel Profiling & TTFB Optimization

### A. Clockwork Profiler:
Clockwork sudah diinstall. Untuk menggunakannya:
1. Akses `/clockwork` di browser
2. Atau install ekstensi Chrome Clockwork
3. Lihat detail query, waktu eksekusi, dan performa

### B. Analisis Dashboard TTFB:
DashboardController (app/Http/Controllers/DashboardController.php:17
- Banyak query yang berulang (contoh: grafik pendapatan per hari)
- Rekomendasi: Cache!

### C. Laravel Cache:
Tambahkan caching untuk query berulang:
```php
// Contoh: Cache total kendaraan
$totalKendaraan = Cache::remember('total_kendaraan', 3600, fn() => Kendaraan::count());
```

### D. Optimisasi Query:
Pastikan semua relasi di-ELOQUENT:
- Gunakan `with()` untuk eager loading
- Tambahkan index database pada kolom yang sering di-where

---

## 3. Nginx Configuration

File konfigurasi Nginx untuk Neston:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name neston.test;
    root /var/www/neston/public;
    index index.php;

    # HTTP/2 Enable
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    # SSL Configuration (sesuaikan dengan sertifikat Anda)
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/json application/xml+rss font/woff font/woff2 image/svg+xml;

    # Brotli (jika tersedia)
    # brotli on;
    # brotli_types text/plain text/css text/xml text/javascript application/javascript application/json application/xml+rss font/woff font/woff2 image/svg+xml;

    # Cache Static Assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, max-age=31536000, immutable";
        try_files $uri =404;
    }

    # HTML Must-Revalidate
    location ~* \.html$ {
        expires -1;
        add_header Cache-Control "no-cache, must-revalidate";
    }

    # Laravel Front Controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Performance
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. PHP-FPM Configuration

File `/etc/php/8.2/fpm/pool.d/neston.conf:
```ini
[neston]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm-neston.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

php_admin_value[error_log = /var/log/php8.2-fpm-neston-error.log
php_admin_flag[log_errors] = on
php_value[memory_limit] = 256M
php_value[max_execution_time] = 30
php_value[upload_max_filesize] = 10M
php_value[post_max_size] = 10M
```

---

## 5. Laravel Production Optimizations

Jalankan perintah ini untuk production:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 6. Rekomendasi Lainnya

- Install OPcache untuk PHP
- Gunakan Redis sebagai cache driver
- Monitor performa dengan Laravel Telescope (opsional)
```
