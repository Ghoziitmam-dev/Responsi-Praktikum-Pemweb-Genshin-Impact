<?php
session_start();
 require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$gacha_result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pull'])) {
    // Sistem Probabilitas (Drop Rate)
    // 1 - 1000
    $roll = rand(1, 1000);
    
    if ($roll <= 6) { // 0.6% chance
        $rarity = 'Legendary'; 
    } elseif ($roll <= 57) { // 5.1% chance
        $rarity = 'Epic';
    } else { // 94.3% chance
        $rarity = 'Common';
    }

    // 1. Ambil 1 item acak dari database berdasarkan rarity yang didapat
     $stmt = $conn->prepare("SELECT id, name FROM items WHERE rarity = ? ORDER BY RAND() LIMIT 1");
     $stmt->bind_param("s", $rarity);
     $stmt->execute();
     $item = $stmt->get_result()->fetch_assoc();
    
    // Simulasi hasil item jika DB belum terhubung:
    $item = ['id' => rand(1, 100), 'name' => "Item $rarity Acak", 'rarity' => $rarity];

    if ($item) {
        $item_id = $item['id'];
        $gacha_result = $item; // Simpan untuk ditampilkan di UI

        // 2. Cek apakah user sudah punya item ini di inventory
         $stmt_check = $conn->prepare("SELECT quantity FROM user_inventory WHERE user_id = ? AND item_id = ?");
         $stmt_check->bind_param("ii", $user_id, $item_id);
         $stmt_check->execute();
         $inv_result = $stmt_check->get_result();
        
         if ($inv_result->num_rows > 0) {
             $stmt_update = $conn->prepare("UPDATE user_inventory SET quantity = quantity + 1 WHERE user_id = ? AND item_id = ?");
             $stmt_update->bind_param("ii", $user_id, $item_id);
             $stmt_update->execute();
         } else {
            // Jika belum ada, insert item baru dengan quantity 1
             $stmt_insert = $conn->prepare("INSERT INTO user_inventory (user_id, item_id, quantity) VALUES (?, ?, 1)");
             $stmt_insert->bind_param("ii", $user_id, $item_id);
             $stmt_insert->execute();
         }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gacha (Wish) - Genshin Impact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .Legendary { color: gold; font-weight: bold; font-size: 1.5em; }
        .Epic { color: purple; font-weight: bold; }
        .Common { color: gray; }
    </style>
</head>
<body>

<h1>Sistem Permohonan (Wish)</h1>
<p>Uji keberuntungan Anda hari ini!</p>

<nav>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</nav>

<hr>

<form method="POST">
    <button type="submit" name="pull" value="1" style="padding: 15px 30px; font-size: 1.2em; cursor:pointer;">
        Gacha 1x (Pull)
    </button>
</form>

<?php if ($gacha_result): ?>
    <div style="margin-top: 20px; padding: 20px; border: 2px dashed #333;">
        <h2>Hasil Wish Anda:</h2>
        <p class="<?= $gacha_result['rarity']; ?>">
            ★ <?= htmlspecialchars($gacha_result['rarity']); ?> ★<br>
            <?= htmlspecialchars($gacha_result['name']); ?>
        </p>
    </div>
<?php endif; ?>

</body>
</html>
