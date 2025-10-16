<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>📖 Riwayat Peminjaman Buku</title>
</head>
<body>

<!-- 🔹 Header User Info -->
<?php if (isset($_SESSION['user'])): ?>
    <div style="margin-bottom: 15px;">
        Halo, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>
        (<?= htmlspecialchars($_SESSION['user']['role']) ?>)
        | <a href="index.php?page=book">Daftar Buku</a>
        | <a href="index.php?page=logout" onclick="return confirm('Yakin mau logout?')">Logout</a>
    </div>
<?php endif; ?>
<hr>

<h2>📖 Riwayat Peminjaman Buku</h2>

<!-- 🔹 Tabel Riwayat -->
<?php if (!empty($riwayat)): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php $no = ($page - 1) * $limit + 1; ?>
        <?php foreach ($riwayat as $r): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['judul']) ?></td>
                <td><?= htmlspecialchars($r['penulis']) ?></td>
                <td><?= htmlspecialchars($r['tanggal_pinjam']) ?></td>
                <td>
                    <?php if ($r['status'] === 'dipinjam'): ?>
                        <span style="color: orange;">Sedang Dipinjam</span>
                    <?php elseif ($r['status'] === 'terlambat'): ?>
                        <span style="color: red;">Terlambat</span>
                    <?php else: ?>
                        <span style="color: green;">Dikembalikan</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($r['status'] === 'dipinjam'): ?>
                        <a href="index.php?page=return&id=<?= $r['id'] ?>" 
                           onclick="return confirm('Kembalikan buku ini?')">Kembalikan</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- 🔹 Pagination -->
    <div style="margin-top: 15px; text-align: center;">
        <?php if ($page > 1): ?>
            <a href="index.php?page=riwayat&p=<?= $page - 1 ?>">⬅ Sebelumnya</a>
        <?php endif; ?>

        <span style="margin: 0 10px;">Halaman <?= $page ?> dari <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=riwayat&p=<?= $page + 1 ?>">Berikutnya ➡</a>
        <?php endif; ?>
    </div>

<?php else: ?>
    <p>Belum ada riwayat peminjaman buku.</p>
<?php endif; ?>

<p><a href="index.php?page=book">← Kembali ke Daftar Buku</a></p>

</body>
</html>
