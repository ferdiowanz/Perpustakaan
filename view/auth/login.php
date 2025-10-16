<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container">
  <h2>Login</h2>

  <?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <form method="POST" action="index.php?page=login_action">
    <label>Email:</label><br>
    <input type="text" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <hr>
  <p>Belum punya akun?
    <a href="index.php?page=register">Daftar di sini</a>
  </p>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
