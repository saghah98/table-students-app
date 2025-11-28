<?php
header('Content-Type: application/json');
require_once 'database.php';

$id = intval($_POST['id'] ?? 0);
if(!$id){ echo json_encode(['status'=>'error','message'=>'Missing id']); exit; }

try {
  // delete attendance records first (optional)
  $pdo->prepare("DELETE FROM attendance WHERE student_id = ?")->execute([$id]);
  $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
  $stmt->execute([$id]);
  echo json_encode(['status'=>'success','message'=>'Student deleted']);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>