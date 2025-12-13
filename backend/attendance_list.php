<?php
header('Content-Type: text/html; charset=utf-8');
require 'db_connect.php';

try {
    // Récupérer les présences
    $stmt = $conn->query("
        SELECT a.id, s.fullname, s.matricule, s.group_id, a.date, a.status
        FROM attendance a
        JOIN students s ON a.student_id = s.id
        ORDER BY a.date DESC, s.fullname ASC
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des présences</title>
    <style>
        body { font-family: Arial; background: #f6f6f6; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #333; color: white; }
        .present { color: green; font-weight: bold; }
        .absent { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>📌 Liste des présences</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nom complet</th>
        <th>Matricule</th>
        <th>Groupe</th>
        <th>Date</th>
        <th>Statut</th>
    </tr>

    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['matricule']) ?></td>
                <td><?= htmlspecialchars($row['group_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td class="<?= $row['status'] === 'present' ? 'present' : 'absent' ?>">
                    <?= htmlspecialchars(ucfirst($row['status'])) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Aucune présence enregistrée.</td></tr>
    <?php endif; ?>

</table>

</body>
</html>
