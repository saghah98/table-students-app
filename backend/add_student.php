<?php
header('Content-Type: application/json');
require 'db_connect.php';

$fullname = $_POST['fullname'] ?? '';
$matricule = $_POST['matricule'] ?? '';
$group_id = $_POST['group_id'] ?? '';

if(!$fullname || !$matricule || !$group_id){
    echo json_encode(["status"=>"error","message"=>"Missing fields"]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO students(fullname, matricule, group_id) VALUES(?,?,?)");
    $stmt->execute([$fullname, $matricule, $group_id]);

    echo json_encode(["status"=>"success","message"=>"Student added"]);
} catch(PDOException $e){
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}
?>


