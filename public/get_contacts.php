<?php
session_start();
require_once 'config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$filter = $_GET['filter'] ?? 'All';
$userId = $_SESSION['user_id'];

$query = "SELECT id, title, firstname, lastname, email, company, type, assigned_to FROM Contacts";
$conditions = [];
$params = [];

if ($filter === 'Sales Leads') {
    $conditions[] = "type = 'SALES LEAD'";
} elseif ($filter === 'Support') {
    $conditions[] = "type = 'SUPPORT'";
} elseif ($filter === 'Assigned to me') {
    $conditions[] = "assigned_to = :userId";
    $params[':userId'] = $userId;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($contacts);