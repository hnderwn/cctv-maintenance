<?php
// config/database.php

// --- Konfigurasi Database ---
// Sesuaikan dengan pengaturan di XAMPP lo
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_cctv_maintenance'); // Pastikan nama DB sesuai

// --- Membuat Koneksi ---
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// --- Cek Koneksi ---
// Jika koneksi gagal, hentikan aplikasi dan tampilkan pesan error
if (!$conn) {
    die("KONEVSI DATABASE GAGAL: " . mysqli_connect_error());
}

// Set charset ke utf8mb4 untuk mendukung berbagai karakter
mysqli_set_charset($conn, "utf8mb4");

// Fungsi ini akan mengembalikan object koneksi
// agar bisa digunakan di file lain.
return $conn;
