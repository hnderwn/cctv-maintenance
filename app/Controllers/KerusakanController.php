<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once 'app/Models/LogKerusakanModel.php';
require_once 'app/Models/TeknisiModel.php';
require_once 'app/Models/CctvUnitModel.php';

class KerusakanController {
    private $logKerusakanModel, $teknisiModel, $cctvUnitModel;

    public function __construct($dbConnection) {
        $this->logKerusakanModel = new LogKerusakanModel($dbConnection);
        $this->teknisiModel = new TeknisiModel($dbConnection);
        $this->cctvUnitModel = new CctvUnitModel($dbConnection);
    }
    
    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $pageTitle = "Laporan Kerusakan";
        $daftar_kerusakan = $this->logKerusakanModel->getAll();
        require_once 'public/views/laporan_kerusakan.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $pageTitle = "Tambah Laporan Kerusakan";
        $daftar_cctv = $this->cctvUnitModel->getAllForDropdown();
        $daftar_teknisi = $this->teknisiModel->getAll();
        require_once 'public/views/form_kerusakan.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=laporan_kerusakan");
            exit();
        }
        if ($this->logKerusakanModel->create($_POST)) {
            $_SESSION['success_message'] = "Laporan kerusakan berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan laporan.";
        }
        header("Location: index.php?page=laporan_kerusakan");
        exit();
    }
    
    public function edit() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
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
        require_once 'public/views/form_kerusakan.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=laporan_kerusakan");
            exit();
        }
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
        // PERBAIKAN DI SINI: Hapus pengecekan POST, tambahkan pengecekan role admin
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk aksi ini.";
            header("Location: index.php?page=laporan_kerusakan");
            exit();
        }

        $id = $_GET['id'];
        if ($this->logKerusakanModel->delete($id)) {
            $_SESSION['success_message'] = "Laporan berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus laporan.";
        }
        header("Location: index.php?page=laporan_kerusakan");
        exit();
    }

    public function exportToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['ID Log', 'Tanggal', 'Jam', 'ID CCTV', 'Lokasi CCTV', 'ID Teknisi', 'Nama Teknisi', 'Deskripsi Kerusakan'];
        $sheet->fromArray($headers, NULL, 'A1');

        $data = $this->logKerusakanModel->getAll();
        
        $rowNumber = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row['id_log']);
            $sheet->setCellValue('B' . $rowNumber, $row['tanggal']);
            $sheet->setCellValue('C' . $rowNumber, $row['jam']);
            $sheet->setCellValue('D' . $rowNumber, $row['id_cctv']);
            $sheet->setCellValue('E' . $rowNumber, $row['cctv_lokasi']);
            $sheet->setCellValue('F' . $rowNumber, $row['id_teknisi']);
            $sheet->setCellValue('G' . $rowNumber, $row['nama_teknisi']);
            $sheet->setCellValue('H' . $rowNumber, $row['deskripsi_kerusakan']);
            $rowNumber++;
        }

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

        $tableRange = 'A1:' . $lastColumn . $lastRow;
        $table = new Table($tableRange, 'LaporanKerusakanTable');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowFirstColumn(true);
        $table->setStyle($tableStyle);
        $sheet->addTable($table);

        $filename = 'laporan-kerusakan-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}