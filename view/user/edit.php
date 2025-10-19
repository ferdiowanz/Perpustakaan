<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>✏️ Edit Anggota</title>
<link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="container">
    <h2>✏️ Edit Data Anggota</h2>

    <form action="index.php?page=anggota_update&id=<?= $user['id'] ?>" method="POST" class="form-box">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Role:</label>
        <select name="role">
            <option value="anggota" <?= $user['role'] === 'anggota' ? 'selected' : '' ?>>Anggota</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a href="index.php?page=anggota" class="btn-back">← mbali</a>
</div>

</body>
</html>
