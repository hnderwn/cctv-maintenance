<?php
// app/Controllers/MaintenanceController.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../app/Models/LogMaintenanceModel.php';
require_once '../app/Models/TeknisiModel.php';
require_once '../app/Models/CctvUnitModel.php'; 

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
    
    /**
     * FUNGSI BARU: Untuk handle export data ke Excel
     */
        public function exportToExcel() {
        // 1. Buat object spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // 2. Buat header untuk tabel di Excel
        $sheet->setCellValue('A1', 'ID Log');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Jam');
        $sheet->setCellValue('D1', 'Lokasi CCTV');
        $sheet->setCellValue('E1', 'Nama Teknisi');
        $sheet->setCellValue('F1', 'Deskripsi');

        // 3. Ambil data dari database
        $data = $this->logMaintenanceModel->getAll();

        // 4. Masukkan data ke dalam sheet Excel
        $row = 2; // Mulai dari baris kedua
        foreach ($data as $log) {
            $sheet->setCellValue('A' . $row, $log['id_log']);
            $sheet->setCellValue('B' . $row, $log['tanggal']);
            $sheet->setCellValue('C' . $row, $log['jam']);
            $sheet->setCellValue('D' . $row, $log['cctv_lokasi']);
            $sheet->setCellValue('E' . $row, $log['nama_teknisi']);
            $sheet->setCellValue('F' . $row, $log['deskripsi_log']);
            $row++;
        }

        // 5. Atur header HTTP untuk memicu download
        $filename = 'laporan-maintenance-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // 6. Buat file Excel dan kirim ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

}