<?php
header('Content-Type: application/json');
require_once 'database.php';

// support two actions:
// GET: ?action=get_all -> returns all attendance records
// POST: JSON { action: 'save_many', records: [ {student_id, session, present, participated}, ... ] }

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET'){
  $action = $_GET['action'] ?? '';
  if($action === 'get_all'){
    $stmt = $pdo->query("SELECT student_id, session, present, participated FROM attendance");
    $rows = $stmt->fetchAll();
    echo json_encode($rows);
    exit;
  }
  echo json_encode(['status'=>'error','message'=>'Invalid GET action']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if(!$input){
  echo json_encode(['status'=>'error','message'=>'No JSON body']);
  exit;
}

$action = $input['action'] ?? '';

if($action === 'save_many'){
  $records = $input['records'] ?? [];
  if(!is_array($records)){
    echo json_encode(['status'=>'error','message'=>'Records must be array']);
    exit;
  }
  try {
    $pdo->beginTransaction();
    $upsert = $pdo->prepare(
      "INSERT INTO attendance (student_id, session, present, participated)
       VALUES (?, ?, ?, ?)
       ON DUPLICATE KEY UPDATE present = VALUES(present), participated = VALUES(participated)"
    );
    foreach($records as $r){
      $sid = intval($r['student_id'] ?? 0);
      $session = intval($r['session'] ?? 0);
      $present = intval($r['present'] ?? 0);
      $participated = intval($r['participated'] ?? 0);
      if(!$sid || !$session) continue;
      $upsert->execute([$sid, $session, $present, $participated]);
    }
    $pdo->commit();
    echo json_encode(['status'=>'success','message'=>'Attendance saved']);
  } catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
  }
  exit;
}

echo json_encode(['status'=>'error','message'=>'Invalid action']);
?>