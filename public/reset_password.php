<?php
require_once 'db_connect.php';

// The password you want to use
$new_password = 'password123'; 

// Hash it securely
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the database
try {
    $stmt = $pdo->prepare("UPDATE Users SET password = :password WHERE email = :email");
    $stmt->execute([
        ':password' => $hashed_password,
        ':email' => 'admin@project2.com'
    ]);
    echo "Success! Admin password has been hashed and updated.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>