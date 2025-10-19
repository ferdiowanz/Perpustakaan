<?php
require_once __DIR__ . "/../model/BookModel.php";

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new BookModel();
    }

    public function index() {
        $books = $this->bookModel->getAllBooks();
        include __DIR__ . "/../view/book/index.php";
    }

    public function store() {
        $judul = trim($_POST['judul'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun_terbit = (int)($_POST['tahun_terbit'] ?? 0);
        $stok = (int)($_POST['stok'] ?? 0);

        if ($judul && $penulis && $penerbit && $tahun_terbit > 0) {
            if ($this->bookModel->createBook($judul, $penulis, $penerbit, $tahun_terbit, $stok)) {
                header("Location: index.php?page=book");
                exit;
            }
        }
        echo "❌ Gagal menambah buku.";
    }

    public function delete($id) {
        $id = (int)$id;
        if ($id && $this->bookModel->deleteBook($id)) {
            header("Location: index.php?page=book");
            exit;
        }
        echo "❌ Gagal menghapus buku.";
    }

    public function edit($id) {
        $book = $this->bookModel->getBookById((int)$id);
        if (!$book) {
            echo "❌ Buku tidak ditemukan.";
            exit;
        }
        include __DIR__ . "/../view/book/edit.php";
    }

    public function update($id) {
        $judul = trim($_POST['judul'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun_terbit = (int)($_POST['tahun_terbit'] ?? 0);
        $stok = (int)($_POST['stok'] ?? 0);

        if ($this->bookModel->updateBook((int)$id, $judul, $penulis, $penerbit, $tahun_terbit, $stok)) {
            header("Location: index.php?page=book");
            exit;
        }
        echo "❌ Gagal memperbarui buku.";
    }
}
?>
