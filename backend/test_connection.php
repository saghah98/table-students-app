<?php
// test_connection.php

$pdo = require 'db_connect.php';

if ($pdo) {
    echo "✅ Connection successful!";
} else {
    echo "❌ Connection failed!";
}
