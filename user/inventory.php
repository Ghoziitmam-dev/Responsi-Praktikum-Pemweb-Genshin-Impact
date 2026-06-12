<?php
session_start();
 require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT inv.quantity, itm.item_name, itm.item_type, itm.image, itm.rarity 
    FROM inventory inv 
    JOIN items itm ON inv.item_id = itm.id 
    WHERE inv.user_id = ?
";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error Query Inventory: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_inventory = $stmt->get_result();

// 2. MASUKKAN HASILNYA KE DALAM VARIABEL $inventory_items
$inventory_items = [];
while ($row = $result_inventory->fetch_assoc()) {
    $inventory_items[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory - Genshin Impact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Tas (Inventory)</h1>

<nav>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</nav>

<hr>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Nama Item</th>
            <th>Tipe</th>
            <th>Rarity</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inventory_items as $row): // Ganti dengan foreach ($inventory_items as $row) dari DB ?>
            <tr>
                <td><?= htmlspecialchars($row['item_name']); ?></td>
                <td>
                    <img src="../image/<?php echo htmlspecialchars($item['image']); ?>" 
                        alt="Gambar Item" 
                        style="width: 50px; height: 50px; object-fit: contain;">
                </td>
                <td><?= htmlspecialchars($row['type'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($row['rarity']); ?></td>
                <td><?= htmlspecialchars($row['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>