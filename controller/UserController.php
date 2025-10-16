<?php
require_once __DIR__ . "/../model/UserModel.php";

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
        if (session_status() === PHP_SESSION_NONE) session_start();

        //  Batasi akses hanya untuk admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "<script>alert('⚠ Akses ditolak. Hanya admin yang dapat mengelola anggota.');window.location='index.php?page=book';</script>";
            exit;
        }
    }

    // Tampilkan daftar anggota
    public function index() {
        $users = $this->model->getAllUsers();
        include "view/user/index.php";
    }

    //  Form tambah anggota
    public function createForm() {
        $user = null;
        include "view/user/form.php";
    }

    // Simpan anggota baru
    public function store() {
        $nama = trim(filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'anggota';

        if (empty($nama) || empty($email) || empty($password)) {
            echo "<script>alert('❌ Semua field wajib diisi.');history.back();</script>";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('❌ Format email tidak valid.');history.back();</script>";
            return;
        }

        if ($this->model->getUserByEmail($email)) {
            echo "<script>alert('❌ Email sudah digunakan!');history.back();</script>";
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ok = $this->model->createUser($nama, $email, $hash, $role);

        if ($ok) {
            header("Location: index.php?page=user");
            exit;
        }

        echo "<script>alert('❌ Gagal menambah user.');history.back();</script>";
    }

    // Form edit anggota
    public function editForm($id) {
        $id = (int)$id;
        $user = $this->model->getUserById($id);
        if (!$user) {
            echo "<script>alert('❌ Data user tidak ditemukan.');window.location='index.php?page=user';</script>";
            return;
        }
        include "view/user/form.php";
    }

    //  Update anggota
    public function update($id) {
        $id = (int)$id;
        $nama = trim(filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $role = $_POST['role'] ?? 'anggota';

        if (empty($nama) || empty($email)) {
            echo "<script>alert('❌ Semua field wajib diisi.');history.back();</script>";
            return;
        }

        $ok = $this->model->updateUser($id, $nama, $email, $role);
        if ($ok) {
            header("Location: index.php?page=user");
            exit;
        }

        echo "<script>alert('❌ Gagal memperbarui user.');history.back();</script>";
    }

    // Hapus anggota
    public function delete($id) {
        $id = (int)$id;
        if ($this->model->deleteUser($id)) {
            echo "<script>alert('✅ User berhasil dihapus.');window.location='index.php?page=user';</script>";
        } else {
            echo "<script>alert('❌ Gagal menghapus user.');history.back();</script>";
        }
    }
}
?>
