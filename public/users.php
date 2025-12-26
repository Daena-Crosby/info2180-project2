<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

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
  <link rel="stylesheet" href="assets/css/users.css" />
</head>
<body>
  <header class="topbar">
    <div class="logo">
      <img src="assets/images/image.png" alt="Dolphin CRM" style="height: 40px; width: auto;" />
    </div>
    <h1>User Management</h1>
    <div class="actions">
      <a href="add_user.php" class="btn btn-primary">Add New User</a>
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </header>

  <main>
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
  </main>

  <script src="assets/js/users.js"></script>
</body>
</html>