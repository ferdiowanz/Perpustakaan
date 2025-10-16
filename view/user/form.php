<?php include __DIR__ . "/../layout/header.php"; ?>

<div class="form-container">
  <h2><?= isset($user) ? '✏️ Edit Anggota' : '➕ Tambah Anggota'; ?></h2>

  <form method="post" 
        action="index.php?page=<?= isset($user) ? 'user_update&id=' . $user['id'] : 'user_store'; ?>"
        style="max-width: 400px; margin-top: 20px;">
    
    <label for="nama"><strong>Nama:</strong></label><br>
    <input type="text" id="nama" name="nama" 
           value="<?= htmlspecialchars($user['nama'] ?? '') ?>" 
           required style="width:100%; padding:8px; margin-bottom:10px;"><br>

    <label for="email"><strong>Email:</strong></label><br>
    <input type="email" id="email" name="email" 
           value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
           required style="width:100%; padding:8px; margin-bottom:10px;"><br>

    <?php if (!isset($user)): ?>
      <label for="password"><strong>Password:</strong></label><br>
      <input type="password" id="password" name="password" 
             required style="width:100%; padding:8px; margin-bottom:10px;"><br>
    <?php endif; ?>

    <label for="role"><strong>Role:</strong></label><br>
    <select id="role" name="role" style="width:100%; padding:8px; margin-bottom:15px;">
      <option value="anggota" <?= (isset($user) && $user['role'] === 'anggota') ? 'selected' : '' ?>>Anggota</option>
      <option value="admin" <?= (isset($user) && $user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
    </select><br>

    <button type="submit" style="width:100%; padding:10px; background:#4CAF50; border:none; color:white; border-radius:5px;">
      💾 Simpan
    </button>
  </form>

  <div style="margin-top:15px;">
    <a href="index.php?page=user" 
       style="text-decoration:none; color:#555;">← Kembali ke Daftar Anggota</a>
  </div>
</div>

<?php include __DIR__ . "/../layout/footer.php"; ?>