<?php
require_once __DIR__ . "/../model/BookModel.php";

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new BookModel();
    }

    // 🔹 Tampilkan semua buku
    public function index() {
        $books = $this->bookModel->getAllBooks();
        include __DIR__ . "/../view/book/index.php";
    }

    // 🔹 Tambah buku baru
    public function store() {
        $judul = $_POST['judul'] ?? '';
        $penulis = $_POST['penulis'] ?? '';
        $penerbit = $_POST['penerbit'] ?? '';
        $tahun_terbit = $_POST['tahun_terbit'] ?? 0;
        $stok = $_POST['stok'] ?? 0;

        if ($this->bookModel->createBook($judul, $penulis, $penerbit, $tahun_terbit, $stok)) {
            header("Location: index.php?page=book");
            exit;
        } else {
            echo "❌ Gagal menambah buku.";
        }
    }

    // 🔹 Hapus buku
    public function delete($id) {
        if ($this->bookModel->deleteBook($id)) {
            header("Location: index.php?page=book");
            exit;
        } else {
            echo "❌ Gagal menghapus buku.";
        }
    }

    // 🔹 Tampilkan form edit buku
    public function edit($id) {
        $book = $this->bookModel->getBookById($id);
        if (!$book) {
            echo "❌ Buku tidak ditemukan.";
            exit;
        }

        include __DIR__ . "/../view/book/edit.php";
    }

    // 🔹 Update buku
    public function update($id) {
        $judul = $_POST['judul'] ?? '';
        $penulis = $_POST['penulis'] ?? '';
        $penerbit = $_POST['penerbit'] ?? '';
        $tahun_terbit = $_POST['tahun_terbit'] ?? 0;
        $stok = $_POST['stok'] ?? 0;

        if ($this->bookModel->updateBook($id, $judul, $penulis, $penerbit, $tahun_terbit, $stok)) {
            header("Location: index.php?page=book");
            exit;
        } else {
            echo "❌ Gagal memperbarui data buku.";
        }
    }
}
?>
