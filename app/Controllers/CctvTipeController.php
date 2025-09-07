<?php

require_once 'app/Models/CctvTipeModel.php';

class CctvTipeController {
    private $tipeModel;

    public function __construct($dbConnection) {
        $this->tipeModel = new CctvTipeModel($dbConnection);
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
            header("Location: index.php?page=dashboard");
            exit();
        }
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nama_model';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        $daftar_model = $this->tipeModel->getAll($sortBy, $sortOrder);

        $pageTitle = "Data Tipe CCTV";
        require_once 'public/views/data_tipe_cctv.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $pageTitle = "Tambah Tipe CCTV Baru";
        require_once 'public/views/form_tipe_cctv.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $data = [
            'id_model' => $_POST['id_model'],
            'nama_model' => $_POST['nama_model'],
            'manufaktur' => $_POST['manufaktur'],
            'spesifikasi' => $_POST['spesifikasi'],
            'umur_ekonomis_th' => $_POST['umur_ekonomis_th']
        ];
        if ($this->tipeModel->create($data)) {
            $_SESSION['success_message'] = "Data tipe CCTV berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan data. Pastikan ID unik.";
        }
        header("Location: index.php?page=cctv_tipe");
        exit();
    }

    public function edit() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $id = $_GET['id'];
        $model = $this->tipeModel->getById($id);
        if (!$model) {
            $_SESSION['error_message'] = "Data tipe CCTV tidak ditemukan.";
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $pageTitle = "Edit Tipe CCTV";
        require_once 'public/views/form_tipe_cctv.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $id = $_POST['id_model'];
        $data = [
            'nama_model' => $_POST['nama_model'],
            'manufaktur' => $_POST['manufaktur'],
            'spesifikasi' => $_POST['spesifikasi'],
            'umur_ekonomis_th' => $_POST['umur_ekonomis_th']
        ];
        if ($this->tipeModel->update($id, $data)) {
            $_SESSION['success_message'] = "Data tipe CCTV berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data tipe CCTV.";
        }
        header("Location: index.php?page=cctv_tipe");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses.";
            header("Location: index.php?page=cctv_tipe");
            exit();
        }
        $id = $_GET['id'];
        if ($this->tipeModel->delete($id)) {
            $_SESSION['success_message'] = "Data tipe CCTV berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data. Pastikan tidak ada unit CCTV yang masih menggunakan model ini.";
        }
        header("Location: index.php?page=cctv_tipe");
        exit();
    }
}