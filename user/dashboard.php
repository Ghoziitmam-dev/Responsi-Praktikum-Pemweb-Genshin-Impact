<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Cegah admin masuk ke dashboard user biasa (opsional, jika ingin dipisah total)
if ($_SESSION['role'] == 'admin') {
    header("Location: ../admin/dashbord.php");
    exit;
}

// Cek apakah user sudah memilih karakter
// Asumsi: Saat login atau pilih karakter, Anda menyimpan 'character_id' di session
if (!isset($_SESSION['character_id']) || empty($_SESSION['character_id'])) {
    // Jika belum pilih karakter, paksa ke halaman pemilihan karakter
    header("Location: select_character.php");
    exit;
}
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