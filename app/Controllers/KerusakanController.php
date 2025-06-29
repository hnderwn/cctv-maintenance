<?php
// app/Controllers/KerusakanController.php

require_once '../app/Models/LogKerusakanModel.php';
require_once '../app/Models/TeknisiModel.php';
require_once '../app/Models/CctvUnitModel.php';

class KerusakanController {
    private $logKerusakanModel, $teknisiModel, $cctvUnitModel;

    public function __construct($dbConnection) {
        $this->logKerusakanModel = new LogKerusakanModel($dbConnection);
        $this->teknisiModel = new TeknisiModel($dbConnection);
        $this->cctvUnitModel = new CctvUnitModel($dbConnection);
    }
    
    public function index() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Laporan Kerusakan";
        $daftar_kerusakan = $this->logKerusakanModel->getAll();
        require_once 'views/laporan_kerusakan.php';
    }

    // FUNGSI BARU: Menampilkan form tambah data
    public function create() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Tambah Laporan Kerusakan";
        $daftar_cctv = $this->cctvUnitModel->getAllForDropdown();
        $daftar_teknisi = $this->teknisiModel->getAll();
        require_once 'views/form_kerusakan.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_kerusakan"); exit(); }
        if ($this->logKerusakanModel->create($_POST)) {
            $_SESSION['success_message'] = "Laporan kerusakan berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan laporan.";
        }
        header("Location: index.php?page=laporan_kerusakan");
        exit();
    }
    
    // FUNGSI BARU: Menampilkan form edit data
    public function edit() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $id = $_GET['id'];
        $log = $this->logKerusakanModel->getById($id);
        if (!$log) {
            $_SESSION['error_message'] = "Laporan tidak ditemukan.";
            header("Location: index.php?page=laporan_kerusakan");
            exit();
        }
        $pageTitle = "Edit Laporan Kerusakan";
        $daftar_cctv = $this->cctvUnitModel->getAllForDropdown();
        $daftar_teknisi = $this->teknisiModel->getAll();
        require_once 'views/form_kerusakan.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_kerusakan"); exit(); }
        $id = $_POST['id_log'];
        if ($this->logKerusakanModel->update($id, $_POST)) {
            $_SESSION['success_message'] = "Laporan berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui laporan.";
        }
        header("Location: index.php?page=laporan_kerusakan");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_kerusakan"); exit(); }
        $id = $_GET['id'];
        if ($this->logKerusakanModel->delete($id)) {
            $_SESSION['success_message'] = "Laporan berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus laporan.";
        }
        header("Location: index.php?page=laporan_kerusakan");
        exit();
    }
}