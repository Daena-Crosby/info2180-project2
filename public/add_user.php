<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = htmlspecialchars($_POST['firstname'] ?? '');
    $lastname  = htmlspecialchars($_POST['lastname'] ?? '');
    $email     = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password  = $_POST['password'] ?? '';
    $role      = htmlspecialchars($_POST['role'] ?? 'Member');

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } else {
        try {
            // Hash password securely using bcrypt
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO Users (firstname, lastname, email, password, role, created_at)
                                   VALUES (:firstname, :lastname, :email, :password, :role, NOW())");
            $stmt->execute([
                ':firstname' => $firstname,
                ':lastname'  => $lastname,
                ':email'     => $email,
                ':password'  => $hashed,
                ':role'      => $role
            ]);
            $success = "User added successfully.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already exists.";
            } else {
                $error = "Error adding user: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <img src="../assets/images/image.png" alt="Dolphin CRM" />
    </div>
    <ul class="nav-links">
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="users.php">Users</a></li>
      <li><a href="add_user.php" class="active">New User</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="add_contact.php" class="btn btn-primary">+ Add Contact</a></li>
    </ul>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </nav>
  <main class="login-container">
    <div class="card">
      <div class="card-header">
        <h1>Add New User</h1>
      </div>
      <div class="card-content">
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="POST" action="add_user.php">
          <div class="form-group">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>
          </div>

          <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>
          </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
          </div>

          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
          </div>

          <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role">
              <option value="Member">Member</option>
              <option value="Admin">Admin</option>
            </select>
          </div>

          <button type="submit" class="btn">Add User</button>
        </form>
      </div>
    </div>
  </main>
</body>
</html>