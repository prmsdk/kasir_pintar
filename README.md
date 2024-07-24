## Technical Test – Primasdika Yunia Putra

### 1. Link Git Penugasan

• Repository: https://github.com/prmsdk/kasir_pintar

### 2. Dokumentasi Langkah Instalasi/Deployment

Langkah-Langkah Instalasi (jalankan pada terminal/command prompt):

-   Clone Repository
    `git clone https://github.com/prmsdk/kasir_pintar`
    `cd [NAMA_PROYEK]`
-   Instal Dependensi
    `composer install`
    `npm install`
-   Konfigurasi Env
    `cp .env.example .env`
    Tambahkan pada .env:
    ```env:
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```
-   Generate App Key
    `php artisan key:generate`
-   Migrasi Database + Seeding
    `php artisan migrate:fresh --seed`
-   Hubungkan Storage Link
    `php artisan storage:link`
-   Jalankan Server
    `php artisan serve`
    `npm run dev`
