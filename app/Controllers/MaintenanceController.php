<?php
// app/Controllers/MaintenanceController.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../app/Models/LogMaintenanceModel.php';
require_once '../app/Models/TeknisiModel.php';
require_once '../app/Models/CctvUnitModel.php'; 
use PhpOffice\PhpSpreadsheet\Worksheet\Table;     
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        
        // Header detail sesuai preferensi lo
        $headers = ['ID Log', 'Tanggal', 'Jam', 'Deskripsi', 'Lokasi CCTV', 'ID CCTV', 'Nama Teknisi', 'ID Teknisi'];
        $sheet->fromArray($headers, NULL, 'A1');

        $data = $this->logMaintenanceModel->getAll();
        
        // Looping manual (cara aman) untuk memasukkan data
        $rowNumber = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row['id_log']);
            $sheet->setCellValue('B' . $rowNumber, $row['tanggal']);
            $sheet->setCellValue('C' . $rowNumber, $row['jam']);
            $sheet->setCellValue('D' . $rowNumber, $row['deskripsi_log']);
            $sheet->setCellValue('E' . $rowNumber, $row['cctv_lokasi']);
            $sheet->setCellValue('F' . $rowNumber, $row['id_cctv']);
            $sheet->setCellValue('G' . $rowNumber, $row['nama_teknisi']);
            $sheet->setCellValue('H' . $rowNumber, $row['id_teknisi']);
            $rowNumber++;
        }

        // --- STYLING PERSIS SEPERTI PREFERENSI LO ---
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

        $firstColumnRange = 'A2:A' . $lastRow;
        $sheet->getStyle($firstColumnRange)->applyFromArray($headerStyle);
        // --- AKHIR STYLING ---

        // --- FORMAT SEBAGAI TABEL ---
        $tableRange = 'A1:' . $lastColumn . $lastRow;
        $table = new Table($tableRange, 'LaporanMaintenanceTable');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowFirstColumn(true);
        $table->setStyle($tableStyle);
        $sheet->addTable($table);
        // --- AKHIR FORMAT TABEL ---

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