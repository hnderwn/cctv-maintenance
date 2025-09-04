<?php

require_once '../app/Models/TeknisiModel.php';

class TeknisiController {
    private $teknisiModel;

    public function __construct($dbConnection) {
        $this->teknisiModel = new TeknisiModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }

        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nama_teknisi';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        $daftar_teknisi = $this->teknisiModel->getAll($sortBy, $sortOrder);

        $pageTitle = "Laporan Data Teknisi";
        
        require_once 'views/data_teknisi.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
            header("Location: index.php?page=dashboard");
            exit();
        }

        $pageTitle = "Tambah Teknisi Baru";
        require_once 'views/form_teknisi.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=teknisi");
            exit();
        }

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
    
    public function edit() {
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

    public function update() {
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

    public function delete() {
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