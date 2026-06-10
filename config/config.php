<?php
// Konfigurasi database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'genshin_db'; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>