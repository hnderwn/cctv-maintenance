<?php

require_once '../app/Models/KomponenModel.php';

class KomponenController {
    private $komponenModel;

    public function __construct($dbConnection) {
        $this->komponenModel = new KomponenModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nama_komponen';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        
        $pageTitle = "Data Komponen";
        $daftar_komponen = $this->komponenModel->getAll($sortBy, $sortOrder);
        
        require_once 'views/data_komponen.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=komponen");
            exit();
        }

        $pageTitle = "Tambah Komponen Baru";
        require_once 'views/form_komponen.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=komponen");
            exit();
        }

        $data = [
            'id_komponen'   => $_POST['id_komponen'],
            'nama_komponen' => $_POST['nama_komponen'],
            'satuan'        => $_POST['satuan'],
            'stok'          => $_POST['stok'],
            'harga_satuan'  => $_POST['harga_satuan']
        ];
        
        if ($this->komponenModel->create($data)) {
            $_SESSION['success_message'] = "Data komponen berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan data komponen. Pastikan ID unik.";
        }
        
        header("Location: index.php?page=komponen");
        exit();
    }
    
    public function edit() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=komponen");
            exit();
        }

        $id = $_GET['id'];
        $komponen = $this->komponenModel->getById($id);
        
        if (!$komponen) {
            $_SESSION['error_message'] = "Data komponen tidak ditemukan.";
            header("Location: index.php?page=komponen");
            exit();
        }

        $pageTitle = "Edit Data Komponen";
        require_once 'views/form_komponen.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=komponen");
            exit();
        }
        
        $id = $_POST['id_komponen'];
        $data = [
            'nama_komponen' => $_POST['nama_komponen'],
            'satuan'        => $_POST['satuan'],
            'stok'          => $_POST['stok'],
            'harga_satuan'  => $_POST['harga_satuan']
        ];

        if ($this->komponenModel->update($id, $data)) {
            $_SESSION['success_message'] = "Data komponen berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data komponen.";
        }

        header("Location: index.php?page=komponen");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=komponen");
            exit();
        }

        $id = $_GET['id'];
        if ($this->komponenModel->delete($id)) {
            $_SESSION['success_message'] = "Data komponen berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data komponen.";
        }
        
        header("Location: index.php?page=komponen");
        exit();
    }
}