<?php
session_start();
require_once 'config/config.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Proses Registrasi saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Enkripsi password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user'; // Secara default mendaftar sebagai user/traveler

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    
    if ($stmt->execute()) {
        header("Location: index.php?status=registered");
        exit;
    }
    $error_message = "Gagal mendaftar. Username mungkin sudah digunakan."; // Echo variabel ini di frontend untuk menampilkan error
}
?>