<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php'; // CSRF functions


// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request.";
    } else {
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "Please enter your Email and Password.";
        } else {
            $stmt = $pdo->prepare("SELECT id, password, role FROM Users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
      
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; 
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dolphin CRM - Login</title>
  <link rel="stylesheet" href="../assets/css/login.css">
  <script src="../assets/js/script.js" defer></script>
</head>
<body>
  <div class="login-container">
    <div class="card">
      <div class="card-header">
        <div class="logo">
          <img src="..\assets\images\dolphin_logo.png" alt="Dolphin CRM" />
        </div>
        <h1>Login</h1>
      </div>

      <!-- Show error if exists -->
      <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <form class="login-form" action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>" />
        <div class="card-content">
          <div class="form-group">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" placeholder="Enter your email" required />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter your password" required />
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn">Login</button>
        </div>
      </form>

      <div class="copyright">
        Copyright © 2025 Dolphin CRM
      </div>
    </div>
  </div>
</body>
</html>