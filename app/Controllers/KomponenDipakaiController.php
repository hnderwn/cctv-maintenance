<?php
// app/Controllers/KomponenDipakaiController.php

require_once '../app/Models/LogKomponenDipakaiModel.php';
require_once '../app/Models/LogMaintenanceModel.php';
require_once '../app/Models/LogKerusakanModel.php';
require_once '../app/Models/KomponenModel.php';

class KomponenDipakaiController {
    private $komponenDipakaiModel, $maintenanceModel, $kerusakanModel, $komponenModel;

    public function __construct($dbConnection) {
        $this->komponenDipakaiModel = new LogKomponenDipakaiModel($dbConnection);
        $this->maintenanceModel = new LogMaintenanceModel($dbConnection);
        $this->kerusakanModel = new LogKerusakanModel($dbConnection);
        $this->komponenModel = new KomponenModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Laporan Komponen Terpakai";
        $daftar_log_komponen = $this->komponenDipakaiModel->getAllUsedComponents();
        require_once 'views/laporan_komponen_dipakai.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Input Pemakaian Komponen";
        $daftar_maintenance = $this->maintenanceModel->getAll();
        $daftar_kerusakan = $this->kerusakanModel->getAll();
        // DIUBAH: Panggil getAll() biar dapet info stok
        $daftar_komponen = $this->komponenModel->getAll(); 
        require_once 'views/form_komponen_dipakai.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=dashboard"); exit(); }
        $log_type = $_POST['log_type']; $log_id = $_POST['log_id'];
        $data = [ 'id_log_maintenance' => ($log_type === 'maintenance') ? $log_id : null, 'id_log_kerusakan' => ($log_type === 'kerusakan') ? $log_id : null, 'id_komponen' => $_POST['id_komponen'], 'jumlah_dipakai' => $_POST['jumlah_dipakai'] ];
        $result = $this->komponenDipakaiModel->create($data);
        $_SESSION[$result['success'] ? 'success_message' : 'error_message'] = $result['message'];
        header("Location: index.php?page=laporan_komponen_dipakai"); exit();
    }

    public function edit() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $id = $_GET['id'];
        $log = $this->komponenDipakaiModel->getById($id);
        if (!$log) {
            $_SESSION['error_message'] = "Data tidak ditemukan.";
            header("Location: index.php?page=laporan_komponen_dipakai"); exit();
        }
        $pageTitle = "Edit Pemakaian Komponen";
        $daftar_maintenance = $this->maintenanceModel->getAll();
        $daftar_kerusakan = $this->kerusakanModel->getAll();
        // DIUBAH: Panggil getAll() biar dapet info stok
        $daftar_komponen = $this->komponenModel->getAll();
        require_once 'views/form_komponen_dipakai.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=dashboard"); exit(); }
        $id = $_POST['id'];
        $log_type = $_POST['log_type']; $log_id = $_POST['log_id'];
        $data = [ 'id_log_maintenance' => ($log_type === 'maintenance') ? $log_id : null, 'id_log_kerusakan' => ($log_type === 'kerusakan') ? $log_id : null, 'id_komponen' => $_POST['id_komponen'], 'jumlah_dipakai' => $_POST['jumlah_dipakai'] ];
        $result = $this->komponenDipakaiModel->update($id, $data);
        $_SESSION[$result['success'] ? 'success_message' : 'error_message'] = $result['message'];
        header("Location: index.php?page=laporan_komponen_dipakai"); exit();
    }
    
    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') { header("Location: index.php?page=laporan_komponen_dipakai"); exit(); }
        $id = $_GET['id'];
        if ($this->komponenDipakaiModel->delete($id)) { $_SESSION['success_message'] = "Log pemakaian komponen berhasil dihapus dan stok telah dikembalikan."; } 
        else { $_SESSION['error_message'] = "Gagal menghapus log."; }
        header("Location: index.php?page=laporan_komponen_dipakai"); exit();
    }
}