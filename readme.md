# Aplikasi Manajemen Pemeliharaan CCTV

Aplikasi web sederhana yang dibangun untuk mengelola dan memantau sistem pemeliharaan CCTV. Aplikasi ini membantu dalam melacak inventaris CCTV, mengelola teknisi, mencatat jadwal pemeliharaan, dan menangani laporan kerusakan.

## Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur untuk memudahkan manajemen sistem CCTV:

### 1. Dashboard Interaktif
- **Ringkasan Statistik:** Menampilkan data kunci secara visual, seperti:
  - Jumlah CCTV Aktif
  - Jumlah CCTV Bermasalah (dalam perbaikan, perawatan, atau rusak)
  - Total Teknisi Terdaftar
- **Akses Cepat:** Menyediakan tautan langsung ke fungsi-fungsi yang paling sering digunakan seperti input maintenance, lapor kerusakan, dan penambahan data baru.

### 2. Manajemen Data Master (CRUD)
- **Manajemen Teknisi:** Menambah, melihat, mengedit, dan menghapus data teknisi.
- **Manajemen Unit CCTV:** Mengelola semua unit CCTV yang terpasang, termasuk statusnya (Aktif, Perbaikan, Perawatan, Rusak).
- **Manajemen Tipe CCTV:** Mengelola berbagai tipe atau model CCTV yang digunakan.
- **Manajemen Komponen:** Mengelola daftar komponen atau suku cadang yang tersedia untuk perbaikan dan pemeliharaan.
- **Manajemen Pengguna:** Mengelola akun pengguna yang dapat mengakses aplikasi.

### 3. Pencatatan Aktivitas & Laporan
- **Jadwal Maintenance:** Mencatat dan melihat riwayat kegiatan pemeliharaan yang dilakukan oleh teknisi.
- **Laporan Kerusakan:** Mencatat laporan kerusakan dari setiap unit CCTV untuk tindak lanjut.
- **Penggunaan Komponen:** Melacak komponen apa saja yang digunakan selama perbaikan atau pemeliharaan.
- **Riwayat Laporan:** Melihat histori laporan kerusakan, maintenance, dan penggunaan komponen.

### 4. Pencarian
- Fitur pencarian global untuk menemukan data dengan cepat di seluruh aplikasi.

### 5. Ekspor Data
- Kemampuan untuk mengekspor data (seperti laporan) ke format spreadsheet (Excel) untuk keperluan analisis atau arsip. *(Fitur ini didukung oleh library `phpoffice/phpspreadsheet`)*.

## Struktur Proyek

Proyek ini dibangun menggunakan PHP native dengan pendekatan arsitektur yang mirip dengan Model-View-Controller (MVC):

- `app/Controllers/`: Berisi semua logika bisnis dan alur aplikasi.
- `app/Models/`: Bertanggung jawab untuk semua interaksi dengan database (query, pengambilan, dan penyimpanan data).
- `public/views/`: Berisi semua file antarmuka pengguna (tampilan HTML) yang dirender ke browser.
- `config/`: Tempat untuk file konfigurasi, seperti koneksi database.
- `index.php`: File entri utama yang menangani semua permintaan.

## Teknologi yang Digunakan

- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript
- **Framework/Library:**
  - [Bootstrap](https://getbootstrap.com/) untuk styling komponen UI.
  - [Bootstrap Icons](https://icons.getbootstrap.com/) untuk ikon.
  - [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) untuk fungsionalitas ekspor ke Excel.

## Panduan Instalasi

Untuk menjalankan aplikasi ini di lingkungan lokal, ikuti langkah-langkah berikut:

1.  **Clone Repository**
    ```bash
    git clone <URL_REPOSITORY_ANDA>
    cd cctv-maintenance
    ```

2.  **Setup Database**
    - Buat database baru di MySQL/MariaDB dengan nama `db_cctv_maintenance`.
    - Impor file SQL (jika tersedia) atau buat tabel-tabel yang diperlukan sesuai dengan struktur yang ada di `app/Models/`.

3.  **Konfigurasi Koneksi**
    - Buka file `config/database.php`.
    - Sesuaikan konstanta `DB_HOST`, `DB_USER`, `DB_PASS`, dan `DB_NAME` dengan konfigurasi database lokal Anda.
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', ''); // Sesuaikan jika Anda menggunakan password
    define('DB_NAME', 'db_cctv_maintenance');
    ```

4.  **Jalankan Aplikasi**
    - Tempatkan direktori proyek di dalam folder `htdocs` pada server lokal Anda (misalnya XAMPP, WAMP).
    - Buka browser dan akses aplikasi melalui URL seperti `http://localhost/cctv-maintenance`.