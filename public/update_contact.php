<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactId = intval($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($contactId <= 0) {
        echo json_encode(["error" => "Invalid contact ID"]);
        exit();
    }

    try {
        if ($action === 'assign_me') {
            $stmt = $pdo->prepare("UPDATE Contacts SET assigned_to = :userId, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':userId' => $_SESSION['user_id'], ':id' => $contactId]);
            echo json_encode(["success" => true]);
        } elseif ($action === 'switch_type') {
            // Get current type
            $stmt = $pdo->query("SELECT type FROM Contacts WHERE id = $contactId");
            $current = $stmt->fetch();
            $newType = $current['type'] === 'SALES LEAD' ? 'SUPPORT' : 'SALES LEAD';
            $stmt = $pdo->prepare("UPDATE Contacts SET type = :type, updated_at = NOW() WHERE id = :id");
            $stmt->execute([':type' => $newType, ':id' => $contactId]);
            echo json_encode(["success" => true, "new_type" => $newType]);
        } else {
            echo json_encode(["error" => "Invalid action"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Database error"]);
    }
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>