<?php
require_once __DIR__ . "/../model/UserModel.php";

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function loginForm() {
        include "view/auth/login.php";
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $user = $this->model->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ];
            header("Location: index.php?page=book");
            exit;
        }

        $error = "Email atau password salah.";
        include "view/auth/login.php";
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    public function registerForm() {
        include "view/auth/register.php";
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nama = trim(filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $role = 'anggota';

        if (!$nama || !$email || !$password || !$password_confirm) {
            $error = "Semua field harus diisi.";
            include "view/auth/register.php";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format email tidak valid.";
            include "view/auth/register.php";
            return;
        }

        if ($password !== $password_confirm) {
            $error = "Password dan konfirmasi tidak sama.";
            include "view/auth/register.php";
            return;
        }

        if (strlen($password) < 6) {
            $error = "Password minimal 6 karakter.";
            include "view/auth/register.php";
            return;
        }

        if ($this->model->getUserByEmail($email)) {
            $error = "Email sudah terdaftar.";
            include "view/auth/register.php";
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ok = $this->model->createUser($nama, $email, $hash, $role);

        if ($ok) {
            $_SESSION['success'] = "Registrasi berhasil, silakan login.";
            header("Location: index.php?page=login");
            exit;
        }

        $error = "Gagal mendaftar, silakan coba lagi.";
        include "view/auth/register.php";
    }
}
?>
