<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $newPassword)) {
        $error = "New password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM Users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();

        if ($user && password_verify($currentPassword, $user['password'])) {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE Users SET password = :password WHERE id = :id");
            $updateStmt->execute([':password' => $hashed, ':id' => $userId]);
            $success = "Password updated successfully.";
        } else {
            $error = "Current password is incorrect.";
        }
    }
}

// Fetch user info
$stmt = $pdo->prepare("SELECT firstname, lastname, email, role FROM Users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account - Dolphin CRM</title>
  <link rel="stylesheet" href="../assets/css/account.css">
</head>
<body>
  <?php $activePage = 'account.php'; include '../includes/navbar.php'; ?>

  <main class="login-container">
    <div class="account-container">
      <div class="card">
        <div class="card-header">
          <h1>Profile Information</h1>
        </div>
        <div class="card-content">
          <div class="profile-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h1>Change Password</h1>
        </div>
        <div class="card-content">
          <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
          <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

          <form method="POST" action="account.php">
            <div class="form-group">
              <label for="current_password">Current Password:</label>
              <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
              <label for="new_password">New Password:</label>
              <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
              <label for="confirm_password">Confirm New Password:</label>
              <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Update Password</button>
          </form>
        </div>
      </div>
    </div>
  </main>
</body>
</html>