<?php
// app/Models/UserModel.php

class UserModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    /**
     * Mengambil semua data user.
     * @return array
     */
    public function getAll() {
        $sql = "SELECT id_user, username, nama_lengkap, role FROM users ORDER BY nama_lengkap ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Mengambil satu data user berdasarkan ID.
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT id_user, username, nama_lengkap, role FROM users WHERE id_user = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Mencari user berdasarkan username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username) {
        $sql = "SELECT id_user, username, password_hash, nama_lengkap, role FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    /**
     * Membuat user baru.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $sql = "INSERT INTO users (username, password_hash, nama_lengkap, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", 
            $data['username'], 
            $data['password_hash'], 
            $data['nama_lengkap'], 
            $data['role']
        );
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Memperbarui data user. Jika password tidak diisi, password lama tidak berubah.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        // Cek apakah ada password baru
        if (!empty($data['password_hash'])) {
            // Jika ada, update dengan password baru
            $sql = "UPDATE users SET username = ?, nama_lengkap = ?, role = ?, password_hash = ? WHERE id_user = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi",
                $data['username'],
                $data['nama_lengkap'],
                $data['role'],
                $data['password_hash'],
                $id
            );
        } else {
            // Jika tidak ada, update tanpa mengubah password
            $sql = "UPDATE users SET username = ?, nama_lengkap = ?, role = ? WHERE id_user = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi",
                $data['username'],
                $data['nama_lengkap'],
                $data['role'],
                $id
            );
        }
        return mysqli_stmt_execute($stmt);
    }

    /**
     * Menghapus user.
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id_user = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
