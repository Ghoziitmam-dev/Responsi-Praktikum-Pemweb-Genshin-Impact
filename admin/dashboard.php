<?php
// admin/dashboard.php
require_once 'auth_check.php'; // Panggil proteksi di sini
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Genshin Gacha</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header>
        <h1>Admin Dashboard</h1>
        <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
    </header>

    <nav>
        <ul>
            <li><a href="items.php">Kelola Item</a></li>
            <li><a href="users.php">Kelola User</a></li>
            <li><a href="manage_item.php">Kelola Banner</a></li>
            <hr>
            <li><a href="../logout.php" style="color: red;">Logout</a></li>
        </ul>
    </nav>

    <section>
        <h3>Statistik Sistem</h3>
        <p>Gunakan menu di atas untuk mengelola data Anda.</p>
    </section>

</body>
</html>