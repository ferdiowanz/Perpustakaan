<?php
require_once __DIR__ . "/../service/database.php";

class UserModel extends Database {

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createUser($nama, $email, $passwordHash, $role = 'anggota') {
        $stmt = $this->conn->prepare("
            INSERT INTO users (nama, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssss", $nama, $email, $passwordHash, $role);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $result = $this->conn->query("SELECT * FROM users ORDER BY created_at DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $nama, $email, $role, $passwordHash = null) {
        if ($passwordHash) {
            $stmt = $this->conn->prepare("
                UPDATE users SET nama=?, email=?, role=?, password=? WHERE id=?
            ");
            $stmt->bind_param("ssssi", $nama, $email, $role, $passwordHash, $id);
        } else {
            $stmt = $this->conn->prepare("
                UPDATE users SET nama=?, email=?, role=? WHERE id=?
            ");
            $stmt->bind_param("sssi", $nama, $email, $role, $id);
        }
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getTotalUsers() {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM users");
        return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
    }
}
?>
