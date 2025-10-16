<?php
require_once __DIR__ . "/../model/BorrowModel.php";

class BorrowController {
    private $model;

    public function __construct() {
        $this->model = new BorrowModel();
    }

    // 🔹 Proses pinjam buku
    public function borrow($buku_id) {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $this->model->borrowBook($user_id, $buku_id);

        header("Location: index.php?page=book");
        exit;
    }

    // 🔹 Proses kembalikan buku
    public function returnBook($peminjaman_id) {
        $this->model->returnBook($peminjaman_id);

        header("Location: index.php?page=riwayat");
        exit;
    }

    // 🔹 Riwayat peminjaman user
    public function riwayat() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $limit = 10;
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $offset = ($page - 1) * $limit;

        $riwayat = $this->model->getUserBorrowedBooks($user_id, $limit, $offset);
        $totalRows = $this->model->countUserBorrowedBooks($user_id);
        $totalPages = ceil($totalRows / $limit);

        include "view/borrow/riwayat.php";
    }

    // 🔹 Riwayat semua peminjaman (admin)
    public function riwayatAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        $filter_status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? null;
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $page = $_GET['p'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $riwayat = $this->model->getAllBorrows($filter_status, $search, $start_date, $end_date, $limit, $offset);
        $totalRows = $this->model->countAllBorrows($filter_status, $search, $start_date, $end_date);
        $totalPages = ceil($totalRows / $limit);

        include "view/borrow/riwayat_admin.php";
    }

    // 🔹 Riwayat peminjaman per buku (admin)
    public function riwayatBuku($buku_id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }

        // Ambil data riwayat buku
        $riwayatBuku = $this->model->getBorrowHistoryByBook($buku_id);
        include "view/borrow/riwayat_buku.php";
    }
}
