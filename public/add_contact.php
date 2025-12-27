<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = htmlspecialchars($_POST['title'] ?? '');
    $firstname  = htmlspecialchars($_POST['firstname'] ?? '');
    $lastname   = htmlspecialchars($_POST['lastname'] ?? '');
    $email      = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $telephone  = htmlspecialchars($_POST['telephone'] ?? '');
    $company    = htmlspecialchars($_POST['company'] ?? '');
    $type       = htmlspecialchars($_POST['type'] ?? '');
    $assignedTo = intval($_POST['assigned_to'] ?? 0);
    $createdBy  = $_SESSION['user_id'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($company) || empty($type)) {
        $error = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Contacts 
                (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at)
                VALUES (:title, :firstname, :lastname, :email, :telephone, :company, :type, :assigned_to, :created_by, NOW(), NOW())");
            $stmt->execute([
                ':title'       => $title,
                ':firstname'   => $firstname,
                ':lastname'    => $lastname,
                ':email'       => $email,
                ':telephone'   => $telephone,
                ':company'     => $company,
                ':type'        => $type,
                ':assigned_to' => $assignedTo,
                ':created_by'  => $createdBy
            ]);
            $success = "Contact added successfully.";
        } catch (PDOException $e) {
            $error = "Error adding contact: " . $e->getMessage();
        }
    }
}

// Fetch users for "Assigned To" dropdown
$usersStmt = $pdo->query("SELECT id, firstname, lastname FROM Users ORDER BY firstname ASC");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Contact</title>
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
      <li><a href="add_user.php">New User</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="add_contact.php" class="btn btn-primary">+ Add Contact</a></li>
    </ul>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </nav>
  <main class="dashboard">
    <div class="card">
      <div class="card-content">
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="POST" action="add_contact.php">
          <div class="form-group">
            <label>Title:</label>
            <select name="title">
              <option value="Mr.">Mr.</option>
              <option value="Ms.">Ms.</option>
              <option value="Mrs.">Mrs.</option>
              <option value="Dr.">Dr.</option>
              <option value="Prof.">Prof.</option>
            </select>
          </div>

          <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="firstname" required>
          </div>

          <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="lastname" required>
          </div>

          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
          </div>

          <div class="form-group">
            <label>Telephone:</label>
            <input type="text" name="telephone">
          </div>

          <div class="form-group">
            <label>Company:</label>
            <input type="text" name="company" required>
          </div>

          <div class="form-group">
            <label>Type:</label>
            <select name="type" required>
              <option value="SALES LEAD">Sales Lead</option>
              <option value="SUPPORT">Support</option>
            </select>
          </div>

          <div class="form-group">
            <label>Assigned To:</label>
            <select name="assigned_to">
              <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" class="btn">Save Contact</button>
        </form>
      </div>
    </div>
  </main>
</body>
</html>