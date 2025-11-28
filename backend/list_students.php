<?php
header('Content-Type: application/json');
require_once 'database.php';

try {
  $stmt = $pdo->query("SELECT id, fullname, matricule, group_id FROM students ORDER BY id ASC");
  $rows = $stmt->fetchAll();
  echo json_encode($rows);
} catch (Exception $e) {
  echo json_encode([]);
}
?>
