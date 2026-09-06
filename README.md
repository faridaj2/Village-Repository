# SIDesa - Sistem Informasi Desa Waeleman

Aplikasi web untuk pengelolaan data desa, penduduk, rumah, fasilitas, dan layanan surat menyurat digital.

Dibangun dengan Laravel 13, Livewire 3, Tailwind CSS, dan Vite.

---

## Fitur Utama

- Dashboard statistik desa
- Peta interaktif wilayah RW/RT, rumah, dan fasilitas umum
- Manajemen Penduduk (CRUD, pencarian, mutasi)
- Manajemen Rumah
- Kartu Keluarga (KK)
- Fasilitas Umum
- Surat Menyurat (template dinamis, pengajuan, tracking, draft, finalisasi, cetak, PDF A4/F4, log pencetakan)
- Pengumuman desa
- Pengaturan identitas desa
- Manajemen pengguna (Admin dan Operator)

---

## Instalasi Lokal

### Prasyarat

- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL / MariaDB

### Langkah-langkah

1. Clone repository

git clone git@github.com:faridaj2/Village-Repository.git
cd Village-Repository

2. Install dependensi PHP

composer install

3. Salin file environment

cp .env.example .env

4. Generate key aplikasi

php artisan key:generate

5. Atur database di .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desa_waeleman
DB_USERNAME=root
DB_PASSWORD=

6. Jalankan migrasi dan seeder

php artisan migrate --seed

7. Install dependensi frontend dan build asset

npm install
npm run build

8. Jalankan server lokal

php artisan serve

Aplikasi dapat diakses di http://127.0.0.1:8000

---

## Akun Default

| Role     | Email               | Password   |
|----------|---------------------|------------|
| Admin    | admin@sidesa.id     | password   |
| Operator | operator@sidesa.id  | password   |

Segera ubah password setelah login pertama.

---

## Deploy ke Hosting

1. Upload seluruh file proyek ke hosting (kecuali vendor, node_modules, .env, dan isi storage/logs, storage/framework/cache).
2. Buat database baru dan catat kredensialnya.
3. Salin .env.example menjadi .env, lalu isi APP_URL, kredensial database, APP_ENV=production, dan APP_DEBUG=false.
4. Jalankan perintah berikut di terminal hosting:

composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

5. Install dan build aset frontend:

npm install
npm run build

6. Beri permission tulis:

chmod -R 775 storage bootstrap/cache

7. Arahkan domain ke folder public.

---

## Migrasi Database

Migrasi sudah dirapikan agar bisa langsung dijalankan di hosting:

php artisan migrate --force

Jika perlu rollback, foreign key checks dinonaktifkan otomatis agar tidak terjadi error circular reference.

---

## Lisensi

Proyek ini menggunakan MIT License.
