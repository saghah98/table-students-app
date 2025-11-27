<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifie que les données POST existent
if (isset($_POST['student_id']) && isset($_POST['name']) && isset($_POST['group'])) {

    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $group = trim($_POST['group']);

    // Validation simple
    if ($student_id === "" || $name === "" || $group === "") {
        echo "❌ All fields are required!";
        exit;
    }

    $file = 'students.json';
    $students = [];

    // Charge le fichier existant
    if (file_exists($file)) {
        $students = json_decode(file_get_contents($file), true);
        if (!$students) $students = [];
    }

    // Ajoute le nouvel étudiant
    $students[] = [
        'student_id' => $student_id,
        'name' => $name,
        'group' => $group
    ];

    // Sauvegarde dans students.json
    file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));

    echo "✅ Student added successfully!";

} else {
    echo "❌ Form data not received!";
}
?>

