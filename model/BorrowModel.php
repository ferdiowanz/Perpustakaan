<?php
require_once __DIR__ . "/../service/database.php";

class BorrowModel extends Database {

    // 🔹 Proses pinjam buku
    public function borrowBook($user_id, $buku_id) {
        // Cek apakah user sudah punya pinjaman aktif
        // $cekPinjaman = $this->conn->prepare("
        //     SELECT COUNT(*) AS total 
        //     FROM peminjaman 
        //     WHERE user_id = ? AND status = 'dipinjam'
        // ");
        // $cekPinjaman->bind_param("i", $user_id);
        // $cekPinjaman->execute();
        // $result = $cekPinjaman->get_result()->fetch_assoc();

        // if ($result['total'] > 10) {
        //     echo "<script>alert('Kamu masih punya buku yang belum dikembalikan! Kembalikan dulu sebelum meminjam lagi.'); window.location='index.php?page=riwayat';</script>";
        //     exit;
        // }

        // Cek stok buku
        $cekStok = $this->conn->prepare("SELECT stok FROM buku WHERE id = ?");
        $cekStok->bind_param("i", $buku_id);
        $cekStok->execute();
        $stok = $cekStok->get_result()->fetch_assoc()['stok'] ?? 0;

        if ($stok <= 0) {
            echo "<script>alert('Stok buku ini sudah habis!'); window.location='index.php?page=book';</script>";
            exit;
        }

        // Kurangi stok
        $this->conn->query("UPDATE buku SET stok = stok - 1 WHERE id = $buku_id");

        // Tambahkan data peminjaman baru
        $query = "INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, tanggal_jatuh_tempo, status)
                  VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'dipinjam')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $buku_id);
        return $stmt->execute();
    }

    // 🔹 Kembalikan buku
    public function returnBook($id) {
        $this->conn->query("UPDATE buku b 
                            JOIN peminjaman p ON b.id = p.buku_id 
                            SET b.stok = b.stok + 1 
                            WHERE p.id = $id");

        $query = "UPDATE peminjaman 
                  SET status = 'dikembalikan', tanggal_kembali = NOW() 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // 🔹 Ambil data peminjaman user (dengan pagination)
    public function getUserBorrowedBooks($user_id, $limit = 10, $offset = 0) {
        $query = "SELECT p.id, b.judul, b.penulis, p.tanggal_pinjam, p.status
                  FROM peminjaman p
                  JOIN buku b ON p.buku_id = b.id
                  WHERE p.user_id = ?
                  ORDER BY p.tanggal_pinjam DESC
                  LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iii', $user_id, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 🔹 Hitung total data untuk user (pagination)
    public function countUserBorrowedBooks($user_id) {
        $query = "SELECT COUNT(*) AS total FROM peminjaman WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    // 🔹 Update status jadi 'terlambat'
    public function updateOverdueStatus() {
        $this->conn->query("
            UPDATE peminjaman 
            SET status = 'terlambat'
            WHERE status = 'dipinjam' 
            AND tanggal_jatuh_tempo < NOW()
        ");
    }

    // 🔹 Ambil semua data peminjaman (admin, dengan pagination + filter)
    public function getAllBorrows($filter_status = null, $search = null, $start_date = null, $end_date = null, $limit = 10, $offset = 0) {
        $this->updateOverdueStatus();

        $query = "SELECT p.*, b.judul, u.nama 
                  FROM peminjaman p
                  JOIN buku b ON p.buku_id = b.id
                  JOIN users u ON p.user_id = u.id
                  WHERE 1=1";

        $params = [];
        $types = "";

        if ($filter_status) {
            $query .= " AND p.status = ?";
            $types .= "s";
            $params[] = $filter_status;
        }
        if ($start_date && $end_date) {
            $query .= " AND DATE(p.tanggal_pinjam) BETWEEN ? AND ?";
            $types .= "ss";
            $params[] = $start_date;
            $params[] = $end_date;
        }
        if ($search) {
            $query .= " AND (b.judul LIKE ? OR u.nama LIKE ?)";
            $types .= "ss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $query .= " ORDER BY p.tanggal_pinjam DESC LIMIT ? OFFSET ?";
        $types .= "ii";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 🔹 Hitung total data semua peminjaman (admin)
    public function countAllBorrows($filter_status = null, $search = null, $start_date = null, $end_date = null) {
        $query = "SELECT COUNT(*) AS total 
                  FROM peminjaman p
                  JOIN buku b ON p.buku_id = b.id
                  JOIN users u ON p.user_id = u.id
                  WHERE 1=1";

        if ($filter_status) {
            $query .= " AND p.status = '" . $this->conn->real_escape_string($filter_status) . "'";
        }
        if ($search) {
            $s = "%" . $this->conn->real_escape_string($search) . "%";
            $query .= " AND (b.judul LIKE '$s' OR u.nama LIKE '$s')";
        }
        if ($start_date && $end_date) {
            $query .= " AND DATE(p.tanggal_pinjam) BETWEEN '$start_date' AND '$end_date'";
        }

        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
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
