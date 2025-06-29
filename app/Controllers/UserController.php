<?php
// app/Controllers/UserController.php

require_once '../app/Models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection);
        // Pastikan hanya admin yang bisa mengakses controller ini
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
            header("Location: index.php?page=dashboard");
            exit();
        }
    }

    public function index() {
        $pageTitle = "Manajemen User";
        $daftar_user = $this->userModel->getAll();
        require_once 'views/laporan_user.php';
    }

    public function create() {
        $pageTitle = "Tambah User Baru";
        require_once 'views/form_user.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user");
            exit();
        }

        $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $data = [
            'username'      => $_POST['username'],
            'nama_lengkap'  => $_POST['nama_lengkap'],
            'role'          => $_POST['role'],
            'password_hash' => $password_hash,
        ];
        
        if ($this->userModel->create($data)) {
            $_SESSION['success_message'] = "User baru berhasil ditambahkan!";
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan user. Username mungkin sudah ada.";
        }
        
        header("Location: index.php?page=user");
        exit();
    }
    
    public function edit() {
        $id = $_GET['id'];
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = "User tidak ditemukan.";
            header("Location: index.php?page=user");
            exit();
        }

        $pageTitle = "Edit User";
        require_once 'views/form_user.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user");
            exit();
        }
        
        $id = $_POST['id_user'];
        $data = [
            'username'     => $_POST['username'],
            'nama_lengkap' => $_POST['nama_lengkap'],
            'role'         => $_POST['role'],
        ];

        // Cek jika admin memasukkan password baru
        if (!empty($_POST['password'])) {
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        if ($this->userModel->update($id, $data)) {
            $_SESSION['success_message'] = "Data user berhasil diperbarui!";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data user.";
        }

        header("Location: index.php?page=user");
        exit();
    }

    public function delete() {
        $id = $_GET['id'];
        // Mencegah admin menghapus dirinya sendiri
        if ($id == $_SESSION['id_user']) {
            $_SESSION['error_message'] = "Anda tidak bisa menghapus akun Anda sendiri!";
            header("Location: index.php?page=user");
            exit();
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success_message'] = "User berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus user.";
        }
        
        header("Location: index.php?page=user");
        exit();
    }
}
