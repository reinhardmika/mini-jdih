# Mini JDIH - Kejaksaan Negeri Trenggalek

Aplikasi web sederhana Jaringan Dokumentasi dan Informasi Hukum (JDIH) untuk Kejaksaan Negeri Trenggalek.

## Requirement
- PHP 8.2+
- Composer
- Node.js & npm
- PostgreSQL

## Cara Install

1. Clone repository
```
git clone https://github.com/reinhardmika/mini-jdih.git
cd mini-jdih
```

2. Install dependency PHP
```
composer install
```

3. Install dependency JS
```
npm install
```

4. Copy environment file
```
cp .env.example .env
```

5. Generate application key
```
php artisan key:generate
```

6. Buat database PostgreSQL bernama `jdih`, lalu sesuaikan kredensial di `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=jdih
DB_USERNAME=postgres
DB_PASSWORD=1234
```

7. Jalankan migration & seeder
```
php artisan migrate --seed
```

8. Buat symbolic link storage (untuk file PDF)
```
php artisan storage:link
```

9. Jalankan server
```
php artisan serve
```

10. Di terminal terpisah, jalankan Vite
```
npm run dev
```

11. Akses di browser: `http://127.0.0.1:8000`

## Akun Admin
- Email: admin@jdih.test
- Password: password123

## Fitur
- Halaman publik: pencarian & filter peraturan (kategori, status, rentang tahun)
- Halaman detail peraturan dengan viewer PDF
- Admin panel: CRUD Peraturan & Kategori dengan proteksi role
- Sistem autentikasi (Laravel Breeze)