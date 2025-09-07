# Dokumentasi Teknis Aplikasi Manajemen CCTV

Dokumen ini bertujuan untuk memberikan pemahaman mendalam mengenai arsitektur, alur kerja, dan komponen teknis dari aplikasi Manajemen Pemeliharaan CCTV. Ditujukan untuk developer yang akan mengelola atau melanjutkan pengembangan aplikasi ini.

---

## 1. Struktur Direktori

Aplikasi ini menggunakan struktur yang terinspirasi dari pola Model-View-Controller (MVC) untuk memisahkan logika, data, dan tampilan.

```
/ (root)
├── app/
│   ├── Controllers/  # (C) Berisi semua file Controller (Logika Aplikasi)
│   └── Models/       # (M) Berisi semua file Model (Logika & Interaksi Database)
├── config/
│   └── database.php  # Konfigurasi dan koneksi ke database
├── public/
│   ├── assets/       # Semua aset statis (CSS, JS, gambar, font)
│   └── views/        # (V) Semua file View (Tampilan & HTML)
│       └── partials/ # Bagian view yang dipakai berulang (header, footer, dll)
├── vendor/           # Direktori dependensi yang dikelola oleh Composer
├── .htaccess         # Aturan routing dan konfigurasi server Apache
├── composer.json     # Definisi dependensi proyek
├── index.php         # File entri utama (Front Controller)
└── dokumentasi.md    # File ini
```

---

## 2. Alur Kerja Aplikasi (Request Lifecycle)

Setiap permintaan HTTP ke aplikasi ini mengikuti alur yang sama:

1.  **Masuk ke `.htaccess`**: Semua permintaan (kecuali untuk file yang ada seperti gambar atau CSS) dialihkan ke `index.php`.
2.  **Inisialisasi `index.php`**: File ini bertindak sebagai **Front Controller**. Tugasnya adalah:
    a. Memuat autoloader Composer (`vendor/autoload.php`).
    b. Memulai session (`session_start()`).
    c. Mendefinisikan konstanta global seperti `BASE_URL`.
    d. Membuat koneksi database dengan memanggil `config/database.php`.
    e. Mengambil parameter `page` dari URL (`?page=...`) untuk menentukan halaman mana yang diminta.
3.  **Routing**: `index.php` menggunakan struktur `switch-case` besar untuk menentukan Controller mana yang harus dimuat berdasarkan nilai `page`.
4.  **Eksekusi Controller**: Controller yang relevan dimuat dan sebuah method di dalamnya dipanggil (misalnya, untuk `?page=teknisi`, `TeknisiController->index()` akan dieksekusi).
5.  **Interaksi dengan Model**: Di dalam method Controller, ia akan membuat instance dari Model yang sesuai. Controller kemudian memanggil method dari Model untuk mengambil data dari atau menyimpan data ke database.
6.  **Interaksi Model dengan Database**: Model berisi semua query SQL. Idealnya, semua query ini menggunakan **Prepared Statements** untuk mencegah SQL Injection.
7.  **Render View**: Setelah Controller mendapatkan data dari Model, ia akan memuat file View yang sesuai (misalnya `require_once 'public/views/data_teknisi.php'`). Data yang didapat dari model dilewatkan ke view ini.
8.  **Tampilan Final**: File View dirender menjadi HTML. View ini juga memuat `partials` (seperti header dan footer) untuk membangun halaman HTML lengkap yang dikirim ke browser pengguna.

---

## 3. Komponen Utama

### a. Controllers (`app/Controllers/`)

- **Tanggung Jawab**: Mengatur logika aplikasi, memproses input dari pengguna (dari `$_GET`, `$_POST`), memanggil Model, dan menentukan View mana yang akan ditampilkan.
- **Contoh (`TeknisiController.php`):**
    - `__construct($db)`: Menerima koneksi database dan membuat instance dari `TeknisiModel`.
    - `index()`: Menampilkan halaman utama data teknisi. Memanggil `TeknisiModel->getAll()` untuk mengambil semua data, lalu memuat view `data_teknisi.php`.
    - `create()`: Menampilkan form untuk menambah teknisi baru.
    - `store()`: Memproses data dari form tambah, memanggil `TeknisiModel->create()` untuk menyimpan ke database, lalu mengarahkan pengguna kembali ke halaman utama.
    - `edit()`: Menampilkan form untuk mengedit data teknisi berdasarkan ID.
    - `update()`: Memproses data dari form edit dan memanggil `TeknisiModel->update()`.
    - `delete()`: Menghapus data teknisi berdasarkan ID dan memanggil `TeknisiModel->delete()`.

