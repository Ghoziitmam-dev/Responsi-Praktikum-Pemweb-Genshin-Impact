<?php
// Mengacak password 'admin123'
$password_asli = "user123";
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

echo "Password Asli: " . $password_asli . "<br><br>";
echo "Copy teks hash di bawah ini ke dalam database kamu:<br>";
echo "<strong style='color:blue;'>" . $password_hash . "</strong>";
?>