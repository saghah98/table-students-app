<?php
$pdo = require 'db_connect.php';

$sql = "CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    course_id TEXT NOT NULL,
    group_id TEXT NOT NULL,
    date TEXT NOT NULL,
    opened_by TEXT NOT NULL,
    status TEXT NOT NULL
)";

$pdo->exec($sql);

echo "✅ Table attendance_sessions created successfully!";
