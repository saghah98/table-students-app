<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$studentsFile = 'students.json';
if (!file_exists($studentsFile)) {
    die("❌ students.json not found!");
}

$students = json_decode(file_get_contents($studentsFile), true);
if (!$students) $students = [];

$date = date('Y-m-d');
$attendanceFile = "attendance_$date.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($attendanceFile)) {
        echo "Attendance for today has already been taken.";
        exit;
    }

    $attendance = [];
    foreach ($students as $s) {
        $id = $s['student_id'];
        $status = isset($_POST["status_$id"]) && $_POST["status_$id"] === 'present' ? 'present' : 'absent';
        $attendance[] = ['student_id'=>$id, 'status'=>$status];
    }

    file_put_contents($attendanceFile, json_encode($attendance, JSON_PRETTY_PRINT));
    echo "✅ Attendance saved for $date!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Take Attendance</title>
</head>
<body>
<h2>Take Attendance for <?php echo $date; ?></h2>
<form method="POST">
<table border="1">
<tr><th>ID</th><th>Name</th><th>Status</th></tr>
<?php foreach($students as $s): ?>
<tr>
<td><?php echo $s['student_id']; ?></td>
<td><?php echo $s['name']; ?></td>
<td>
<label><input type="radio" name="status_<?php echo $s['student_id']; ?>" value="present" checked> Present</label>
<label><input type="radio" name="status_<?php echo $s['student_id']; ?>" value="absent"> Absent</label>
</td>
</tr>
<?php endforeach; ?>
</table>
<button type="submit">Submit Attendance</button>
</form>
</body>
</html>

