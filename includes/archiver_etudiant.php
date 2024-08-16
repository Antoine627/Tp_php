<?php
session_start(); // Démarrer la session
include '../db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: ../login.php');
    exit();
}

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Mettre à jour l'étudiant pour le marquer comme archivé
    $stmt = $pdo->prepare('UPDATE etudiants SET archive = 1, date_archiv = NOW() WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Rediriger vers la page d'accueil après l'archivage
    header('Location: accueil.php');
    exit();
}

// Récupérer les étudiants archivés
$stmt_archive = $pdo->prepare('SELECT * FROM etudiants WHERE archive = 1');
$stmt_archive->execute();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étudiants Archivés</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Étudiants Archivés</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date Naissance</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Niveau</th>
                <th>Date Archivage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($etudiant = $stmt_archive->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($etudiant['id']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['date_naiss']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['tel']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['niveau']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['date_archiv']); ?></td>
                    <td>
                        <a href="unarchive.php?id=<?php echo $etudiant['id']; ?>">Désarchiver</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="accueil.php">Retour</a>
</body>
</html>
