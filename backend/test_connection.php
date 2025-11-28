<?php
require 'config.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connection successful!";
} catch(PDOException $e) {
    echo "❌ DB connection failed: " . $e->getMessage();
}
?>
