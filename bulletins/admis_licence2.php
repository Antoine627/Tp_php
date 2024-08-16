<?php
// Connexion à la base de données
include 'config.php';

try {
    // Requête pour récupérer tous les étudiants admis en Licence 1
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE niveau = :niveau AND statut = 'admis' AND archive = 0");

    $stmt->execute(['niveau' => 'L2']);

    $etudiants = $stmt->fetchAll();

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étudiants Admis en Licence 2</title>
    <link rel="stylesheet" href="css/statut.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Liste des Étudiants Admis en Licence 2</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($etudiant['id']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['moyenne']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="btn-return">
            <button class="btn-stylized2" onclick="window.history.back();">Retour</button>
        </div>
    </div>
</body>
</html>

