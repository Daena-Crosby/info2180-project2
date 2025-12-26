<?php
require 'db_connect.php';
$pass = password_hash("password123", PASSWORD_DEFAULT);
$sql = "UPDATE Users SET password = '$pass' WHERE email = 'admin@project2.com'";
$pdo->query($sql);
echo "Admin password updated to 'password123'";
?>