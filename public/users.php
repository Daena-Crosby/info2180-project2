<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <script src="../assets/js/users.js" defer></script>

</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <img src="../assets/images/image.png" alt="Dolphin CRM" />
    </div>
    <ul class="nav-links">
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="users.php" class="active">Users</a></li>
      <li><a href="add_user.php">New User</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="add_contact.php" class="btn btn-primary">+ Add Contact</a></li>
    </ul>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </nav>

  <main class="dashboard">
    <section class="users">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody id="usersTable">
          <!-- Rows injected by users.js -->
        </tbody>
      </table>
    </section>
  </main>

  <script src="assets/js/users.js"></script>
</body>
</html>