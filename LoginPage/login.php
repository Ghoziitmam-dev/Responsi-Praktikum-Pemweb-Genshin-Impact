<?php
// LoginPage/login.php
session_start();

// Panggil file koneksi. Gunakan ../ untuk naik satu folder dari LoginPage
require_once '../config/config.php';

$error_message = "";

// Jika tombol login ditekan
if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan Prepared Statement untuk mencegah celah keamanan SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan di database
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi kecocokan password yang diketik dengan hash di database
        if (password_verify($password, $user['password'])) {
            // Login sukses, simpan data ke session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect ke halaman dashboard
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            $error_message = "Password yang kamu masukkan salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genshin Impact - Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="background-game"></div>
    <div class="container-login">
        <div class="kartu-login">
            
            <div class="wrapper-judul">
                <img src="../image/login.png" alt="Login" class="gambar-judul">
            </div>
            
           <form action="" method="POST"> <div class="grup-input">
                    <label>Username / Email</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-4-8-4z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="text" name="username" placeholder="Masukkan Username / Email" required>
                    </div>
                </div>
                
                <div class="grup-input">
                    <label>Password</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="password" name="password" placeholder="Masukkan Password" required>
                    </div>
                </div>
                
                <button type="submit" name="login_btn" class="tombol-login">Login</button>
            </form>
            
            <p class="teks-bawah">Belum punya akun? <a href="../RegisterPage/register.php">Register</a></p>
        </div>
    </div>

</body>
</html>