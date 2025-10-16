<?php include __DIR__ . "/../layout/header.php"; ?>

<div class="container">
  <a href="index.php?page=book" class="btn-back">⬅ Kembali ke Daftar Buku</a>

  <h2>📚 Riwayat Peminjaman (Admin)</h2>

  <form method="get" action="index.php" class="filter-form">
    <input type="hidden" name="page" value="riwayat_admin">
    <input type="text" name="search" placeholder="Cari judul atau nama..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <select name="status">
      <option value="">Semua Status</option>
      <option value="dipinjam" <?= (($_GET['status'] ?? '') === 'dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
      <option value="dikembalikan" <?= (($_GET['status'] ?? '') === 'dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
      <option value="terlambat" <?= (($_GET['status'] ?? '') === 'terlambat') ? 'selected' : '' ?>>Terlambat</option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
  </form>

  <table class="data-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Anggota</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Jatuh Tempo</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($riwayat)): ?>
        <?php $no = ($page - 1) * $limit + 1; ?>
        <?php foreach ($riwayat as $r): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($r['nama']) ?></td>
            <td><?= htmlspecialchars($r['judul']) ?></td>
            <td><?= htmlspecialchars($r['tanggal_pinjam']) ?></td>
            <td><?= htmlspecialchars($r['tanggal_jatuh_tempo']) ?></td>
            <td><?= htmlspecialchars($r['tanggal_kembali'] ?: '-') ?></td>
            <td>
              <?php if ($r['status'] === 'dipinjam'): ?>
                <span class="status status-pinjam">Dipinjam</span>
              <?php elseif ($r['status'] === 'terlambat'): ?>
                <span class="status status-late">Terlambat</span>
              <?php else: ?>
                <span class="status status-return">Dikembalikan</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">Tidak ada data peminjaman.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php
        $baseUrl = "index.php?page=riwayat_admin";
        if (!empty($_GET['status'])) $baseUrl .= "&status=" . urlencode($_GET['status']);
        if (!empty($_GET['search'])) $baseUrl .= "&search=" . urlencode($_GET['search']);
      ?>
      <?php if ($page > 1): ?>
        <a href="<?= $baseUrl . "&p=" . ($page - 1) ?>" class="page-btn">⬅ Sebelumnya</a>
      <?php endif; ?>

      <span>Halaman <?= $page ?> dari <?= $totalPages ?></span>

      <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl . "&p=" . ($page + 1) ?>" class="page-btn">Berikutnya ➡</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . "/../layout/footer.php"; ?>
