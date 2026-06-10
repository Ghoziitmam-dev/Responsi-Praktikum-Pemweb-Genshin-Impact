<?php
session_start();
require_once 'config/config.php';

// Redirect otomatis jika user sudah memiliki sesi login aktif
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashbord.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}

// Proses validasi login jika ada request POST dari form frontend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password, role, selected_character_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password yang sudah di-hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['character_id'] = $user['selected_character_id'];

            header("Location: " . ($user['role'] === 'admin' ? "admin/dashbord.php" : "user/dashboard.php"));
            exit;
        }
    }
    $error_message = "Username atau password salah."; // Variabel ini bisa di-echo oleh frontend jika login gagal
}
?>