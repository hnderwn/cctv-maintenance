<?php
require_once 'app/Models/UserModel.php';


class AuthController
{
    private $userModel;

    public function __construct($dbConnection)
    {
        $this->userModel = new UserModel($dbConnection);
    }

    /**
     * Menampilkan halaman/view untuk login.
     */
    public function login()
    {
        // Jika user sudah login, langsung tendang ke dashboard
        if (isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=dashboard");
            exit();
        }

        // Panggil file view untuk menampilkan form login
        require_once 'public/views/login.php';
    }

    /**
     * Memproses data yang dikirim dari form login.
     */
    public function prosesLogin()
    {
        // Pastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=login");
            exit();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Cari user di database melalui Model
        $user = $this->userModel->findByUsername($username);

        // Verifikasi user dan password (kompatibel dengan jBCrypt)
        if ($user && password_verify($password, $user['password_hash'])) {
            // Jika login sukses, buat session
            $_SESSION['is_logged_in'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];

            // Arahkan ke dashboard
            header("Location: index.php?page=dashboard");
            exit();
        } else {
            // Jika login gagal, buat pesan error dan kembali ke halaman login
            $_SESSION['login_error'] = "Username atau Password yang Anda masukkan salah!";
            header("Location: index.php?page=login");
            exit();
        }
    }

    /**
     * Menghapus sesi dan me-logout user.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}
