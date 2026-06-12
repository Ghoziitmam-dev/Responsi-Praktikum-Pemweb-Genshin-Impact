<?php
// admin/auth_check.php
// Pastikan sesi sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login dan role-nya adalah 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, tendang ke halaman login/indeks
    header("Location: ../index.php");
    exit;
}
?>