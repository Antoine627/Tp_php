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

// Vérifier si l'identifiant de l'admin est passé en GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir l'identifiant en entier pour des raisons de sécurité

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Supprimer l'admin de la table principale
        $stmt = $pdo->prepare('DELETE FROM admins WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Valider la transaction
        $pdo->commit();

        // Redirection vers la page d'accueil après l'archivage
        header('Location: admin.php');
        exit();
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    // Si l'identifiant n'est pas fourni, rediriger vers la page d'accueil avec un message d'erreur
    header('Location: admin.php?error=Invalid ID');
    exit();
}

