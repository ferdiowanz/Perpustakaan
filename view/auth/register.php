<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Daftar Akun Baru</h2>
    <form action="index.php?page=register_action" method="POST">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Konfirmasi Password:</label><br>
    <input type="password" name="password_confirm" required><br><br>

    <button type="submit">Daftar</button>
</form>

<p>Sudah punya akun? <a href="index.php?page=login">Login</a></p>
</body>
</html>