### b. Models (`app/Models/`)

- **Tanggung Jawab**: Berinteraksi langsung dengan database. Semua query SQL harus berada di sini. Bertugas untuk melakukan operasi CRUD (Create, Read, Update, Delete).
- **Contoh (`TeknisiModel.php`):**
    - `__construct($db)`: Menerima dan menyimpan koneksi database.
    - `getAll($sortBy, $sortOrder)`: Mengambil semua data teknisi dari database. **Peringatan**: Metode ini saat ini masih rentan terhadap SQL Injection karena variabel `$sortBy` dan `$sortOrder` langsung dimasukkan ke string query. **Perlu diperbaiki**.
    - `getById($id)`: Mengambil satu data teknisi berdasarkan ID. Sudah menggunakan prepared statement (aman).
    - `create($data)`: Menyimpan data teknisi baru. Sudah menggunakan prepared statement (aman).
    - `update($id, $data)`: Memperbarui data teknisi. Sudah menggunakan prepared statement (aman).
    - `delete($id)`: Menghapus data teknisi. Sudah menggunakan prepared statement (aman).

### c. Views (`public/views/`)

- **Tanggung Jawab**: Menampilkan data ke pengguna dalam format HTML. File view harus seminimal mungkin berisi logika PHP, idealnya hanya untuk perulangan (`foreach`) dan menampilkan variabel (`echo`).
- **Partials (`/partials`):** Berisi potongan kode HTML yang digunakan di banyak halaman, seperti `header.php`, `footer.php`, `sidebar.php`, dan `navbar.php`. Ini membantu menghindari duplikasi kode HTML.

---

## 4. Panduan Pengembangan

### Cara Menambahkan Halaman CRUD Baru (Contoh: Halaman "Gedung")

1.  **Buat Tabel di Database**: Buat tabel `gedung` di database MySQL Anda.
2.  **Buat Model**: Buat file `app/Models/GedungModel.php`. Isi dengan class `GedungModel` yang memiliki method CRUD (`getAll`, `getById`, `create`, dll) untuk tabel `gedung`.
3.  **Buat Controller**: Buat file `app/Controllers/GedungController.php`. Buat class `GedungController` dengan method-method seperti `index`, `create`, `store`, `edit`, `update`, `delete`.
4.  **Buat View**: Buat file-file view yang diperlukan di `public/views/`, seperti:
    - `data_gedung.php` (untuk menampilkan tabel data)
    - `form_gedung.php` (untuk form tambah dan edit)
5.  **Tambahkan Rute**: Buka `index.php` dan tambahkan `case` baru di dalam struktur `switch` untuk menangani `page=gedung`, `page=gedung_create`, `page=gedung_store`, dan seterusnya. Arahkan setiap case ke method yang sesuai di `GedungController`.
6.  **Tambahkan Link di Menu**: Buka `public/views/partials/sidebar.php` dan tambahkan link baru di menu navigasi yang mengarah ke `index.php?page=gedung`.

---

## 5. Rekomendasi Peningkatan (Improvement)

Seperti yang telah didiskusikan, berikut adalah area utama untuk peningkatan:

1.  **Keamanan**: Prioritaskan untuk memperbaiki semua query SQL yang belum menggunakan prepared statements untuk mencegah SQL Injection.
2.  **Struktur Kode (DRY)**: Implementasikan sebuah `BaseController` dan sistem layout template untuk mengurangi duplikasi kode.
3.  **Routing**: Ganti `switch-case` di `index.php` dengan library routing yang lebih modern untuk skalabilitas.
4.  **Autoloading**: Manfaatkan PSR-4 autoloader dari Composer untuk menghilangkan semua `require_once`.
