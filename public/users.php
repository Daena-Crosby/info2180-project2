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
  <link rel="stylesheet" href="../assets/css/users.css">
  <script src="../assets/js/users.js" defer></script>

</head>
<body>
  <?php $activePage = 'users.php'; include '../includes/navbar.php'; ?>

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