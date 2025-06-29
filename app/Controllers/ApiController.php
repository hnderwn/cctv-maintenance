<?php
// app/Controllers/ApiController.php

require_once '../app/Models/LogKomponenDipakaiModel.php';
require_once '../app/Models/LogMaintenanceModel.php';
require_once '../app/Models/LogKerusakanModel.php';

class ApiController {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        header('Content-Type: application/json'); // Set header agar browser tahu ini JSON
    }

    // Fungsi untuk mengambil detail komponen yang dipakai (Modal Detail)
    public function getLogDetails() {
        if (!isset($_GET['id']) || !isset($_GET['type'])) { echo json_encode(['error' => 'Parameter tidak lengkap.']); exit(); }
        $log_id = intval($_GET['id']);
        $log_type = $_GET['type'];
        $model = new LogKomponenDipakaiModel($this->conn);
        $details = $model->getForDetailModal($log_id, $log_type);
        echo json_encode($details);
        exit();
    }

    // Fungsi BARU untuk mengambil data log untuk form EDIT
    public function getLogById() {
        if (!isset($_GET['id']) || !isset($_GET['type'])) { echo json_encode(['error' => 'Parameter tidak lengkap.']); exit(); }
        $log_id = intval($_GET['id']);
        $log_type = $_GET['type'];
        $data = null;
        if ($log_type === 'maintenance') {
            $model = new LogMaintenanceModel($this->conn);
            $data = $model->getById($log_id);
        } elseif ($log_type === 'kerusakan') {
            $model = new LogKerusakanModel($this->conn);
            $data = $model->getById($log_id);
        }
        echo json_encode($data);
        exit();
    }
}
