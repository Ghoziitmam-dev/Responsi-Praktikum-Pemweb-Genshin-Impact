<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
require_once '../config/config.php';

$edit_id = '';
$edit_name = '';
$edit_status = '';

// Hapus Banner
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: banners.php");
    exit;
}

// Simpan/Update Banner
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO banners (name, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $status);
    } else {
        $stmt = $conn->prepare("UPDATE banners SET name=?, status=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $status, $id);
    }
    $stmt->execute();
    header("Location: banners.php");
    exit;
}

// Ambil data edit
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if ($data) {
        $edit_id = $data['id'];
        $edit_name = $data['name'];
        $edit_status = $data['status'];
    }
}

$banners = $conn->query("SELECT * FROM banners ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Banner - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Kelola Banner Gacha</h1>
    <nav><a href="dashbord.php">Kembali ke Dashboard</a></nav>
    <hr>

    <h3><?= empty($edit_id) ? 'Tambah' : 'Edit' ?> Banner</h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_id ?>">
        <label>Nama Banner: <input type="text" name="name" value="<?= htmlspecialchars($edit_name) ?>" required></label><br><br>
        <label>Status: 
            <select name="status">
                <option value="Active" <?= $edit_status == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $edit_status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label><br><br>
        <button type="submit">Simpan</button>
        <?php if(!empty($edit_id)): ?> <a href="banners.php">Batal Edit</a> <?php endif; ?>
    </form>
    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>ID</th><th>Nama Banner</th><th>Status</th><th>Aksi</th></tr>
        <?php 
        // Bypass error if table doesn't exist yet during initial setup
        if ($banners): while ($row = $banners->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><a href="?edit=<?= $row['id'] ?>">Edit</a> | <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a></td>
        </tr>
        <?php endwhile; endif; ?>
    </table>
</body>
</html>