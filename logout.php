<?php
session_start();
// Hapus semua data sesi
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>