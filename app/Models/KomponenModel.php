<?php

class KomponenModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllForDropdown() {
        $sql = "SELECT id_komponen, nama_komponen FROM komponen ORDER BY nama_komponen ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getAll($sortBy = 'nama_komponen', $sortOrder = 'ASC') {
        $allowedColumns = ['id_komponen', 'nama_komponen', 'satuan', 'stok', 'harga_satuan'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'nama_komponen';
        }

        if (strtoupper($sortOrder) !== 'ASC' && strtoupper($sortOrder) !== 'DESC') {
            $sortOrder = 'ASC';
        }

        $sql = "SELECT id_komponen, nama_komponen, satuan, stok, harga_satuan FROM komponen ORDER BY $sortBy $sortOrder";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT id_komponen, nama_komponen, satuan, stok, harga_satuan FROM komponen WHERE id_komponen = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO komponen (id_komponen, nama_komponen, satuan, stok, harga_satuan) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssid",
            $data['id_komponen'],
            $data['nama_komponen'],
            $data['satuan'],
            $data['stok'],
            $data['harga_satuan']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE komponen SET nama_komponen = ?, satuan = ?, stok = ?, harga_satuan = ? WHERE id_komponen = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssids",
            $data['nama_komponen'],
            $data['satuan'],
            $data['stok'],
            $data['harga_satuan'],
            $id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $sql = "DELETE FROM komponen WHERE id_komponen = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        return mysqli_stmt_execute($stmt);
    }
}