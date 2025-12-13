<?php
$host = "localhost"; // ton serveur MySQL
$db   = "tablestudent_db"; // remplace par le nom de ta base
$user = "root"; // ton utilisateur MySQL
$pass = ""; // ton mot de passe MySQL (vide par défaut sur XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(["status"=>"error","message"=>$e->getMessage()]));
}
?>




