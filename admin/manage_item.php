<?php
// admin/manage_gacha_items.php
session_start();
require_once 'auth_check.php';
require_once '../config/config.php';

// 1. Logika Tambah Item ke Banner
if (isset($_POST['add_btn'])) {
    $stmt = $conn->prepare("INSERT INTO banner_items (banner_id, item_id, drop_rate) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $_POST['banner_id'], $_POST['item_id'], $_POST['drop_rate']);
    $stmt->execute();
}

// 2. Logika Hapus Item dari Banner
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM banner_items WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Kelola Item Gacha (Rate Up)</h2>

<form method="POST">
    <select name="banner_id">
        <?php 
        $res = $conn->query("SELECT id, banner_name FROM banners");
        while($b = $res->fetch_assoc()) echo "<option value='{$b['id']}'>{$b['banner_name']}</option>";
        ?>
    </select>
    <select name="item_id">
        <?php 
        $res = $conn->query("SELECT id, item_name FROM items");
        while($i = $res->fetch_assoc()) echo "<option value='{$i['id']}'>{$i['item_name']}</option>";
        ?>
    </select>
    <input type="number" step="0.01" name="drop_rate" placeholder="Drop Rate (0.01 - 100)" required>
    <button type="submit" name="add_btn">Tambah ke Gacha</button>
</form>

<table border="1">
    <tr><th>Banner</th><th>Item</th><th>Drop Rate</th><th>Aksi</th></tr>
    <?php
    $query = "SELECT bi.id, b.banner_name, i.item_name, bi.drop_rate 
              FROM banner_items bi
              JOIN banners b ON bi.banner_id = b.id
              JOIN items i ON bi.item_id = i.id";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['banner_name']}</td>
            <td>{$row['item_name']}</td>
            <td>{$row['drop_rate']}%</td>
            <td><a href='?delete={$row['id']}' onclick='return confirm(\"Hapus?\")'>Hapus</a></td>
        </tr>";
    }
    ?>
</table>
</body>
</html>