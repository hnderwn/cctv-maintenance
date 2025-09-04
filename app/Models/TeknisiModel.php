<?php

class TeknisiModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAll($sortBy = 'nama_teknisi', $sortOrder = 'ASC') {
        $allowedColumns = ['id_teknisi', 'nama_teknisi', 'kontak'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'nama_teknisi';
        }

        if (strtoupper($sortOrder) !== 'ASC' && strtoupper($sortOrder) !== 'DESC') {
            $sortOrder = 'ASC';
        }

        $sql = "SELECT id_teknisi, nama_teknisi, kontak FROM teknisi ORDER BY $sortBy $sortOrder";
        $result = mysqli_query($this->conn, $sql);
        
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT id_teknisi, nama_teknisi, kontak FROM teknisi WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO teknisi (id_teknisi, nama_teknisi, kontak) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $data['id_teknisi'], $data['nama_teknisi'], $data['kontak']);
        
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE teknisi SET nama_teknisi = ?, kontak = ? WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $data['nama_teknisi'], $data['kontak'], $id);

        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $sql = "DELETE FROM teknisi WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        
        return mysqli_stmt_execute($stmt);
    }
}