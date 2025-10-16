<?php
require_once __DIR__ . "/../service/database.php";

class BookModel extends Database {

    // 🔹 Ambil semua buku (tanpa pagination)
    public function getAllBooks() {
        $query = "SELECT * FROM buku ORDER BY id ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 🔹 Ambil data buku dengan pagination (opsional)
    public function getBooksPaginated($limit = 10, $offset = 0) {
        $query = "SELECT * FROM buku ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // 🔹 Hitung total buku (untuk pagination)
    public function getTotalBooks() {
        $query = "SELECT COUNT(*) as total FROM buku";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // 🔹 Tambah buku baru
    public function createBook($judul, $penulis, $penerbit, $tahun_terbit, $stok) {
        $query = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok, created_at)
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Gagal prepare query: " . $this->conn->error);
        }

        $stmt->bind_param("sssii", $judul, $penulis, $penerbit, $tahun_terbit, $stok);

        if (!$stmt->execute()) {
            die("Gagal menambah buku: " . $stmt->error);
        }

        return true;
    }

    // 🔹 Hapus buku
    public function deleteBook($id) {
        $query = "DELETE FROM buku WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // 🔹 Ambil satu data buku (untuk edit)
    public function getBookById($id) {
        $query = "SELECT * FROM buku WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // 🔹 Update buku
    public function updateBook($id, $judul, $penulis, $penerbit, $tahun_terbit, $stok) {
        $query = "UPDATE buku 
                  SET judul=?, penulis=?, penerbit=?, tahun_terbit=?, stok=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssiii", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $id);
        return $stmt->execute();
    }
}
