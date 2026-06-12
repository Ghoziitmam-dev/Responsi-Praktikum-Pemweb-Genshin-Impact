<?php
session_start();
require_once '../config/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/login.php");
    exit;
}

// Cegah admin masuk ke dashboard user biasa
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mengecek apakah user ini sudah ada di tabel user_character
$stmt = $conn->prepare("SELECT character_id FROM user_character WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Jika hasilnya 0 baris (berarti belum pernah pilih karakter)
if ($result->num_rows === 0) {
    header("Location: select_character.php");
    exit;
} else {
    // Jika sudah punya, ambil ID karakternya dan simpan di session
    $user_data = $result->fetch_assoc();
    $_SESSION['character_id'] = $user_data['character_id'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Traveler Dashboard - Genshin Impact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Selamat Datang, Traveler!</h1>
<p>Karakter Aktif Anda: <strong><?php echo htmlspecialchars($_SESSION['character_name'] ?? 'Karakter'); ?></strong></p>

<nav>
    <ul>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="inventory.php">Inventory</a></li>
        <li><a href="gacha.php">Gacha Item (Wish)</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<hr>
<p>Ad Astra Abyssosque! Selamat datang di Petualangan Hari Ini.</p>

</body>
</html>