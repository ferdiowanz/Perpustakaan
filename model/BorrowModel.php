<?php
require_once __DIR__ . "/../service/database.php";

class BorrowModel extends Database {

    // tambah transaksi peminjaman
    public function borrowBook($user_id, $buku_id) {
        // Cek stok buku
        $cekStok = $this->conn->prepare("SELECT stok FROM buku WHERE id = ?");
        $cekStok->bind_param("i", $buku_id);
        $cekStok->execute();
        $stok = $cekStok->get_result()->fetch_assoc()['stok'] ?? 0;

        if ($stok <= 0) {
            echo "<script>alert('❌ Stok buku ini habis!');window.location='index.php?page=book';</script>";
            exit;
        }

        // Kurangi stok
        $this->conn->query("UPDATE buku SET stok = stok - 1 WHERE id = $buku_id");

        // Simpan peminjaman
        $stmt = $this->conn->prepare("
            INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, tanggal_jatuh_tempo, status)
            VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 'dipinjam')
        ");
        $stmt->bind_param("ii", $user_id, $buku_id);
        return $stmt->execute();
    }

    // Kembalikan buku
    public function returnBook($id) {
        $this->conn->query("
            UPDATE buku b 
            JOIN peminjaman p ON b.id = p.buku_id 
            SET b.stok = b.stok + 1 
            WHERE p.id = $id
        ");

        $stmt = $this->conn->prepare("
            UPDATE peminjaman 
            SET status = 'dikembalikan', tanggal_kembali = NOW() 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Riwayat anggota
    public function getUserBorrowedBooks($user_id, $limit, $offset) {
        $stmt = $this->conn->prepare("
            SELECT p.id, b.judul, b.penulis, p.tanggal_pinjam, p.tanggal_jatuh_tempo, p.status
            FROM peminjaman p
            JOIN buku b ON p.buku_id = b.id
            WHERE p.user_id = ?
            ORDER BY p.tanggal_pinjam DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $user_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countUserBorrowedBooks($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM peminjaman WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    }

    // Update status terlambat otomatis
    private function updateOverdueStatus() {
        $this->conn->query("
            UPDATE peminjaman 
            SET status = 'terlambat'
            WHERE status = 'dipinjam' AND tanggal_jatuh_tempo < NOW()
        ");
    }

    // Riwayat semua (admin)
    public function getAllBorrows($filter_status, $search, $start_date, $end_date, $limit, $offset) {
        $this->updateOverdueStatus();

        $query = "
            SELECT p.*, b.judul, u.nama 
            FROM peminjaman p
            JOIN buku b ON p.buku_id = b.id
            JOIN users u ON p.user_id = u.id
            WHERE 1=1
        ";

        $params = [];
        $types = "";

        if ($filter_status) {
            $query .= " AND p.status = ?";
            $params[] = $filter_status;
            $types .= "s";
        }
        if ($search) {
            $query .= " AND (b.judul LIKE ? OR u.nama LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }
        if ($start_date && $end_date) {
            $query .= " AND DATE(p.tanggal_pinjam) BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
            $types .= "ss";
        }

        $query .= " ORDER BY p.tanggal_pinjam DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllBorrows($filter_status, $search, $start_date, $end_date) {
        $query = "
            SELECT COUNT(*) AS total 
            FROM peminjaman p
            JOIN buku b ON p.buku_id = b.id
            JOIN users u ON p.user_id = u.id
            WHERE 1=1
        ";

        if ($filter_status) $query .= " AND p.status = '" . $this->conn->real_escape_string($filter_status) . "'";
        if ($search) {
            $s = "%" . $this->conn->real_escape_string($search) . "%";
            $query .= " AND (b.judul LIKE '$s' OR u.nama LIKE '$s')";
        }
        if ($start_date && $end_date) {
            $query .= " AND DATE(p.tanggal_pinjam) BETWEEN '$start_date' AND '$end_date'";
        }

        $result = $this->conn->query($query);
        return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
    }

   //  Ambil riwayat peminjaman berdasarkan ID buku
    public function getBorrowHistoryByBook($buku_id) {
        $query = "SELECT p.id, u.nama, u.email, p.tanggal_pinjam, 
                         p.tanggal_jatuh_tempo, p.tanggal_kembali, p.status
                  FROM peminjaman p
                  JOIN users u ON p.user_id = u.id
                  WHERE p.buku_id = ?
                  ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $buku_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}