<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$hasil_tarikan = []; 
$error_message = ""; 

// ==========================================
// 1. AMBIL SALDO DARI TABEL 'primogems'
// ==========================================
$stmt_user = $conn->prepare("SELECT amount FROM primogems WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_gems = $stmt_user->get_result();

if ($result_gems->num_rows > 0) {
    $user_info = $result_gems->fetch_assoc();
    $current_primogems = $user_info['amount'];
} else {
    // Jika user belum punya data dompet, buatkan otomatis dengan modal 16000
    $current_primogems = 16000;
    $insert_gems = $conn->prepare("INSERT INTO primogems (user_id, amount) VALUES (?, ?)");
    $insert_gems->bind_param("ii", $user_id, $current_primogems);
    $insert_gems->execute();
    $insert_gems->close();
}
$stmt_user->close();

// ==========================================
// 2. AMBIL POOL GACHA DARI TABEL ITEMS
// ==========================================
$pool_query = $conn->query("SELECT id, item_name, rarity, image, drop_rate FROM items WHERE drop_rate > 0");
$gacha_pool = [];
$total_weight = 0;

if ($pool_query) {
    while ($row = $pool_query->fetch_assoc()) {
        $gacha_pool[] = $row;
        $total_weight += (float)$row['drop_rate']; 
    }
}

// ==========================================
// 3. MESIN PENGACAK (RNG)
// ==========================================
function tarikGacha($pool, $total_weight) {
    if (empty($pool)) return null; 
    
    $random = (mt_rand() / mt_getrandmax()) * $total_weight;
    $current = 0;
    foreach ($pool as $item) {
        $current += (float)$item['drop_rate'];
        if ($random <= $current) {
            return $item;
        }
    }
    return $pool[0]; 
}

// ==========================================
// 4. JIKA TOMBOL GACHA DITEKAN
// ==========================================
if (isset($_POST['gacha_1x']) || isset($_POST['gacha_5x'])) {
    $jumlah_tarikan = isset($_POST['gacha_1x']) ? 1 : 5;
    $harga_per_tarikan = 160;
    $total_harga = $jumlah_tarikan * $harga_per_tarikan;

    // CEK SALDO
    if ($current_primogems >= $total_harga) {
        
        // Kurangi 'amount' di tabel 'primogems'
        $update_gems = $conn->prepare("UPDATE primogems SET amount = amount - ? WHERE user_id = ?");
        $update_gems->bind_param("ii", $total_harga, $user_id);
        $update_gems->execute();
        $update_gems->close();

        // Update variabel saldo untuk UI
        $current_primogems -= $total_harga;

        // Lakukan perulangan gacha
        for ($i = 0; $i < $jumlah_tarikan; $i++) {
            $item_dapat = tarikGacha($gacha_pool, $total_weight);
            
            if ($item_dapat) {
                $hasil_tarikan[] = $item_dapat;
                $id_barang = $item_dapat['id'];

                // Catat ke History
                $stmt_history = $conn->prepare("INSERT INTO gacha_history (user_id, item_id) VALUES (?, ?)");
                $stmt_history->bind_param("ii", $user_id, $id_barang);
                $stmt_history->execute();
                $stmt_history->close();

                // Masukkan ke Inventory
                $cek_inv = $conn->prepare("SELECT id, quantity FROM inventory WHERE user_id = ? AND item_id = ?");
                $cek_inv->bind_param("ii", $user_id, $id_barang);
                $cek_inv->execute();
                $result_inv = $cek_inv->get_result();

                if ($result_inv->num_rows > 0) {
                    $inv_data = $result_inv->fetch_assoc();
                    $update_inv = $conn->prepare("UPDATE inventory SET quantity = quantity + 1 WHERE id = ?");
                    $update_inv->bind_param("i", $inv_data['id']);
                    $update_inv->execute();
                    $update_inv->close();
                } else {
                    $insert_inv = $conn->prepare("INSERT INTO inventory (user_id, item_id, quantity) VALUES (?, ?, 1)");
                    $insert_inv->bind_param("ii", $user_id, $id_barang);
                    $insert_inv->execute();
                    $insert_inv->close();
                }
                $cek_inv->close();
            }
        }
    } else {
        $error_message = "Primogems kamu tidak cukup! Butuh " . $total_harga . " Primogems.";
    }
}

// ==========================================
// 5. AMBIL DATA HISTORY UNTUK UI KANAN
// ==========================================
$history_stmt = $conn->prepare("
    SELECT h.created_at, i.item_name, i.rarity 
    FROM gacha_history h 
    JOIN items i ON h.item_id = i.id 
    WHERE h.user_id = ? 
    ORDER BY h.created_at DESC LIMIT 15
");
$history_stmt->bind_param("i", $user_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
$history_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gacha - Genshin Impact</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        body {
            background-color: #2c2c2c;
            color: white;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('../image/background_gacha.jpg'); 
            background-size: cover;
            background-position: center;
        }
        .container {
            display: flex;
            gap: 50px;
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
            position: relative; 
        }
        .left-panel, .right-panel {
            width: 350px;
        }
        .primogems-display {
            position: absolute;
            top: -50px;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 20px;
            border: 2px solid #57C3E2;
            font-weight: bold;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="primogems-display">
            💎 Primogems: <?php echo number_format($current_primogems, 0, ',', '.'); ?>
        </div>

        <div class="left-panel">
            <h2 style="color: #FFD700; text-align: center;">Simulasi Gacha</h2>
            
            <?php if (!empty($error_message)): ?>
                <div style="background-color: #ff4d4d; color: white; padding: 10px; text-align: center; border-radius: 8px; font-weight: bold;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                <button type="submit" name="gacha_1x" style="background-color: #8BC34A; color: white; padding: 10px 20px; border-radius: 25px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">
                    Gacha 1x<br><small>💎 160</small>
                </button>
                <button type="submit" name="gacha_5x" style="background-color: #8BC34A; color: white; padding: 10px 20px; border-radius: 25px; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">
                    Gacha 5x<br><small>💎 800</small>
                </button>
            </form>

            <div style="text-align: center; margin-top: 30px;">
                <a href="dashboard.php" style="color: white; text-decoration: none; border-bottom: 1px solid white;">Kembali ke Dashboard</a>
            </div>
        </div>

        <div class="right-panel">
            <?php if (!empty($hasil_tarikan)): ?>
                <div style="background: rgba(255, 215, 0, 0.2); padding: 15px; border-radius: 10px; border: 1px solid #FFD700; margin-bottom: 20px;">
                    <h4 style="color: #FFD700; margin: 0 0 10px 0; text-align: center;">✨ Hasil Tarikan ✨</h4>
                    <?php foreach ($hasil_tarikan as $item): ?>
                        <p style="margin: 5px 0; font-size: 14px; color: <?php echo ($item['rarity'] >= 4) ? '#FFD700' : 'white'; ?>;">
                            <?php echo str_repeat('★', $item['rarity']) . " " . htmlspecialchars($item['item_name']); ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h3 style="border-bottom: 1px solid #555; padding-bottom: 10px;">History Gacha mu!</h3>
            <ul style="list-style: none; padding: 0; margin: 0; max-height: 300px; overflow-y: auto;">
                <?php while($row = $history_result->fetch_assoc()): ?>
                    <li style="border-bottom: 1px solid rgba(255,255,255,0.2); padding: 10px 0; font-size: 14px;">
                        <span style="color: #bbb; font-size: 12px; display: block;">
                            <?php echo date('H:i, d M Y', strtotime($row['created_at'])); ?>
                        </span>
                        <span style="color: <?php echo ($row['rarity'] >= 4) ? '#FFD700' : 'white'; ?>;">
                            <?php echo htmlspecialchars($row['item_name']); ?> (★<?php echo $row['rarity']; ?>)
                        </span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

</body>
</html>