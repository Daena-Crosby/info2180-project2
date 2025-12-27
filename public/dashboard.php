<?php
session_start();

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dolphin CRM - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="../assets/js/script.js" defer></script>
</head>
<body>
  <header class="topbar">
    <div class="logo">
      <img src="assets/images/image.png" alt="Dolphin CRM" style="height: 40px; width: auto;" />
    </div>
    <h1>Dashboard</h1>
    <div class="actions">
      <a href="add_contact.php" class="btn btn-primary">+ Add Contact</a>
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </header>

  <main class="dashboard">
    <!-- Search + Filters -->
    <section class="filters">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search contacts..." />
      </div>
      <div class="filter-buttons">
        <span>Filter By:</span>
        <button class="btn filter-btn active" data-filter="All">All</button>
        <button class="btn filter-btn" data-filter="Sales Leads">Sales Leads</button>
        <button class="btn filter-btn" data-filter="Support">Support</button>
        <button class="btn filter-btn" data-filter="Assigned to me">Assigned to me</button>
      </div>
    </section>

    <!-- Contacts Table -->
    <section class="contacts">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Type</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="contactsTable">
          <!-- Rows injected by dashboard.js -->
        </tbody>
      </table>
    </section>
  </main>

  <script src="assets/js/dashboard.js"></script>
</body>
</html>