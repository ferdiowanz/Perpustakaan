<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <a href="index.php?page=book"
    class="btn-back"> <-back </a>
    <title>👥 Manajemen Anggota</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #3498db;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>👥 Daftar Anggota</h2>
    <a href="index.php?page=book" class="btn-back"> ⬅Kembali ke Daftar Buku</a>
    <a href="index.php?page=user_create" class="btn">Tambah Anggota</a>
</div>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Tanggal Dibuat</th>
        <th>Aksi</th>
    </tr>

    <?php if (!empty($users)): ?>
        <?php $no = 1; foreach ($users as $u): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($u['nama']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= ucfirst(htmlspecialchars($u['role'])) ?></td>
                <td><?= htmlspecialchars(date("d M Y", strtotime($u['created_at']))) ?></td>
                <td>
                    <a href="index.php?page=user_edit&id=<?= $u['id'] ?>" class="btn">Edit</a>
                    <a href="index.php?page=user_delete&id=<?= $u['id'] ?>" class="btn btn-danger"
                       onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Belum ada anggota terdaftar.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
