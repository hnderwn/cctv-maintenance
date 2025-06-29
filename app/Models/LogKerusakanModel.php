<?php
// app/Models/LogKerusakanModel.php

class LogKerusakanModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAll() {
        // QUERY DIPERLENGKAP: tambahkan lk.id_cctv dan lk.id_teknisi
        $sql = "SELECT lk.id_log, lk.tanggal, lk.jam, lk.deskripsi_kerusakan, 
                       c.lokasi as cctv_lokasi, lk.id_cctv,
                       t.nama_teknisi, lk.id_teknisi
                FROM log_kerusakan lk
                LEFT JOIN cctv c ON lk.id_cctv = c.id_cctv
                LEFT JOIN teknisi t ON lk.id_teknisi = t.id_teknisi
                ORDER BY lk.tanggal DESC, lk.jam DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM log_kerusakan WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO log_kerusakan (id_cctv, id_teknisi, tanggal, jam, deskripsi_kerusakan) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        $id_teknisi = !empty($data['id_teknisi']) ? $data['id_teknisi'] : null;
        mysqli_stmt_bind_param($stmt, "sssss", 
            $data['id_cctv'], $id_teknisi, $data['tanggal'], $data['jam'], $data['deskripsi_kerusakan']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE log_kerusakan SET id_cctv = ?, id_teknisi = ?, tanggal = ?, jam = ?, deskripsi_kerusakan = ? WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        $id_teknisi = !empty($data['id_teknisi']) ? $data['id_teknisi'] : null;
        mysqli_stmt_bind_param($stmt, "sssssi",
            $data['id_cctv'], $id_teknisi, $data['tanggal'], $data['jam'], $data['deskripsi_kerusakan'], $id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $sql_komponen = "DELETE FROM log_komponen_dipakai WHERE id_log_kerusakan = ?";
        $stmt_komponen = mysqli_prepare($this->conn, $sql_komponen);
        mysqli_stmt_bind_param($stmt_komponen, "i", $id);
        mysqli_stmt_execute($stmt_komponen);

        $sql = "DELETE FROM log_kerusakan WHERE id_log = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
