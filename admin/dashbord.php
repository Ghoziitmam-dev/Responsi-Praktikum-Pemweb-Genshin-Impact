<?php
session_start();
if($_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Admin Dashboard</h1>

<ul>
    <li><a href="characters.php">Kelola Karakter</a></li>
    <li><a href="items.php">Kelola Item</a></li>
    <li><a href="banners.php">Kelola Banner</a></li>
    <li><a href="users.php">Kelola User</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

</body>
</html>