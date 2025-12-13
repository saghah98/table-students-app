<?php
header('Content-Type: application/json');
require 'db_connect.php';

$id = $_POST['id'] ?? '';

if(!$id){
    echo json_encode(["status"=>"error","message"=>"Missing ID"]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["status"=>"success","message"=>"Student deleted"]);
} catch(PDOException $e){
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}
?>
