<?php
// app/Controllers/TeknisiController.php

// Panggil Model yang dibutuhkan
require_once '../app/Models/TeknisiModel.php';

class TeknisiController {
    private $teknisiModel;

    public function __construct($dbConnection) {
        $this->teknisiModel = new TeknisiModel($dbConnection);
    }

    /**
     * Menampilkan halaman utama (laporan) Teknisi.
     */
    public function index() {
        // Proteksi Halaman
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }

        // 1. Ambil parameter sorting dari URL, atau gunakan default
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nama_teknisi';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        // 2. Ambil semua data teknisi dari Model dengan parameter sorting
        $daftar_teknisi = $this->teknisiModel->getAll($sortBy, $sortOrder);

        // 3. Kirim variabel sorting ke view untuk membuat link dinamis
        $pageTitle = "Laporan Data Teknisi";
        
        // Panggil file view dan kirimkan semua data yang dibutuhkan
        require_once 'views/data_teknisi.php';
    }

    /**
     * Menampilkan halaman form untuk menambah teknisi baru.
     * Hanya bisa diakses oleh Admin.
     */
    public function create() {
        // Proteksi Role & Halaman
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
            header("Location: index.php?page=dashboard");
            exit();
        }

        $pageTitle = "Tambah Teknisi Baru";
        require_once 'views/form_teknisi.php';
    }

    /**
     * Menyimpan data teknisi baru dari form ke database.
     * Hanya bisa diakses oleh Admin.
     */
    public function store() {
        // Proteksi Role & Halaman
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=teknisi");
            exit();
        }

        // Ambil data dari POST
        $data = [
            'id_teknisi' => $_POST['id_teknisi'],
            'nama_teknisi' => $_POST['nama_teknisi'],
            'kontak' => $_POST['kontak']
        ];
        
        if ($this->teknisiModel->create($data)) {
            $_SESSION['success_message'] = "Data teknisi berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan data teknisi.";
        }
        
        header("Location: index.php?page=teknisi");
        exit();
    }
    
    /**
     * Menampilkan halaman form untuk mengedit data teknisi.
     * Hanya bisa diakses oleh Admin.
     */
    public function edit() {
        // Proteksi Role & Halaman
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=teknisi");
            exit();
        }

        $id = $_GET['id'];
        $teknisi = $this->teknisiModel->getById($id);

        if (!$teknisi) {
            $_SESSION['error_message'] = "Data teknisi tidak ditemukan.";
            header("Location: index.php?page=teknisi");
            exit();
        }

        $pageTitle = "Edit Data Teknisi";
        require_once 'views/form_teknisi.php';
    }

    /**
     * Memperbarui data teknisi di database.
     * Hanya bisa diakses oleh Admin.
     */
    public function update() {
         // Proteksi Role & Halaman
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=teknisi");
            exit();
        }
        
        $id = $_POST['id_teknisi'];
        $data = [
            'nama_teknisi' => $_POST['nama_teknisi'],
            'kontak' => $_POST['kontak']
        ];

        if ($this->teknisiModel->update($id, $data)) {
            $_SESSION['success_message'] = "Data teknisi berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data teknisi.";
        }

        header("Location: index.php?page=teknisi");
        exit();
    }

    /**
     * Menghapus data teknisi dari database.
     * Hanya bisa diakses oleh Admin.
     */
    public function delete() {
        // Proteksi Role & Halaman
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=teknisi");
            exit();
        }

        $id = $_GET['id'];
        if ($this->teknisiModel->delete($id)) {
            $_SESSION['success_message'] = "Data teknisi berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data teknisi.";
        }
        
        header("Location: index.php?page=teknisi");
        exit();
    }
}
