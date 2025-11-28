<?php
header('Content-Type: application/json');
require_once 'database.php';

$id = intval($_POST['id'] ?? 0);
$fullname = trim($_POST['fullname'] ?? '');
$matricule = trim($_POST['matricule'] ?? '');
$group_id = trim($_POST['group_id'] ?? '');

if(!$id || !$fullname || !$matricule || !$group_id){
  echo json_encode(['status'=>'error','message'=>'Missing fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE students SET fullname = ?, matricule = ?, group_id = ? WHERE id = ?");
  $stmt->execute([$fullname, $matricule, $group_id, $id]);
  echo json_encode(['status'=>'success','message'=>'Student updated']);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>