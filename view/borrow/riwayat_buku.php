<?php include __DIR__ . "/../layout/header.php"; ?>


<div class="container">
  <h2>📘 Riwayat Peminjaman Buku</h2>

  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>Nama Peminjam</th>
      <th>Email</th>
      <th>Tanggal Pinjam</th>
      <th>Jatuh Tempo</th>
      <th>Tanggal Kembali</th>
      <th>Status</th>
    </tr>

    <?php if (!empty($riwayatBuku)): ?>
      <?php foreach ($riwayatBuku as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['nama']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td><?= htmlspecialchars($r['tanggal_pinjam']) ?></td>
          <td><?= htmlspecialchars($r['tanggal_jatuh_tempo']) ?></td>
          <td><?= htmlspecialchars($r['tanggal_kembali'] ?: '-') ?></td>
          <td><?= ucfirst(htmlspecialchars($r['status'])) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6" align="center">Belum ada riwayat peminjaman untuk buku ini.</td></tr>
    <?php endif; ?>
  </table>

  <br>
  <a href="index.php?page=riwayat_admin">← Kembali ke Riwayat Semua</a>
</div>

<?php include __DIR__ . "/../layout/footer.php"; ?>

