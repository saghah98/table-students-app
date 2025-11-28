<?php
// api/add_student.php
require_once __DIR__ . '/../inc/db_connect.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['success'=>false,'error'=>'Method not allowed'],405);
$input = json_decode(file_get_contents('php://input'), true);

$student_id = trim($input['studentId'] ?? '');
$last = trim($input['lastName'] ?? '');
$first = trim($input['firstName'] ?? '');
$email = trim($input['email'] ?? '');

$errors = [];
if ($student_id === '' || !preg_match('/^[0-9]+$/', $student_id)) $errors['studentId']='Invalid';
if ($last === '' ) $errors['lastName']='Required';
if ($first === '' ) $errors['firstName']='Required';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email']='Invalid';

if (!empty($errors)) json_response(['success'=>false,'errors'=>$errors],422);

$pdo = getPDO();
try {
    $stmt = $pdo->prepare("INSERT INTO students (student_id, last_name, first_name, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student_id, $last, $first, $email]);
    $id = $pdo->lastInsertId();
    json_response(['success'=>true,'id'=>$id]);
} catch (PDOException $e) {
    json_response(['success'=>false,'error'=> ($e->getCode()==23000 ? 'Student ID exists' : 'DB error')],500);
}




