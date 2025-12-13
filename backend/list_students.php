<?php
header('Content-Type: application/json');
require 'db_connect.php'; // <--- Important !

try {
    $stmt = $conn->query("SELECT * FROM students ORDER BY id ASC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch(PDOException $e){
    echo json_encode([]);
}
?>

