<?php
session_start();
 require_once '../config/config.php'; // Uncomment baris ini dan pastikan file koneksi DB Anda sesuai

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character_id'])) {
    $character_id = intval($_POST['character_id']);
    
    // Ambil nama karakter untuk disimpan di session (Opsional, tapi mempermudah UI)
     $stmt = $conn->prepare("SELECT name FROM characters WHERE id = ?");
     $stmt->bind_param("i", $character_id);
     $stmt->execute();
     $result = $stmt->get_result();
     $char_data = $result->fetch_assoc();
     $character_name = $char_data['name'];

    // Update data user di database (Contoh query)
     $stmt = $conn->prepare("UPDATE users SET selected_character_id = ? WHERE id = ?");
     $stmt->bind_param("ii", $character_id, $user_id);
     if($stmt->execute()) {
        // Simulasi jika berhasil update database
        $_SESSION['character_id'] = $character_id;
        $_SESSION['character_name'] = "Karakter Pilihan"; // Ganti dengan $character_name dari DB
        
        header("Location: dashboard.php");
        exit;
     }
}

// Ambil daftar karakter dari database (Contoh Dummy Data untuk UI jika DB belum siap)
// $result = $conn->query("SELECT * FROM characters");
$characters = [
    ['id' => 1, 'name' => 'Aether (Male Traveler)', 'element' => 'Anemo'],
    ['id' => 2, 'name' => 'Lumine (Female Traveler)', 'element' => 'Anemo']
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pilih Karakter - Genshin Impact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .character-card {
            border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; cursor: pointer;
        }
        .character-card:hover { border-color: gold; }
    </style>
</head>
<body>

<h1>Pilih Karakter Utama Anda</h1>
<p>Setiap perjalanan dimulai dengan satu langkah. Siapakah yang akan menjelajahi Teyvat?</p>

<form method="POST" action="">
    <?php foreach ($characters as $char): // Ganti $characters dengan $result jika pakai DB ?>
        <label class="character-card">
            <!-- Gambar bisa ditambahkan di sini: <img src="<?= $char['image_url'] ?>" width="100"> -->
            <input type="radio" name="character_id" value="<?= $char['id']; ?>" required>
            <strong><?= htmlspecialchars($char['name']); ?></strong><br>
            Elemen: <?= htmlspecialchars($char['element']); ?>
        </label>
    <?php endforeach; ?>
    
    <br><br>
    <button type="submit">Mulai Petualangan</button>
</form>

</body>
</html>
