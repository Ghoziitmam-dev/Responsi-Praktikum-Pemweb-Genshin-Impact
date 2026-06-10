<?php
session_start();
require_once '../config/config.php';

// Pastikan user sudah login sebelum bisa melihat profil
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil detail data profil user sekaligus menggabungkannya dengan tabel karakter
$sql = "SELECT u.username, c.name AS character_name, c.element 
        FROM users u 
        LEFT JOIN characters c ON u.selected_character_id = c.id 
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc(); // Frontend bisa menggunakan $user_data['username'], dll.
?>