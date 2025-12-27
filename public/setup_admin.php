<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

$pass = password_hash("password123", PASSWORD_DEFAULT);
$sql = "UPDATE Users SET password = '$pass' WHERE email = 'admin@project2.com'";
$pdo->query($sql);
echo "Admin password updated to 'password123'";
?>