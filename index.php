<?php
session_start();

/*
    LOAD SEMUA CONTROLLER */
require_once __DIR__ . "/controller/AuthController.php";
require_once __DIR__ . "/controller/BookController.php";
require_once __DIR__ . "/controller/BorrowController.php";
require_once __DIR__ . "/controller/UserController.php";

/* 
   HELPER FUNCTION UNTUK PROTEKSI LOGIN & ROLE
 */
function requireLogin($role = null) {
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit;
    }

    if ($role && $_SESSION['user']['role'] !== $role) {
        echo "<p style='color:red;'>⚠ Akses ditolak. Hanya {$role} yang dapat mengakses halaman ini.</p>";
        exit;
    }
}

/* 
    ROUTING UTAMA
 */
$page = $_GET['page'] ?? 'login';

switch ($page) {

    /* 
       AUTHENTICATION
    */
    case 'register':
        (new AuthController())->registerForm();
        break;

    case 'register_action':
        (new AuthController())->register();
        break;

    case 'login':
        (new AuthController())->loginForm();
        break;

    case 'login_action':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    /* 
        CRUD BUKU
    */
    case 'book':
        requireLogin();
        (new BookController())->index();
        break;

    case 'book_store':
        requireLogin('admin');
        (new BookController())->store();
        break;

    case 'book_delete':
        requireLogin('admin');
        (new BookController())->delete($_GET['id'] ?? null);
        break;

    case 'book_edit':
        requireLogin('admin');
        (new BookController())->edit($_GET['id'] ?? null);
        break;

    case 'book_update':
        requireLogin('admin');
        (new BookController())->update($_GET['id'] ?? null);
        break;

    /* 
        PEMINJAMAN / PENGEMBALIAN
    */
    case 'borrow':
        requireLogin('anggota');
        (new BorrowController())->borrow($_GET['id'] ?? null);
        break;

    case 'return':
        requireLogin('anggota');
        (new BorrowController())->returnBook($_GET['id'] ?? null);
        break;

    case 'riwayat':
        requireLogin('anggota');
        (new BorrowController())->riwayat();
        break;

    case 'riwayat_admin':
        requireLogin('admin');
        (new BorrowController())->riwayatAdmin();
        break;

    case 'riwayat_buku':
        requireLogin('admin');
        $buku_id = $_GET['book_id'] ?? null;
        if ($buku_id) {
            (new BorrowController())->riwayatBuku($buku_id);
        } else {
            header("Location: index.php?page=riwayat_admin");
        }
        break;

    /* 
        MANAJEMEN ANGGOTA (ADMIN)
    */
    case 'user':
        requireLogin('admin');
        (new UserController())->index();
        break;

    case 'user_create':
        requireLogin('admin');
        (new UserController())->createForm();
        break;

    case 'user_store':
        requireLogin('admin');
        (new UserController())->store();
        break;

    case 'user_edit':
        requireLogin('admin');
        (new UserController())->editForm($_GET['id'] ?? null);
        break;

    case 'user_update':
        requireLogin('admin');
        (new UserController())->update($_GET['id'] ?? null);
        break;

    case 'user_delete':
        requireLogin('admin');
        (new UserController())->delete($_GET['id'] ?? null);
        break;

    // DEFAULT / 404 HANDLER /
   
        default:
        echo "<h2 style='color:red;'>❌ Halaman tidak ditemukan.</h2>";
        break;
}
?>
