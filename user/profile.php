<?php
session_start();
require_once '../config/config.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mengambil profile_picture dari tabel users (u) DAN tabel characters (c)
$sql = "
    SELECT 
        u.username, 
        u.profile_picture AS foto_user, 
        c.name AS character_name, 
        c.profile_picture AS character_image 
    FROM users u 
    JOIN user_character uc ON u.id = uc.user_id 
    JOIN characters c ON uc.character_id = c.id 
    WHERE u.id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error Database: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    // Jika data tidak ditemukan
    $user_data = [
        'username' => 'User (Error Data)',
        'foto_user' => 'default_user.png',
        'character_name' => 'Belum ada karakter',
        'character_image' => 'default_char.png'
    ];
}

// Pastikan variabel foto user tidak kosong
$foto_profil_aktif = !empty($user_data['foto_user']) ? $user_data['foto_user'] : 'default_user.png';

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Genshin Impact</title>
</head>
<body style="font-family: sans-serif; padding: 20px; background-color: #e9e9e9; display: flex; justify-content: center;">

    <div style="background-color: white; border: 1px solid #ddd; padding: 30px 20px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <img src="../uploads/<?php echo htmlspecialchars($foto_profil_aktif); ?>" 
             alt="Foto Profil" 
             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 4px solid #4CAF50; margin-bottom: 10px; background-color: #f0f0f0;">
        
        <h2 style="margin: 0; color: #333; font-size: 24px;">
            <?php echo htmlspecialchars($user_data['username']); ?>
        </h2>
        <p style="margin: 5px 0 20px 0; color: #888; font-size: 14px;">Traveler Level 1</p>

        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

        <p style="margin: 0 0 10px 0; color: #666; font-size: 14px; font-weight: bold;">
            🌟 KARAKTER ANDALAN 🌟
        </p>

        <img src="../image/<?php echo htmlspecialchars($user_data['character_image']); ?>" 
             alt="Foto Karakter" 
             style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 3px solid #d4af37; margin-bottom: 10px; background-color: #f0f0f0;">
        
        <h3 style="margin: 0 0 25px 0; color: #d4af37; font-size: 20px;">
            <?php echo htmlspecialchars($user_data['character_name']); ?>
        </h3>

        <a href="dashboard.php" style="text-decoration: none;">
            <button style="padding: 12px 20px; cursor: pointer; background-color: #333; color: white; border: none; border-radius: 8px; width: 100%; font-weight: bold; transition: 0.3s;">
                Kembali ke Dashboard
            </button>
        </a>

    </div>

</body>
</html>