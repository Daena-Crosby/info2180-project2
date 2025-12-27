<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$contactId = intval($_GET['id'] ?? 0);
if ($contactId <= 0) {
    die("Invalid contact ID.");
}

// Fetch contact
$stmt = $pdo->prepare("SELECT c.*, u.firstname AS assigned_first, u.lastname AS assigned_last, 
                              creator.firstname AS creator_first, creator.lastname AS creator_last
                       FROM Contacts c
                       LEFT JOIN Users u ON c.assigned_to = u.id
                       LEFT JOIN Users creator ON c.created_by = creator.id
                       WHERE c.id = :id");
$stmt->execute([':id' => $contactId]);
$contact = $stmt->fetch();

if (!$contact) {
    die("Contact not found.");
}

// Handle new note
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $createdBy = $_SESSION['user_id'];
    if (!empty($comment)) {
        $noteStmt = $pdo->prepare("INSERT INTO Notes (contact_id, comment, created_by, created_at) 
                                   VALUES (:contact_id, :comment, :created_by, NOW())");
        $noteStmt->execute([
            ':contact_id' => $contactId,
            ':comment'    => $comment,
            ':created_by' => $createdBy
        ]);
        // Update contact updated_at
        $pdo->prepare("UPDATE Contacts SET updated_at = NOW() WHERE id = :id")->execute([':id' => $contactId]);
        header("Location: contact_details.php?id=" . $contactId);
        exit();
    } else {
        $error = "Note cannot be empty.";
    }
}

// Fetch notes
$notesStmt = $pdo->prepare("SELECT n.comment, n.created_at, u.firstname, u.lastname 
                            FROM Notes n 
                            JOIN Users u ON n.created_by = u.id 
                            WHERE n.contact_id = :id 
                            ORDER BY n.created_at DESC");
$notesStmt->execute([':id' => $contactId]);
$notes = $notesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Details</title>
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
        <p><strong>Name:</strong> <?= htmlspecialchars($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
        <p><strong>Telephone:</strong> <?= htmlspecialchars($contact['telephone']) ?></p>
        <p><strong>Company:</strong> <?= htmlspecialchars($contact['company']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($contact['type']) ?> <button id="switchTypeBtn" class="btn btn-primary">Switch type</button></p>
        <p><strong>Created By:</strong> <?= htmlspecialchars($contact['creator_first'] . ' ' . $contact['creator_last']) ?></p>
        <p><strong>Assigned To:</strong> <?= htmlspecialchars($contact['assigned_first'] . ' ' . $contact['assigned_last']) ?> <button id="assignMeBtn" class="btn btn-primary">Assign to me</button></p>
        <p><strong>Created At:</strong> <?= htmlspecialchars($contact['created_at']) ?></p>
        <p><strong>Updated At:</strong> <?= htmlspecialchars($contact['updated_at']) ?></p>

        <h3>Notes</h3>
        <?php foreach ($notes as $note): ?>
          <div class="note">
            <p><?= htmlspecialchars($note['comment']) ?></p>
            <small>By <?= htmlspecialchars($note['firstname'] . ' ' . $note['lastname']) ?> on <?= htmlspecialchars($note['created_at']) ?></small>
          </div>
        <?php endforeach; ?>

        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST" action="contact_details.php?id=<?= $contactId ?>">
          <div class="form-group">
            <label>Add Note:</label>
            <textarea name="comment" required></textarea>
          </div>
          <button type="submit" class="btn">Add Note</button>
        </form>
      </div>
    </div>
  </main>
  <script src="assets/js/notes.js"></script>
  <script>
    document.getElementById('assignMeBtn').addEventListener('click', () => {
      fetch('update_contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${<?= $contactId ?>}&action=assign_me`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Assigned to you!');
          location.reload();
        } else {
          alert('Error: ' + data.error);
        }
      });
    });

    document.getElementById('switchTypeBtn').addEventListener('click', () => {
      fetch('update_contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${<?= $contactId ?>}&action=switch_type`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Type switched!');
          location.reload();
        } else {
          alert('Error: ' + data.error);
        }
      });
    });
  </script>
</body>
</html>