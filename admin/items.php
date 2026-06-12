<?php
// admin/items.php
session_start();
require_once 'auth_check.php'; // Proteksi admin
require_once '../config/config.php';

// 1. Logika Tambah Item
if (isset($_POST['tambah_btn'])) {
    $item_name   = $_POST['item_name'];
    $item_type   = $_POST['item_type'];
    $rarity      = $_POST['rarity'];
    $drop_rate   = $_POST['drop_rate'];
    $image       = $_POST['image'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO items (item_name, item_type, rarity, drop_rate, image, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidds", $item_name, $item_type, $rarity, $drop_rate, $image, $description);
    
    if ($stmt->execute()) {
        echo "<script>alert('Item berhasil ditambahkan!'); window.location='items.php';</script>";
    }
    $stmt->close();
}

// 2. Logika Hapus Item
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: items.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Item & Senjata</title>
</head>
<body>

<h2>Kelola Item & Senjata</h2>
<a href="dashboard.php">Kembali ke Dashboard</a>

<section>
    <h3>Tambah Item Baru</h3>
    <form method="POST" action="">
        <input type="text" name="item_name" placeholder="Nama Item" required>
        <select name="item_type">
            <option value="weapon">Weapon</option>
            <option value="material">Material</option>
        </select>
        <input type="number" name="rarity" placeholder="Rarity (1-5)" min="1" max="5" required>
        <input type="number" step="0.01" name="drop_rate" placeholder="Drop Rate" required>
        <input type="text" name="image" placeholder="Nama File Gambar" required>
        <textarea name="description" placeholder="Deskripsi"></textarea>
        <button type="submit" name="tambah_btn">Simpan Item</button>
    </form>
</section>
<hr>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Tipe</th>
        <th>Rarity</th>
        <th>Aksi</th>
    </tr>
    <?php
    $query = "SELECT id, item_name, item_type, rarity FROM items";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'] ?? '';
            $name = (string)($row['item_name'] ?? '');
            $type = (string)($row['item_type'] ?? '');
            $rarity = $row['rarity'] ?? '';
            
            echo "<tr>
                <td>".htmlspecialchars($id)."</td>
                <td>".htmlspecialchars($name)."</td>
                <td>".htmlspecialchars($type)."</td>
                <td>".htmlspecialchars($rarity)."</td>
                <td>
                    <a href='items.php?delete=".$id."' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Data belum ada.</td></tr>";
    }
    ?>
</table>

</body>
</html>