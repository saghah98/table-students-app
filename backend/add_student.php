<?php
header('Content-Type: application/json');
require_once 'database.php';

$fullname = trim($_POST['fullname'] ?? '');
$matricule = trim($_POST['matricule'] ?? '');
$group_id = trim($_POST['group_id'] ?? '');

if(!$fullname || !$matricule || !$group_id){
  echo json_encode(['status'=>'error','message'=>'Missing fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)");
  $stmt->execute([$fullname, $matricule, $group_id]);
  echo json_encode(['status'=>'success','message'=>'Student added','id'=>$pdo->lastInsertId()]);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>'DB error: '.$e->getMessage()]);
}
?>

