<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$contactId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$contactId) {
    echo "Invalid Contact ID.";
    exit();
}

// --- HANDLE ACTIONS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'assign_to_me') {
        $stmt = $pdo->prepare("UPDATE Contacts SET assigned_to = :user_id, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':user_id' => $_SESSION['user_id'], ':id' => $contactId]);
    
    } elseif ($action === 'switch_type') {
        $stmt = $pdo->prepare("UPDATE Contacts SET type = CASE WHEN type = 'Support' THEN 'Sales Lead' ELSE 'Support' END, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $contactId]);
    
    } elseif ($action === 'add_note') {
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($comment)) {
            $stmt = $pdo->prepare("INSERT INTO Notes (contact_id, comment, created_by, created_at) VALUES (:cid, :comment, :uid, NOW())");
            $stmt->execute([
                ':cid' => $contactId,
                ':comment' => $comment,
                ':uid' => $_SESSION['user_id']
            ]);
            $stmt = $pdo->prepare("UPDATE Contacts SET updated_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => $contactId]);
        }
    }
    header("Location: view_contact.php?id=" . $contactId);
    exit();
}

// --- FETCH DATA (GET) ---
$sql = "SELECT c.*, 
        u_created.firstname AS created_fn, u_created.lastname AS created_ln,
        u_assigned.firstname AS assigned_fn, u_assigned.lastname AS assigned_ln
        FROM Contacts c
        LEFT JOIN Users u_created ON c.created_by = u_created.id
        LEFT JOIN Users u_assigned ON c.assigned_to = u_assigned.id
        WHERE c.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $contactId]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    die("Contact not found.");
}

$noteSql = "SELECT n.*, u.firstname, u.lastname 
            FROM Notes n 
            JOIN Users u ON n.created_by = u.id 
            WHERE n.contact_id = :id 
            ORDER BY n.created_at DESC";
$noteStmt = $pdo->prepare($noteSql);
$noteStmt->execute([':id' => $contactId]);
$notes = $noteStmt->fetchAll(PDO::FETCH_ASSOC);

$createdDate = new DateTime($contact['created_at']);
$updatedDate = new DateTime($contact['updated_at']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($contact['firstname']) ?> - Dolphin CRM</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="dolphin_logo.png" alt="Logo"> Dolphin CRM
        </div>
        <nav>
            <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a>
            <a href="add_contact.php"><i class="fas fa-user-plus"></i> New Contact</a>
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
            <hr style="border: 0; border-top: 1px solid #374151; margin: 10px 0;">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="contact-header">
            <div class="profile-info">
                <div class="avatar">
                    <img src="https://ui-avatars.com/api/?name=<?= $contact['firstname'] ?>+<?= $contact['lastname'] ?>&background=random" alt="Avatar">
                </div>
                <div>
                    <h1><?= htmlspecialchars($contact['title'] . '. ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></h1>
                    <p class="meta-text">
                        Created on <?= $createdDate->format('F j, Y') ?> by <?= htmlspecialchars($contact['created_fn'] . ' ' . $contact['created_ln']) ?>
                    </p>
                    <p class="meta-text">
                        Updated on <?= $updatedDate->format('F j, Y') ?>
                    </p>
                </div>
            </div>
            
            <div class="action-buttons">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="assign_to_me">
                    <button type="submit" class="btn-hand">
                        <i class="fa fa-hand-paper-o"></i> Assign to me
                    </button>
                </form>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="switch_type">
                    <button type="submit" class="btn-switch">
                        <i class="fa fa-exchange"></i> Switch to <?= $contact['type'] === 'Sales Lead' ? 'Support' : 'Sales Lead' ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <label>Email</label>
                <p><?= htmlspecialchars($contact['email']) ?></p>
            </div>
            <div class="detail-item">
                <label>Telephone</label>
                <p><?= htmlspecialchars($contact['telephone']) ?></p>
            </div>
            <div class="detail-item">
                <label>Company</label>
                <p><?= htmlspecialchars($contact['company']) ?></p>
            </div>
            <div class="detail-item">
                <label>Assigned To</label>
                <p><?= htmlspecialchars($contact['assigned_fn'] . ' ' . $contact['assigned_ln']) ?></p>
            </div>
        </div>

        <div class="notes-section">
            <h3><i class="fa fa-pencil"></i> Notes</h3>
            
            <?php foreach ($notes as $note): 
                $noteDate = new DateTime($note['created_at']);
            ?>
                <div class="note-card">
                    <h4><?= htmlspecialchars($note['firstname'] . ' ' . $note['lastname']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($note['comment'])) ?></p>
                    <span class="note-date"><?= $noteDate->format('F j, Y \a\t g:ia') ?></span>
                </div>
            <?php endforeach; ?>

            <div class="add-note-area">
                <h4>Add a note about <?= htmlspecialchars($contact['firstname']) ?></h4>
                <form method="POST">
                    <input type="hidden" name="action" value="add_note">
                    <textarea name="comment" required placeholder="Enter details here..."></textarea>
                    <button type="submit" class="btn-primary">Add Note</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>