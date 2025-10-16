<?php
require_once __DIR__ . "/../service/database.php";

class UserModel extends Database {

    /** 🔹 Ambil user berdasarkan email (login/register/validasi unik) */
    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /** 🔹 Tambah user baru (default role = anggota) */
    public function createUser($nama, $email, $passwordHash, $role = 'anggota') {
        $query = "INSERT INTO users (nama, email, password, role, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Gagal prepare query: " . $this->conn->error);
        }
        $stmt->bind_param("ssss", $nama, $email, $passwordHash, $role);
        return $stmt->execute();
    }

    /** 🔹 Ambil semua user (untuk admin, urut terbaru) */
    public function getAllUsers() {
        $query = "SELECT id, nama, email, role, created_at 
                  FROM users 
                  ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** 🔹 Ambil data user berdasarkan ID */
    public function getUserById($id) {
        $query = "SELECT id, nama, email, role FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /** 🔹 Update user (nama, email, role, dan password opsional) */
    public function updateUser($id, $nama, $email, $role, $passwordHash = null) {
        if (!empty($passwordHash)) {
            $query = "UPDATE users 
                      SET nama = ?, email = ?, role = ?, password = ? 
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $nama, $email, $role, $passwordHash, $id);
        } else {
            $query = "UPDATE users 
                      SET nama = ?, email = ?, role = ? 
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $nama, $email, $role, $id);
        }
        return $stmt->execute();
    }

    /** 🔹 Hapus user berdasarkan ID (admin only) */
    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /** 🔹 Hitung total user (untuk dashboard/statistik) */
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) AS total FROM users";
        $result = $this->conn->query($query);
        return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
    }
}
