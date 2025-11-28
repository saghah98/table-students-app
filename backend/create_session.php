<?php
$pdo = require 'db_connect.php';

// lecture des données POST (form ou JSON)
$data = json_decode(file_get_contents('php://input'), true);

$course_id = $data['course_id'] ?? '';
$group_id = $data['group_id'] ?? '';
$opened_by = $data['opened_by'] ?? '';

if (!$course_id || !$group_id || !$opened_by) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

$date = date('Y-m-d H:i:s');
$status = 'open';

$stmt = $pdo->prepare("INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$course_id, $group_id, $date, $opened_by, $status]);
    $session_id = $pdo->lastInsertId();
    echo json_encode(['status' => 'success', 'session_id' => $session_id]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
