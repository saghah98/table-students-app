<?php
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/tablestudent.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        fullname TEXT NOT NULL,
        matricule TEXT UNIQUE NOT NULL,
        group_id TEXT NOT NULL
    )";

    $pdo->exec($sql);
    echo "✅ Table students created successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>