<?php
// database.php
// configure DB here
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'tableStudent');
define('DB_USER', 'root');
define('DB_PASS', ''); // put your mysql password

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'DB connection failed: '.$e->getMessage()]);
  exit;
}
?>