<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>✏️ Edit Buku</title>
</head>
<body>

<!-- 🔹 Header Navigasi -->
<div style="margin-bottom: 15px;">
    <a href="index.php?page=book" 
       style="text-decoration:none; background:#4CAF50; color:white; padding:6px 10px; border-radius:4px;">
       ⬅ Kembali ke Daftar Buku
    </a>
</div>

<h2>✏️ Edit Data Buku</h2>

<?php if (!empty($book)): ?>
    <form action="index.php?page=book_update&id=<?= $book['id'] ?>" method="POST" style="margin-top: 15px;">
        <label>Judul Buku:</label><br>
        <input type="text" name="judul" value="<?= htmlspecialchars($book['judul']) ?>" required><br><br>

        <label>Penulis:</label><br>
        <input type="text" name="penulis" value="<?= htmlspecialchars($book['penulis']) ?>" required><br><br>

        <label>Penerbit:</label><br>
        <input type="text" name="penerbit" value="<?= htmlspecialchars($book['penerbit']) ?>" required><br><br>

        <label>Tahun Terbit:</label><br>
        <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($book['tahun_terbit']) ?>" min="1900" max="2099" required><br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= htmlspecialchars($book['stok']) ?>" min="0" required><br><br>

        <button type="submit" 
                style="background:#2196F3; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
            💾 Simpan Perubahan
        </button>
    </form>
<?php else: ?>
    <p style="color:red;">❌ Data buku tidak ditemukan.</p>
<?php endif; ?>

</body>
</html>
