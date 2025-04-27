# MyMoney - Aplikasi Manajemen Keuangan

Aplikasi manajemen keuangan berbasis web yang membantu pengguna untuk melacak pemasukan dan pengeluaran mereka.

## Fitur

- Manajemen transaksi (pemasukan dan pengeluaran)
- Kategorisasi transaksi
- Dashboard dengan ringkasan keuangan
- Laporan keuangan
- Analisis pengeluaran
- Export laporan (PDF & Excel)

## Teknologi

- Laravel
- MySQL
- Tailwind CSS
- Chart.js

## Instalasi

1. Clone repository
```bash
git clone https://github.com/temorubun/MyMoney.git
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file .env.example ke .env dan sesuaikan konfigurasi database

4. Generate application key
```bash
php artisan key:generate
```

5. Jalankan migrasi database
```bash
php artisan migrate
```

6. Jalankan seeder (opsional)
```bash
php artisan db:seed
```

7. Jalankan aplikasi
```bash
php artisan serve
```

## Login Default

- Email: admin@mymoney.com
- Password: admin123
