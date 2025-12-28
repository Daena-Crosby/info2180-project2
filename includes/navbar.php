<?php $activePage = $activePage ?? ''; ?>
<nav class="navbar">
    <div class="logo">
      <img src="../assets/images/dolphin_logo.png" alt="Dolphin CRM" />
    </div>
    <ul class="nav-links">
      <li><a href="dashboard.php"<?php if ($activePage === 'dashboard.php') echo ' class="active"'; ?>>Dashboard</a></li>
      <li><a href="users.php"<?php if ($activePage === 'users.php') echo ' class="active"'; ?>>Users</a></li>
      <li><a href="add_user.php"<?php if ($activePage === 'add_user.php') echo ' class="active"'; ?>>New User</a></li>
      <li><a href="account.php"<?php if ($activePage === 'account.php') echo ' class="active"'; ?>>Account</a></li>
      <li><a href="add_contact.php"<?php if ($activePage === 'add_contact.php') echo ' class="active"'; ?>>Add Contact</a></li>
    </ul>
    <div class="actions">
      <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
  </nav>