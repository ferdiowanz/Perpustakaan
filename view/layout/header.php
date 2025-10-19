<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Ferdio</title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        header a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px 25px;
        }
        footer {
            text-align: center;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <div>
        <strong>📚 Perpustakaan MVC</strong>
    </div>
    <nav>
        <?php if (isset($_SESSION['user'])): ?>
            Halo, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>
            (<?= htmlspecialchars($_SESSION['user']['role']) ?>)
            |
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="index.php?page=book"> Buku</a>
                <a href="index.php?page=user">👥 Anggota</a>
                <a href="index.php?page=riwayat_admin"> Riwayat</a>
            <?php else: ?>
                <a href="index.php?page=book">Buku</a>
                <a href="index.php?page=riwayat"> Riwayat Saya</a>
            <?php endif; ?>
            |
            <a href="index.php?page=logout" onclick="return confirm('Yakin mau logout?')">🚪 Logout</a>
        <?php else: ?>
            <a href="index.php?page=login">🔐 Login</a>
            <a href="index.php?page=register">📝 Register</a>
        <?php endif; ?>
    </nav>
</header>

<main>
