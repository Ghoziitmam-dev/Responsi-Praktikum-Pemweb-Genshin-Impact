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
            
            <form action="">
                <div class="grup-input">
                    <label>Username / Email</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-4-8-4z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="text" placeholder="Masukkan Username / Email" required>
                    </div>
                </div>
                
                <div class="grup-input">
                    <label>Password</label>
                    <div class="kotak-input">
                        <svg class="ikon-input" viewBox="0 0 24 24">
                            <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                        <div class="garis-pembatas"></div>
                        <input type="password" placeholder="Masukkan Password" required>
                    </div>
                </div>
                
                <button type="submit" class="tombol-login">Login</button>
            </form>
            
            <p class="teks-bawah">Belum punya akun? <a href="../RegisterPage/register.php">Register</a></p>
        </div>
    </div>

</body>
</html>