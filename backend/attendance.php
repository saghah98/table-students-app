<?php
header("Content-Type: application/json");
require "db_connect.php";

$input = json_decode(file_get_contents("php://input"), true);
$action = $_GET["action"] ?? ($input["action"] ?? "");

if ($action === "get_all") {
    try {
        $stmt = $conn->query("SELECT * FROM attendance ORDER BY student_id, session");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode([]);
    }
    exit;
}

if ($action === "save_many") {
    $records = $input["records"] ?? [];

    try {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, session, present, participated)
                                VALUES (?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE present=VALUES(present), participated=VALUES(participated)");

        foreach ($records as $r) {
            $stmt->execute([
                $r["student_id"],
                $r["session"],
                $r["present"],
                $r["participated"]
            ]);
        }

        echo json_encode(["status"=>"success","message"=>"Attendance saved"]);
    } catch (Exception $e) {
        echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
    }
    exit;
}
?>
