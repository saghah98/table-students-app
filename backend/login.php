<?php
header('Content-Type: application/json');
session_start();
require_once 'database.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if(!$username || !$password){
  echo json_encode(['status'=>'error','message'=>'Missing credentials']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
  $stmt->execute([$username]);
  $row = $stmt->fetch();
  if(!$row || !password_verify($password, $row['password_hash'])){
    echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
    exit;
  }
  // store minimal session
  $_SESSION['admin_id'] = $row['id'];
  $_SESSION['admin_user'] = $row['username'];
  echo json_encode(['status'=>'success','message'=>'Logged in']);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>