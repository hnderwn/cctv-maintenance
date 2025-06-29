<?php
// app/Controllers/MaintenanceController.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../app/Models/LogMaintenanceModel.php';
require_once '../app/Models/TeknisiModel.php';
require_once '../app/Models/CctvUnitModel.php'; 
use PhpOffice\PhpSpreadsheet\Worksheet\Table;     
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

class MaintenanceController {
    private $logMaintenanceModel, $teknisiModel, $cctvUnitModel;

    public function __construct($dbConnection) {
        $this->logMaintenanceModel = new LogMaintenanceModel($dbConnection);
        $this->teknisiModel = new TeknisiModel($dbConnection);
        $this->cctvUnitModel = new CctvUnitModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Laporan Maintenance";
        $daftar_maintenance = $this->logMaintenanceModel->getAll();
        require_once 'views/laporan_maintenance.php';
    }
    
    // FUNGSI BARU: Menampilkan form tambah data
    public function create() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $pageTitle = "Tambah Laporan Maintenance";
        // Ambil data untuk dropdown
        $daftar_cctv = $this->cctvUnitModel->getAllForDropdown();
        $daftar_teknisi = $this->teknisiModel->getAll();
        require_once 'views/form_maintenance.php';
    }

    public function store() { 
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_maintenance"); exit(); }
        if ($this->logMaintenanceModel->create($_POST)) {
            $_SESSION['success_message'] = "Laporan maintenance berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan laporan.";
        }
        header("Location: index.php?page=laporan_maintenance");
        exit();
    }

    // FUNGSI BARU: Menampilkan form edit data
    public function edit() {
        if (!isset($_SESSION['is_logged_in'])) { header("Location: index.php?page=login"); exit(); }
        $id = $_GET['id'];
        $log = $this->logMaintenanceModel->getById($id);
        if (!$log) {
            $_SESSION['error_message'] = "Laporan tidak ditemukan.";
            header("Location: index.php?page=laporan_maintenance");
            exit();
        }
        $pageTitle = "Edit Laporan Maintenance";
        // Ambil data untuk dropdown
        $daftar_cctv = $this->cctvUnitModel->getAllForDropdown();
        $daftar_teknisi = $this->teknisiModel->getAll();
        require_once 'views/form_maintenance.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_maintenance"); exit(); }
        $id = $_POST['id_log'];
        if ($this->logMaintenanceModel->update($id, $_POST)) {
            $_SESSION['success_message'] = "Laporan berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui laporan.";
        }
        header("Location: index.php?page=laporan_maintenance");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=laporan_maintenance"); exit(); }
        $id = $_GET['id'];
        if ($this->logMaintenanceModel->delete($id)) {
            $_SESSION['success_message'] = "Laporan berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus laporan.";
        }
        header("Location: index.php?page=laporan_maintenance");
        exit();
    }
    
    public function exportToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Buat header
        $headers = ['ID Log', 'Tanggal', 'Jam', 'Lokasi CCTV', 'Nama Teknisi', 'Deskripsi'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Ambil data
        $data = $this->logMaintenanceModel->getAll();
        // Masukkan data ke sheet mulai dari baris 2
        $sheet->fromArray($data, NULL, 'A2');

        // --- BAGIAN BARU UNTUK FORMAT SEBAGAI TABEL ---
        // 1. Tentukan range tabel (dari A1 sampai kolom terakhir di baris terakhir)
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        $tableRange = 'A1:' . $lastColumn . $lastRow;

        // 2. Buat objek Tabel
        $table = new Table($tableRange, 'LaporanMaintenanceTable');

        // 3. Buat dan terapkan style tabel
        $tableStyle = new TableStyle();
        // TABLE_STYLE_MEDIUM9 adalah style bawaan dengan warna biru-abu
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowFirstColumn(true);
        
        $table->setStyle($tableStyle);

        // 4. Tambahkan objek tabel ke worksheet
        $sheet->addTable($table);
        // --- AKHIR BAGIAN BARU ---

        // Atur header HTTP untuk download
        $filename = 'laporan-maintenance-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }


}