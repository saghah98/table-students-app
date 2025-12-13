<?php
header('Content-Type: application/json');
require 'db_connect.php';

$id = $_POST['id'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$matricule = $_POST['matricule'] ?? '';
$group_id = $_POST['group_id'] ?? '';

if(!$id){
    echo json_encode(["status"=>"error","message"=>"Missing ID"]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?");
    $stmt->execute([$fullname, $matricule, $group_id, $id]);

    echo json_encode(["status"=>"success","message"=>"Student updated"]);
} catch(PDOException $e){
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}
?>
