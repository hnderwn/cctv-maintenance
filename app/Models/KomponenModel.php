<?php
// app/Models/KomponenModel.php

class KomponenModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    /**
     * Mengambil semua data komponen untuk dropdown.
     * @return array
     */
    public function getAllForDropdown() {
        $sql = "SELECT id_komponen, nama_komponen FROM komponen ORDER BY nama_komponen ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Mengambil semua data komponen untuk tabel laporan.
     * @return array
     */
    public function getAll() {
        $sql = "SELECT id_komponen, nama_komponen, satuan, stok, harga_satuan FROM komponen ORDER BY nama_komponen ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Mengambil satu data komponen berdasarkan ID.
     * @param string $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT id_komponen, nama_komponen, satuan, stok, harga_satuan FROM komponen WHERE id_komponen = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Membuat data komponen baru.
     * @param array $data
     * @return bool
     */
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

    /**
     * Memperbarui data komponen.
     * @param string $id
     * @param array $data
     * @return bool
     */
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

    /**
     * Menghapus data komponen.
     * @param string $id
     * @return bool
     */
    public function delete($id) {
        $sql = "DELETE FROM komponen WHERE id_komponen = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        return mysqli_stmt_execute($stmt);
    }
}
