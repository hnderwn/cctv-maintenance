<?php
// app/Controllers/CctvTipeController.php

// Panggil model dengan nama baru
require_once '../app/Models/CctvTipeModel.php';

// Nama class diubah menjadi CctvTipeController
class CctvTipeController {
    private $tipeModel;

    public function __construct($dbConnection) {
        // Buat instance dari class dengan nama baru
        $this->tipeModel = new CctvTipeModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Data Tipe CCTV";
        $daftar_model = $this->tipeModel->getAll();
        // Panggil view dengan nama baru
        require_once 'views/data_tipe_cctv.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_tipe"); exit(); }
        $pageTitle = "Tambah Tipe CCTV Baru";
        // Panggil view dengan nama baru
        require_once 'views/form_tipe_cctv.php';
    }

    // ... (Fungsi store, edit, update, delete tidak ada perubahan logika, hanya pemanggilan view) ...
    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=cctv_tipe"); exit(); }
        $data = ['id_model' => $_POST['id_model'], 'nama_model' => $_POST['nama_model'], 'manufaktur' => $_POST['manufaktur'], 'spesifikasi' => $_POST['spesifikasi'], 'umur_ekonomis_th' => $_POST['umur_ekonomis_th']];
        if ($this->tipeModel->create($data)) { $_SESSION['success_message'] = "Data tipe CCTV berhasil ditambahkan!"; } else { $_SESSION['error_message'] = "Gagal menambahkan data. Pastikan ID unik."; }
        header("Location: index.php?page=cctv_tipe"); exit();
    }
    public function edit() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_tipe"); exit(); }
        $id = $_GET['id']; $model = $this->tipeModel->getById($id);
        if (!$model) { $_SESSION['error_message'] = "Data tipe CCTV tidak ditemukan."; header("Location: index.php?page=cctv_tipe"); exit(); }
        $pageTitle = "Edit Tipe CCTV";
        require_once 'views/form_tipe_cctv.php';
    }
    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=cctv_tipe"); exit(); }
        $id = $_POST['id_model']; $data = ['nama_model' => $_POST['nama_model'], 'manufaktur' => $_POST['manufaktur'], 'spesifikasi' => $_POST['spesifikasi'], 'umur_ekonomis_th' => $_POST['umur_ekonomis_th']];
        if ($this->tipeModel->update($id, $data)) { $_SESSION['success_message'] = "Data tipe CCTV berhasil diperbarui!"; } else { $_SESSION['error_message'] = "Gagal memperbarui data tipe CCTV."; }
        header("Location: index.php?page=cctv_tipe"); exit();
    }
    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { $_SESSION['error_message'] = "Anda tidak memiliki hak akses."; header("Location: index.php?page=cctv_tipe"); exit(); }
        $id = $_GET['id'];
        if ($this->tipeModel->delete($id)) { $_SESSION['success_message'] = "Data tipe CCTV berhasil dihapus!"; } else { $_SESSION['error_message'] = "Gagal menghapus data. Pastikan tidak ada unit CCTV yang masih menggunakan model ini."; }
        header("Location: index.php?page=cctv_tipe"); exit();
    }
}
