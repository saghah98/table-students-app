<?php
$pdo = require 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$session_id = $data['session_id'] ?? '';

if (!$session_id) {
    echo json_encode(['status' => 'error', 'message' => 'Session ID required']);
    exit;
}

$stmt = $pdo->prepare("UPDATE attendance_sessions SET status = 'closed' WHERE id = ?");
try {
    $stmt->execute([$session_id]);
    echo json_encode(['status' => 'success', 'message' => "Session $session_id closed."]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
