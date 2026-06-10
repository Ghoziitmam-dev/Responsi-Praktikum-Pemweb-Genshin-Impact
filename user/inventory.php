<?php
session_start();
 require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query untuk mengambil data inventory beserta detail itemnya
 $sql = "SELECT i.name, i.type, i.rarity, ui.quantity 
         FROM user_inventory ui 
         JOIN items i ON ui.item_id = i.id 
         WHERE ui.user_id = ?";
        
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $inventory_items = $stmt->get_result();

// Simulasi data agar halaman bisa dilihat sebelum DB tersambung
$inventory_items = [
    ['name' => 'Dull Blade', 'type' => 'Weapon', 'rarity' => 'Common', 'quantity' => 12],
    ['name' => 'Wolf\'s Gravestone', 'type' => 'Weapon', 'rarity' => 'Legendary', 'quantity' => 1]
];
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
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['type'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($row['rarity']); ?></td>
                <td><?= htmlspecialchars($row['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>