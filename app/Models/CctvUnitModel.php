<?php

class CctvUnitModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    public function getAllForDropdown() {
        $sql = "SELECT id_cctv, lokasi FROM cctv ORDER BY lokasi ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getAll($sortBy = 'lokasi', $sortOrder = 'ASC') {
        $allowedColumns = ['c.id_cctv', 'c.lokasi', 'c.status', 'cm.nama_model'];
        $sortColumnMap = [
            'id_cctv' => 'c.id_cctv',
            'lokasi' => 'c.lokasi',
            'status' => 'c.status',
            'nama_model' => 'cm.nama_model'
        ];

        $dbSortColumn = isset($sortColumnMap[$sortBy]) ? $sortColumnMap[$sortBy] : 'c.lokasi';

        if (strtoupper($sortOrder) !== 'ASC' && strtoupper($sortOrder) !== 'DESC') {
            $sortOrder = 'ASC';
        }

        $sql = "SELECT c.id_cctv, c.lokasi, c.status, c.keterangan, c.id_model, 
                         cm.nama_model, cm.manufaktur, cm.spesifikasi
                 FROM cctv c
                 JOIN cctv_models cm ON c.id_model = cm.id_model
                 ORDER BY $dbSortColumn $sortOrder";
        
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT id_cctv, lokasi, status, id_model, keterangan FROM cctv WHERE id_cctv = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO cctv (id_cctv, lokasi, status, id_model, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", 
            $data['id_cctv'], $data['lokasi'], $data['status'], $data['id_model'], $data['keterangan']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE cctv SET lokasi = ?, status = ?, id_model = ?, keterangan = ? WHERE id_cctv = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss",
            $data['lokasi'], $data['status'], $data['id_model'], $data['keterangan'], $id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $sql = "DELETE FROM cctv WHERE id_cctv = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        return mysqli_stmt_execute($stmt);
    }
}