<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Vérifier si l'identifiant de l'étudiant est passé en GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir l'identifiant en entier pour des raisons de sécurité

    // Mettre à jour le champ archive de l'étudiant dans la table etudiants
    $stmt = $pdo->prepare('UPDATE etudiants SET archive = 0 WHERE id = :id');
    $stmt->execute(['id' => $id]);

    header('Location: accueil.php'); // Redirection après désarchivation
    exit();
} else {
    header('Location: accueil.php?error=ID non fourni');
    exit();
}
?>
