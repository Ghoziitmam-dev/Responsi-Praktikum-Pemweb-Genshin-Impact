<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
require_once '../config/config.php';

// Inisialisasi variabel untuk form edit
$edit_id = '';
$edit_name = '';
$edit_element = '';
$edit_rarity = '';

// Proses Hapus Data (Delete)
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM characters WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: characters.php");
    exit;
}

// Proses Simpan Data (Create & Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $element = $_POST['element'];
    $rarity = $_POST['rarity'];
    $id = $_POST['id'];

    if (empty($id)) {
        // Insert
        $stmt = $conn->prepare("INSERT INTO characters (name, element, rarity) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $element, $rarity);
    } else {
        // Update
        $stmt = $conn->prepare("UPDATE characters SET name=?, element=?, rarity=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $element, $rarity, $id);
    }
    $stmt->execute();
    header("Location: characters.php");
    exit;
}

// Jika ada request Edit (Read single data)
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM characters WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if ($data) {
        $edit_id = $data['id'];
        $edit_name = $data['name'];
        $edit_element = $data['element'];
        $edit_rarity = $data['rarity'];
    }
}

// Ambil semua data karakter (Read all)
$characters = $conn->query("SELECT * FROM characters ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Karakter - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Kelola Karakter Utama</h1>
    <nav><a href="dashbord.php">Kembali ke Dashboard</a></nav>
    <hr>

    <h3><?= empty($edit_id) ? 'Tambah' : 'Edit' ?> Karakter</h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_id ?>">
        <label>Nama: <input type="text" name="name" value="<?= htmlspecialchars($edit_name) ?>" required></label><br><br>
        <label>Elemen: <input type="text" name="element" value="<?= htmlspecialchars($edit_element) ?>" required></label><br><br>
        <label>Rarity: <input type="text" name="rarity" value="<?= htmlspecialchars($edit_rarity) ?>" required></label><br><br>
        <button type="submit">Simpan</button>
        <?php if(!empty($edit_id)): ?> <a href="characters.php">Batal Edit</a> <?php endif; ?>
    </form>
    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>ID</th><th>Nama</th><th>Elemen</th><th>Rarity</th><th>Aksi</th></tr>
        <?php while ($row = $characters->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['element']) ?></td>
            <td><?= htmlspecialchars($row['rarity']) ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>