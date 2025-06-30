<?php
// app/Controllers/CctvUnitController.php

require_once '../app/Models/CctvUnitModel.php';
require_once '../app/Models/CctvTipeModel.php';

class CctvUnitController {
    private $unitModel;
    private $tipeModel;

    public function __construct($dbConnection) {
        $this->unitModel = new CctvUnitModel($dbConnection);
        $this->tipeModel = new CctvTipeModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        
        // Ambil parameter sorting dari URL
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'lokasi';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        $pageTitle = "Data Unit CCTV";
        // Ambil data dari Model dengan parameter sorting
        $daftar_cctv = $this->unitModel->getAll($sortBy, $sortOrder);

        // Kirim variabel sorting ke view
        require_once 'views/data_unit_cctv.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_unit"); exit(); }
        $pageTitle = "Tambah Unit CCTV Baru";
        $daftar_model = $this->tipeModel->getAll();
        require_once 'views/form_unit_cctv.php';
    }
    
    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=cctv_unit"); exit(); }
        $data = ['id_cctv' => $_POST['id_cctv'], 'lokasi' => $_POST['lokasi'], 'status' => $_POST['status'], 'id_model' => $_POST['id_model'], 'keterangan' => $_POST['keterangan']];
        if ($this->unitModel->create($data)) { $_SESSION['success_message'] = "Data CCTV berhasil ditambahkan!"; } else { $_SESSION['error_message'] = "Gagal menambahkan data CCTV. Pastikan ID unik."; }
        header("Location: index.php?page=cctv_unit"); exit();
    }
    public function edit() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_unit"); exit(); }
        $id = $_GET['id']; $cctv = $this->unitModel->getById($id);
        if (!$cctv) { $_SESSION['error_message'] = "Data CCTV tidak ditemukan."; header("Location: index.php?page=cctv_unit"); exit(); }
        $pageTitle = "Edit Unit CCTV"; $daftar_model = $this->tipeModel->getAll();
        require_once 'views/form_unit_cctv.php';
    }
    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=cctv_unit"); exit(); }
        $id = $_POST['id_cctv']; $data = ['lokasi' => $_POST['lokasi'], 'status' => $_POST['status'], 'id_model' => $_POST['id_model'], 'keterangan' => $_POST['keterangan']];
        if ($this->unitModel->update($id, $data)) { $_SESSION['success_message'] = "Data CCTV berhasil diperbarui!"; } else { $_SESSION['error_message'] = "Gagal memperbarui data CCTV."; }
        header("Location: index.php?page=cctv_unit"); exit();
    }
    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_unit"); exit(); }
        $id = $_GET['id'];
        if ($this->unitModel->delete($id)) { $_SESSION['success_message'] = "Data CCTV berhasil dihapus!"; } else { $_SESSION['error_message'] = "Gagal menghapus data CCTV."; }
        header("Location: index.php?page=cctv_unit"); exit();
    }
}
