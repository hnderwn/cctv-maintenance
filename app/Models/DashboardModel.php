<?php

class DashboardModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function countActiveCctv() {
        $sql = "SELECT COUNT(id_cctv) as total FROM cctv WHERE status = 'Aktif'";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['total'] ?? 0;
    }

    public function countProblemCctv() {
        $sql = "SELECT COUNT(id_cctv) as total FROM cctv WHERE status IN ('Perbaikan', 'Perawatan', 'Rusak')";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['total'] ?? 0;
    }

    public function countTotalTeknisi() {
        $sql = "SELECT COUNT(id_teknisi) as total FROM teknisi";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['total'] ?? 0;
    }
}