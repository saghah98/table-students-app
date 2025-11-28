<?php
// db_connect.php
try {
    $dbFile = __DIR__ . '/tablestudent.db'; // chemin vers ta base
    if (!file_exists($dbFile)) {
        // crée la base vide si elle n'existe pas
        $pdo = new PDO("sqlite:$dbFile");
    } else {
        $pdo = new PDO("sqlite:$dbFile");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

