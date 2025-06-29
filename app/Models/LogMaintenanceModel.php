<?php
// app/Models/LogMaintenanceModel.php

class LogMaintenanceModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAll() {
        // QUERY DIPERLENGKAP: tambahkan lm.id_cctv dan lm.id_teknisi
        $sql = "SELECT lm.id_log, lm.tanggal, lm.jam, lm.deskripsi_log, 
                       c.lokasi as cctv_lokasi, lm.id_cctv, 
                       t.nama_teknisi, lm.id_teknisi
                FROM log_maintenance lm
                JOIN cctv c ON lm.id_cctv = c.id_cctv
                JOIN teknisi t ON lm.id_teknisi = t.id_teknisi
                ORDER BY lm.tanggal DESC, lm.jam DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM log_maintenance WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO log_maintenance (id_cctv, id_teknisi, tanggal, jam, deskripsi_log) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", 
            $data['id_cctv'], $data['id_teknisi'], $data['tanggal'], $data['jam'], $data['deskripsi_log']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE log_maintenance SET id_cctv = ?, id_teknisi = ?, tanggal = ?, jam = ?, deskripsi_log = ? WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi",
            $data['id_cctv'], $data['id_teknisi'], $data['tanggal'], $data['jam'], $data['deskripsi_log'], $id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        // Hapus juga komponen yang terhubung dengan log ini
        $sql_komponen = "DELETE FROM log_komponen_dipakai WHERE id_log_maintenance = ?";
        $stmt_komponen = mysqli_prepare($this->conn, $sql_komponen);
        mysqli_stmt_bind_param($stmt_komponen, "i", $id);
        mysqli_stmt_execute($stmt_komponen);

        $sql = "DELETE FROM log_maintenance WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
