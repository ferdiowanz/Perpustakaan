<?php
// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>📚 Daftar Buku</title>
    <link rel="stylesheet" href="public/css/style.css"> <!-- ✅ Hubungkan CSS -->
</head>
<body>

<!-- 🔹 Navbar -->
<header class="navbar">
    <div class="container">
        <div class="left">
            <h1>📘 Perpustakaan MVC</h1>
        </div>
        <div class="right">
            <?php if (isset($_SESSION['user'])): ?>
                <span>Halo, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong> (<?= $_SESSION['user']['role'] ?>)</span>

                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="index.php?page=riwayat_admin">📊 Riwayat Semua</a>
                    <a href="index.php?page=user">👥 Anggota</a>
                <?php else: ?>
                    <a href="index.php?page=riwayat">📖 Riwayat Saya</a>
                <?php endif; ?>

                <a href="index.php?page=logout" class="logout" onclick="return confirm('Yakin mau logout?')">🚪 Logout</a>
            <?php else: ?>
                <a href="index.php?page=login">🔐 Login</a>
                <a href="index.php?page=register">📝 Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">
    <h2>📚 Daftar Buku di Perpustakaan</h2>

    <!-- 🔹 Form Tambah Buku (khusus admin) -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <section class="form-section">
            <h3>➕ Tambah Buku Baru</h3>
            <form action="index.php?page=book_store" method="POST" class="form-box">
                <label>Judul:</label>
                <input type="text" name="judul" required>

                <label>Penulis:</label>
                <input type="text" name="penulis" required>

                <label>Penerbit:</label>
                <input type="text" name="penerbit" required>

                <label>Tahun Terbit:</label>
                <input type="number" name="tahun_terbit" min="1900" max="2099" required>

                <label>Stok:</label>
                <input type="number" name="stok" min="1" required>

                <button type="submit" class="btn-primary">Tambah Buku</button>
            </form>
        </section>
    <?php endif; ?>

    <!-- 🔹 Daftar Buku -->
    <section class="table-section">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)): ?>
                    <?php $no = 1; foreach ($books as $buku): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($buku['judul']) ?></td>
                            <td><?= htmlspecialchars($buku['penulis']) ?></td>
                            <td><?= htmlspecialchars($buku['penerbit']) ?></td>
                            <td><?= htmlspecialchars($buku['tahun_terbit']) ?></td>
                            <td><?= htmlspecialchars($buku['stok']) ?></td>
                            <td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <a href="index.php?page=book_delete&id=<?= $buku['id'] ?>" class="btn-danger" onclick="return confirm('Yakin hapus buku ini?')">🗑 Hapus</a>
                                    <a href="index.php?page=riwayat_buku&book_id=<?= $buku['id'] ?>" class="btn-secondary">📖 Riwayat</a>
                                <?php else: ?>
                                    <?php if ($buku['stok'] > 0): ?>
                                        <a href="index.php?page=borrow&id=<?= $buku['id'] ?>" class="btn-primary">📚 Pinjam</a>
                                    <?php else: ?>
                                        <span class="out-of-stock">Habis</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada buku tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> Perpustakaan FERDIO| Dibuat dengan ❤</p>
</footer>

</body>
</html>
