Dokumentasi Proyek: Sistem Manajemen Maintenance CCTV

Versi: 1.0 (Fase Fungsional)
Tanggal Terakhir Update: 25 Juni 2025

1. Deskripsi Proyek

Aplikasi ini adalah sistem manajemen berbasis web yang berfungsi sebagai alat bantu untuk mencatat, melacak, dan mengelola semua aset CCTV serta seluruh aktivitas perawatannya. Tujuannya adalah untuk memusatkan data, mempermudah pelacakan riwayat perbaikan, dan menyediakan laporan aktivitas yang terstruktur.

2. Arsitektur & Teknologi

Untuk memastikan kode yang bersih, terstruktur, dan mudah dikelola, aplikasi ini mengadopsi pola arsitektur MVC-like (Model-View-Controller).

    Stack Teknologi:

        Bahasa: PHP (Native)

        Database: MySQL / MariaDB

        Tampilan (Front-end): HTML, Bootstrap 5, Bootstrap Icons, JavaScript (untuk interaktivitas)

    Pola Arsitektur:

        Model (/app/Models): Bertindak sebagai lapisan akses data (mirip Pola Repository atau DAO). Setiap kelas Model bertanggung jawab penuh atas semua interaksi (query SELECT, INSERT, UPDATE, DELETE) ke satu tabel spesifik di database.

        View (/public/views): Bertanggung jawab murni untuk presentasi atau tampilan (UI). Isinya hanya HTML dan kode PHP sederhana untuk menampilkan data yang sudah disiapkan oleh Controller.

        Controller (/app/Controllers): Bertindak sebagai "otak" aplikasi. Controller menerima permintaan dari user, berkomunikasi dengan Model untuk mengambil atau memanipulasi data, dan kemudian memilih View mana yang akan ditampilkan kepada user.

        Router (/public/index.php): Semua permintaan dari browser masuk melalui satu pintu gerbang (Front Controller). Router ini yang bertugas memetakan URL ke Controller dan method yang sesuai.

3. Struktur Folder Proyek

cctv-maintenance/
├── app/
│ ├── Controllers/ // Berisi semua file Controller (logika aplikasi)
│ └── Models/ // Berisi semua file Model (interaksi database)
│
├── config/
│ └── database.php // Konfigurasi koneksi database
│
└── public/ // Folder yang dapat diakses publik dari browser
├── assets/ // Untuk file CSS, JavaScript, dan gambar
├── views/ // Berisi semua file tampilan (UI)
└── index.php // Router / Pintu Gerbang Utama Aplikasi

REKAP PROGRES PROYEK

A. Apa yang TELAH Diselesaikan (Fitur yang Sudah Berfungsi)

    ✅ Fondasi & Arsitektur: Proyek berhasil direstrukturisasi ke dalam pola MVC-like.

    ✅ Keamanan Dasar:

        Implementasi password_hash() dan password_verify() untuk manajemen password yang aman.

        Implementasi Prepared Statements pada semua query SQL untuk mencegah SQL Injection.

    ✅ Manajemen User (CRUD): Fungsionalitas penuh bagi admin untuk menambah, melihat, mengedit (termasuk reset password), dan menghapus data user.

    ✅ Manajemen Data Master (CRUD): Fungsionalitas penuh bagi admin untuk mengelola:

        Data Teknisi

        Data Tipe/Model CCTV

        Data Unit CCTV

        Data Komponen & Stok

    ✅ Fitur Laporan Aktivitas:

        Modul Laporan Maintenance dengan fitur Tambah & Edit via pop-up modal.

        Modul Laporan Kerusakan dengan fitur Tambah & Edit via pop-up modal.

    ✅ Desain Antarmuka (UI): Tampilan aplikasi konsisten di seluruh halaman dengan tema biru-putih, sidebar navigasi, dan tabel data yang rapi.

B. Apa yang AKAN Dilakukan (Rencana Pengembangan Selanjutnya)

Ini adalah daftar tugas yang telah kita sepakati untuk menyempurnakan aplikasi.

    1. Rombak Total Fitur Pemakaian Komponen:

        [ ] Hapus fitur input komponen dari modal "Tambah Laporan Maintenance".

        [ ] Buat modul baru "Input Pemakaian Komponen" dengan halaman form terpisah.

        [ ] Form harus memungkinkan user memilih Laporan Maintenance atau Laporan Kerusakan sebagai referensi.

        [ ] Implementasikan Transaksi Database di backend untuk:

            Menyimpan data pemakaian ke log_komponen_dipakai sambil menghitung total biaya.

            Otomatis mengurangi stok di tabel komponen.

        [ ] Tampilkan notifikasi sukses yang informatif (termasuk sisa stok terbaru).

        [ ] Tambahkan kartu "Input Pemakaian Komponen" di Dashboard.

    2. Standarisasi & Upgrade Tampilan Tabel:

        [ ] Audit semua tabel di halaman Data Master untuk memastikan semua kolom dari database (misal: Spesifikasi, Umur Ekonomis, dll) sudah ditampilkan.

        [ ] Tambahkan kolom "Aksi" pada semua halaman Laporan (laporan_maintenance, laporan_kerusakan, dll).

        [ ] Implementasikan tombol "Detail" pada kolom Aksi yang akan memunculkan pop-up modal berisi ringkasan laporan dan daftar komponen yang digunakan.

        [ ] Aktifkan fungsionalitas tombol "Edit" dan "Hapus" untuk semua jenis data laporan.
