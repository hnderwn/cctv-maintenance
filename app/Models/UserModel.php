<?php

class UserModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAll() {
        $sql = "SELECT id_user, username, nama_lengkap, role FROM users ORDER BY nama_lengkap ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT id_user, username, nama_lengkap, role FROM users WHERE id_user = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function findByUsername($username) {
        $sql = "SELECT id_user, username, password_hash, nama_lengkap, role FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
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

    public function update($id, $data) {
        if (!empty($data['password_hash'])) {
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

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id_user = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    public function resetPassword($id, $defaultPassword = '123456') {
        $new_password_hash = password_hash($defaultPassword, PASSWORD_BCRYPT);
        
        $sql = "UPDATE users SET password_hash = ? WHERE id_user = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $new_password_hash, $id);
        
        return mysqli_stmt_execute($stmt);
    }
}