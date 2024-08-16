<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Vérifier si l'identifiant de l'étudiant est passé en GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir l'identifiant en entier pour des raisons de sécurité

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Copier l'étudiant dans la table Archivage
        $stmt = $pdo->prepare('INSERT INTO Archivage (nom, prenom, email, date_naiss, tel, niveau, matricule)
            SELECT nom, prenom, email, date_naiss, tel, niveau, matricule
            FROM etudiants WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Supprimer l'étudiant de la table principale
        $stmt = $pdo->prepare('DELETE FROM etudiants WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Valider la transaction
        $pdo->commit();

        // Redirection vers la page d'accueil après l'archivage
        header('Location: accueil.php');
        exit();
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    // Si l'identifiant n'est pas fourni, rediriger vers la page d'accueil avec un message d'erreur
    header('Location: accueil.php?error=Invalid ID');
    exit();
}
?>
