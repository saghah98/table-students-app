<?php
require 'config.php';
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  echo json_encode(['status'=>'error','message'=>'DB Connection failed: '.$e->getMessage()]);
  exit;
}
?>



