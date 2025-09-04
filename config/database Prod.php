<?php
// config/database.php

// --- Konfigurasi Database ---
define('DB_HOST', 'sql104.infinityfree.com');
define('DB_USER', 'if0_39358972');
define('DB_PASS', 'omfanke73');
define('DB_NAME', 'if0_39358972_db_cctv_maintenance');

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
