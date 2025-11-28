<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance = $_POST['attendance']; // tableau ['student_id'=>status]
    $session_date = date('Y-m-d');

    foreach ($attendance as $student_id => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, session, present, absent) VALUES (?, ?, ?, ?)");
        $present = $status === 'present' ? 1 : 0;
        $absent = $status === 'absent' ? 1 : 0;
        $stmt->execute([$student_id, 'S1', $present, $absent]); // S1 peut être dynamique
    }

    echo json_encode(['status'=>'success','message'=>'Attendance saved.']);
}
?>


