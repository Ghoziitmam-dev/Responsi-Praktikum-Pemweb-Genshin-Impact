<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
require_once '../config/config.php';

// Inisialisasi form
$edit_id = '';
$edit_name = '';
$edit_type = '';
$edit_rarity = '';

// Hapus Item
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: items.php");
    exit;
}

// Simpan/Update Item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $rarity = $_POST['rarity'];
    $id = $_POST['id'];

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO items (name, type, rarity) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $type, $rarity);
    } else {
        $stmt = $conn->prepare("UPDATE items SET name=?, type=?, rarity=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $type, $rarity, $id);
    }
    $stmt->execute();
    header("Location: items.php");
    exit;
}

// Ambil data edit
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if ($data) {
        $edit_id = $data['id'];
        $edit_name = $data['name'];
        $edit_type = $data['type'];
        $edit_rarity = $data['rarity'];
    }
}

$items = $conn->query("SELECT * FROM items ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Item (Gacha) - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Kelola Item & Senjata</h1>
    <nav><a href="dashbord.php">Kembali ke Dashboard</a></nav>
    <hr>

    <h3><?= empty($edit_id) ? 'Tambah' : 'Edit' ?> Item</h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_id ?>">
        <label>Nama: <input type="text" name="name" value="<?= htmlspecialchars($edit_name) ?>" required></label><br><br>
        <label>Tipe: <input type="text" name="type" value="<?= htmlspecialchars($edit_type) ?>" placeholder="Weapon/Artifact" required></label><br><br>
        <label>Rarity: 
            <select name="rarity">
                <option value="Common" <?= $edit_rarity == 'Common' ? 'selected' : '' ?>>Common</option>
                <option value="Epic" <?= $edit_rarity == 'Epic' ? 'selected' : '' ?>>Epic</option>
                <option value="Legendary" <?= $edit_rarity == 'Legendary' ? 'selected' : '' ?>>Legendary</option>
            </select>
        </label><br><br>
        <button type="submit">Simpan</button>
        <?php if(!empty($edit_id)): ?> <a href="items.php">Batal Edit</a> <?php endif; ?>
    </form>
    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>ID</th><th>Nama</th><th>Tipe</th><th>Rarity</th><th>Aksi</th></tr>
        <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['rarity']) ?></td>
            <td><a href="?edit=<?= $row['id'] ?>">Edit</a> | <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>