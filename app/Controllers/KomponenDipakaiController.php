<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once 'app/Models/LogKomponenDipakaiModel.php';
require_once 'app/Models/LogMaintenanceModel.php';
require_once 'app/Models/LogKerusakanModel.php';
require_once 'app/Models/KomponenModel.php';

class KomponenDipakaiController {
    private $komponenDipakaiModel, $maintenanceModel, $kerusakanModel, $komponenModel;

    public function __construct($dbConnection) {
        $this->komponenDipakaiModel = new LogKomponenDipakaiModel($dbConnection);
        $this->maintenanceModel = new LogMaintenanceModel($dbConnection);
        $this->kerusakanModel = new LogKerusakanModel($dbConnection);
        $this->komponenModel = new KomponenModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $pageTitle = "Laporan Komponen Terpakai";
        $daftar_log_komponen = $this->komponenDipakaiModel->getAllUsedComponents();
        require_once 'public/views/laporan_komponen_dipakai.php';
    }

    public function create() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $pageTitle = "Input Pemakaian Komponen";
        $daftar_maintenance = $this->maintenanceModel->getAll();
        $daftar_kerusakan = $this->kerusakanModel->getAll();
        $daftar_komponen = $this->komponenModel->getAll(); 
        require_once 'public/views/form_komponen_dipakai.php';
    }

    public function store() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=dashboard");
            exit();
        }
        $log_type = $_POST['log_type'];
        $log_id = $_POST['log_id'];
        $data = [
            'id_log_maintenance' => ($log_type === 'maintenance') ? $log_id : null,
            'id_log_kerusakan' => ($log_type === 'kerusakan') ? $log_id : null,
            'id_komponen' => $_POST['id_komponen'],
            'jumlah_dipakai' => $_POST['jumlah_dipakai']
        ];
        $result = $this->komponenDipakaiModel->create($data);
        $_SESSION[$result['success'] ? 'success_message' : 'error_message'] = $result['message'];
        header("Location: index.php?page=laporan_komponen_dipakai");
        exit();
    }

    public function edit() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $id = $_GET['id'];
        $log = $this->komponenDipakaiModel->getById($id);
        if (!$log) {
            $_SESSION['error_message'] = "Data tidak ditemukan.";
            header("Location: index.php?page=laporan_komponen_dipakai");
            exit();
        }
        $pageTitle = "Edit Pemakaian Komponen";
        $daftar_maintenance = $this->maintenanceModel->getAll();
        $daftar_kerusakan = $this->kerusakanModel->getAll();
        $daftar_komponen = $this->komponenModel->getAll();
        require_once 'public/views/form_komponen_dipakai.php';
    }

    public function update() {
        if (!isset($_SESSION['is_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=dashboard");
            exit();
        }
        $id = $_POST['id'];
        $log_type = $_POST['log_type'];
        $log_id = $_POST['log_id'];
        $data = [
            'id_log_maintenance' => ($log_type === 'maintenance') ? $log_id : null,
            'id_log_kerusakan' => ($log_type === 'kerusakan') ? $log_id : null,
            'id_komponen' => $_POST['id_komponen'],
            'jumlah_dipakai' => $_POST['jumlah_dipakai']
        ];
        $result = $this->komponenDipakaiModel->update($id, $data);
        $_SESSION[$result['success'] ? 'success_message' : 'error_message'] = $result['message'];
        header("Location: index.php?page=laporan_komponen_dipakai");
        exit();
    }
    
    public function delete() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=laporan_komponen_dipakai");
            exit();
        }
        $id = $_GET['id'];
        if ($this->komponenDipakaiModel->delete($id)) {
            $_SESSION['success_message'] = "Log pemakaian komponen berhasil dihapus dan stok telah dikembalikan.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus log.";
        }
        header("Location: index.php?page=laporan_komponen_dipakai");
        exit();
    }

    public function exportToExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['ID Log', 'ID Laporan', 'Jenis Laporan', 'Tanggal', 'Jam', 'Lokasi CCTV', 'ID Komponen', 'Nama Komponen', 'Jumlah', 'Satuan', 'Harga Satuan (Rp)', 'Total Biaya (Rp)'];
        $sheet->fromArray($headers, NULL, 'A1');

        $data = $this->komponenDipakaiModel->getAllUsedComponents();
        
        $rowNumber = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row['id']);
            $sheet->setCellValue('B' . $rowNumber, $row['id_laporan_referensi']);
            $sheet->setCellValue('C' . $rowNumber, $row['jenis_laporan']);
            $sheet->setCellValue('D' . $rowNumber, $row['tanggal_laporan']);
            $sheet->setCellValue('E' . $rowNumber, $row['jam_laporan']);
            $sheet->setCellValue('F' . $rowNumber, $row['lokasi_cctv']);
            $sheet->setCellValue('G' . $rowNumber, $row['id_komponen']);
            $sheet->setCellValue('H' . $rowNumber, $row['nama_komponen']);
            $sheet->setCellValue('I' . $rowNumber, $row['jumlah_dipakai']);
            $sheet->setCellValue('J' . $rowNumber, $row['satuan']);
            $sheet->setCellValue('K' . $rowNumber, $row['harga_satuan']);
            $sheet->setCellValue('L' . $rowNumber, $row['biaya']);

            $sheet->getStyle('K' . $rowNumber)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('L' . $rowNumber)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            
            $rowNumber++;
        }
        
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        $headerStyle = [
            'font' => ['bold' => true,],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER,],
        ];
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
        $firstColumnRange = 'A2:A' . $lastRow;
        $sheet->getStyle($firstColumnRange)->applyFromArray($headerStyle);

        $tableRange = 'A1:' . $lastColumn . $lastRow;
        $table = new Table($tableRange, 'LaporanKomponenTable');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowFirstColumn(true);
        $table->setStyle($tableStyle);
        $sheet->addTable($table);

        $filename = 'laporan-komponen-dipakai-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}