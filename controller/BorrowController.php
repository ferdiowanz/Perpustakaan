<?php
require_once __DIR__ . "/../model/BorrowModel.php";

class BorrowController {
    private $model;

    public function __construct() {
        $this->model = new BorrowModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // proses peminjaman buku
    public function borrow($buku_id) {
        $buku_id = (int)$buku_id;
        if (!$buku_id || !isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $this->model->borrowBook($user_id, $buku_id);
        header("Location: index.php?page=book");
        exit;
    }

    // proses pengembalian buku
    public function returnBook($peminjaman_id) {
        $peminjaman_id = (int)$peminjaman_id;
        if ($peminjaman_id) {
            $this->model->returnBook($peminjaman_id);
        }
        header("Location: index.php?page=riwayat");
        exit;
    }

    // riwayat peminjaman(anggota)
    public function riwayat() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $limit = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $limit;

        $riwayat = $this->model->getUserBorrowedBooks($user_id, $limit, $offset);
        $totalRows = $this->model->countUserBorrowedBooks($user_id);
        $totalPages = ceil($totalRows / $limit);

        include "view/borrow/riwayat.php";
    }

    // riwayat peminjaman (admin)
    public function riwayatAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        $filter_status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? null;
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;

        $limit = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $limit;

        $riwayat = $this->model->getAllBorrows($filter_status, $search, $start_date, $end_date, $limit, $offset);
        $totalRows = $this->model->countAllBorrows($filter_status, $search, $start_date, $end_date);
        $totalPages = ceil($totalRows / $limit);

        include "view/borrow/riwayat_admin.php";
    }

    //riwayat per buku hanya admin
    public function riwayatBuku($buku_id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        $buku_id = (int)$buku_id;
        $riwayatBuku = $this->model->getBorrowHistoryByBook($buku_id);

        include "view/borrow/riwayat_buku.php";
    }
}
?>
