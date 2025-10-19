<?php
require_once __DIR__ . "/../service/database.php";

class BookModel extends Database {

    // ambil semua buku
    public function getAllBooks() {
        $query = "SELECT * FROM buku ORDER BY id ASC";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ambil dengan pagination
    public function getBooksPaginated($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // hitung total bbuku
    public function getTotalBooks() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM buku");
        return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
    }

    // tambah buku
    public function createBook($judul, $penulis, $penerbit, $tahun_terbit, $stok) {
        $stmt = $this->conn->prepare("
            INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        if (!$stmt) die("Gagal prepare query: " . $this->conn->error);
        $stmt->bind_param("sssii", $judul, $penulis, $penerbit, $tahun_terbit, $stok);
        return $stmt->execute();
    }

    // hapus buku
    public function deleteBook($id) {
        $stmt = $this->conn->prepare("DELETE FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ambil 1 buku
    public function getBookById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // update buku
    public function updateBook($id, $judul, $penulis, $penerbit, $tahun_terbit, $stok) {
        $stmt = $this->conn->prepare("
            UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun_terbit=?, stok=? WHERE id=?
        ");
        $stmt->bind_param("sssiii", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $id);
        return $stmt->execute();
    }
}
?>
