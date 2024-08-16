<?php
// Connexion à la base de données
include 'config.php';

try {
    // Requête pour récupérer tous les étudiants en Licence 1
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE niveau = :niveau");
    $stmt->execute(['niveau' => 'L1']); // 1 correspond au niveau Licence 1

    // Variables pour compter le nombre d'admis et de recalés
    $nombreAdmis = 0;
    $nombreRecale = 0;

    // Parcourir les résultats
    while ($etudiant = $stmt->fetch()) {
        if ($etudiant['statut'] === 'admis') {
            $nombreAdmis++;
        } elseif ($etudiant['statut'] === 'recalé') {
            $nombreRecale++;
        }
    }

  
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/licence.css">
</head>
<body>
    <div class="container">
        <div class="btn-container">
            <div class="btn">
                <a href="admis_licence1.php"><button class="btn-stylized">Admis</button></a>
            </div>

            <div class="btn">
                <a href="recales_licence1.php"><button class="btn-stylized">Recalé</button></a>
            </div>
        </div>
        <div class="btn-return">
            <button class="btn-stylized2" onclick="window.history.back();">Retour</button>
        </div>
    </div>
</body>
</html>
