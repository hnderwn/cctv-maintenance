<?php
// app/Models/CctvTipeModel.php

// Nama class diubah menjadi CctvTipeModel
class CctvTipeModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAll() {
        $sql = "SELECT id_model, nama_model, manufaktur, spesifikasi, umur_ekonomis_th FROM cctv_models ORDER BY nama_model ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT id_model, nama_model, manufaktur, spesifikasi, umur_ekonomis_th FROM cctv_models WHERE id_model = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $sql = "INSERT INTO cctv_models (id_model, nama_model, manufaktur, spesifikasi, umur_ekonomis_th) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi",
            $data['id_model'],
            $data['nama_model'],
            $data['manufaktur'],
            $data['spesifikasi'],
            $data['umur_ekonomis_th']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $sql = "UPDATE cctv_models SET nama_model = ?, manufaktur = ?, spesifikasi = ?, umur_ekonomis_th = ? WHERE id_model = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssis",
            $data['nama_model'],
            $data['manufaktur'],
            $data['spesifikasi'],
            $data['umur_ekonomis_th'],
            $id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $sql = "DELETE FROM cctv_models WHERE id_model = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        return mysqli_stmt_execute($stmt);
    }
}
