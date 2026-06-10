<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
require_once '../config/config.php';

// Hapus User (Tidak bisa hapus admin sendiri)
if (isset($_GET['delete'])) {
    if ($_GET['delete'] != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $_GET['delete']);
        $stmt->execute();
    }
    header("Location: users.php");
    exit;
}

// Ambil semua data user beserta karakter yang mereka pilih (jika ada)
$sql = "SELECT u.id, u.username, u.role, c.name AS character_name 
        FROM users u 
        LEFT JOIN characters c ON u.selected_character_id = c.id 
        ORDER BY u.id DESC";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola User - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Daftar Traveler (User) Terdaftar</h1>
    <nav><a href="dashbord.php">Kembali ke Dashboard</a></nav>
    <hr>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>ID</th><th>Username</th><th>Role</th><th>Karakter Aktif</th><th>Aksi</th></tr>
        <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['character_name'] ?? 'Belum Pilih') ?></td>
            <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus user ini?')">Hapus</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>