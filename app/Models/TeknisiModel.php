<?php
// app/Models/TeknisiModel.php

class TeknisiModel {
    private $conn; // Properti untuk menyimpan object koneksi database

    /**
     * Constructor untuk "menyuntikkan" koneksi database ke dalam model.
     * @param mysqli $dbConnection Object koneksi database yang aktif.
     */
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    /**
     * Mengambil semua data teknisi dari database.
     * @return array Array berisi semua data teknisi, atau array kosong jika tidak ada data.
     */
    public function getAll($sortBy = 'nama_teknisi', $sortOrder = 'ASC') {
        // Whitelist untuk kolom yang diizinkan untuk sorting (demi keamanan)
        $allowedColumns = ['id_teknisi', 'nama_teknisi', 'kontak'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'nama_teknisi'; // Default jika kolom tidak valid
        }

        // Pastikan sort order hanya ASC atau DESC
        if (strtoupper($sortOrder) !== 'ASC' && strtoupper($sortOrder) !== 'DESC') {
            $sortOrder = 'ASC'; // Default jika order tidak valid
        }

        $sql = "SELECT id_teknisi, nama_teknisi, kontak FROM teknisi ORDER BY $sortBy $sortOrder";
        $result = mysqli_query($this->conn, $sql);
        
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Mengambil satu data teknisi berdasarkan ID-nya.
     * @param string $id ID teknisi yang dicari.
     * @return array|null Data teknisi jika ditemukan, atau null jika tidak.
     */
    public function getById($id) {
        $sql = "SELECT id_teknisi, nama_teknisi, kontak FROM teknisi WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Menyimpan data teknisi baru ke database.
     * @param array $data Data teknisi (harus berisi id_teknisi, nama_teknisi, kontak).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create($data) {
        $sql = "INSERT INTO teknisi (id_teknisi, nama_teknisi, kontak) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $data['id_teknisi'], $data['nama_teknisi'], $data['kontak']);
        
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Memperbarui data teknisi yang ada di database berdasarkan ID.
     * @param string $id ID teknisi yang akan diperbarui.
     * @param array $data Data baru untuk teknisi (nama_teknisi, kontak).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update($id, $data) {
        $sql = "UPDATE teknisi SET nama_teknisi = ?, kontak = ? WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $data['nama_teknisi'], $data['kontak'], $id);

        return mysqli_stmt_execute($stmt);
    }

    /**
     * Menghapus data teknisi dari database berdasarkan ID.
     * @param string $id ID teknisi yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete($id) {
        $sql = "DELETE FROM teknisi WHERE id_teknisi = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        
        return mysqli_stmt_execute($stmt);
    }
}
