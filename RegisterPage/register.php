<?php
// 1. Koneksi Database
session_start();
require_once '../config/config.php'; // Pastikan path ke config benar

$error_message = "";
$success_message = "";

// 2. Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Cek apakah password cocok
    if ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        // 3. Masukkan ke Database
        // Sesuaikan nama kolom (username, email, password, role) dengan tabelmu
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

        try {
            if ($stmt->execute()) {
                $success_message = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = "Gagal mendaftar: Username atau Email mungkin sudah terdaftar.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Genshin Impact - Register</title>
    <link rel="stylesheet" href="register.css">
    <style>
        .pesan-error {
            background-color: rgba(255, 0, 0, 0.2);
            border: 1px solid #ff4d4d;
            color: #ff9999;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="background-game"></div>
    <div class="container-register">
        <div class="kartu-register">
            
            <div class="wrapper-judul">
                <img src="../image/register.png" alt="Register" class="gambar-judul">
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="pesan-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <div class="grup-input">
                    <label>Username</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-4-8-4z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="text" name="username" placeholder="Masukkan username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>
                </div>

                <div class="grup-input">
                    <label>Email</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="email" name="email" placeholder="Masukkan email" required>
                    </div>
                </div>
                
                <div class="grup-input">
                    <label>Password</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="grup-input">
                    <label>Confirm Password</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="password" name="confirm_password" placeholder="Masukkan ulang pasword" required>
                    </div>
                </div>
                
                <button type="submit" class="tombol-register">Register</button>
            </form>
            
            <p class="teks-bawah">Sudah punya akun? <a href="../LoginPage/login.php">Login</a></p>
        </div>
    </div>

</body>
</html>