<?php
header('Content-Type: application/json');
require 'db_connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=?");
$stmt->execute([$username, $password]);

if($stmt->rowCount() > 0){
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid login"]);
}
