<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginPage/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// JIKA TOMBOL SUBMIT DITEKAN
if (isset($_POST['submit_karakter'])) {
    $karakter_pilihan_id = $_POST['character_id'];

    // Masukkan user_id dan character_id ke tabel user_character
    $insert_stmt = $conn->prepare("INSERT INTO user_character (user_id, character_id) VALUES (?, ?)");
    $insert_stmt->bind_param("ii", $user_id, $karakter_pilihan_id);
    
    if ($insert_stmt->execute()) {
        $_SESSION['character_id'] = $karakter_pilihan_id;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Gagal menyimpan karakter: " . $conn->error;
    }
    $insert_stmt->close();
}

// AMBIL DAFTAR KARAKTER DARI DATABASE (Sesuaikan nama tabel karakternya, misal 'characters')
// Jika nama tabel karaktermu berbeda, ganti tulisan 'characters' di bawah ini
$query_karakter = "SELECT id, name FROM characters"; 
$hasil_karakter = $conn->query($query_karakter);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pilih Karakter</title>
</head>
<body>
    <h2>Pilih Karakter Genshin Impact Kamu</h2>
    
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="POST">
        <?php while($row = $hasil_karakter->fetch_assoc()): ?>
            <div>
                <input type="radio" id="char_<?php echo $row['id']; ?>" name="character_id" value="<?php echo $row['id']; ?>" required>
                <label for="char_<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </label>
            </div>
        <?php endwhile; ?>
        
        <br>
        <button type="submit" name="submit_karakter">Konfirmasi Pilihan</button>
    </form>
</body>
</html>