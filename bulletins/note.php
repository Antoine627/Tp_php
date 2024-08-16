<?php
session_start();
include 'config.php'; // Inclure votre fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $etudiant_id = isset($_POST['etudiant_id']) ? trim($_POST['etudiant_id']) : '';
    $note1 = isset($_POST['note1']) ? trim($_POST['note1']) : '';
    $note2 = isset($_POST['note2']) ? trim($_POST['note2']) : '';
    $note3 = isset($_POST['note3']) ? trim($_POST['note3']) : '';
    $note4 = isset($_POST['note4']) ? trim($_POST['note4']) : '';

    // Calcul du total des notes et de la moyenne
    $total_notes = $note1 + $note2 + $note3 + $note4;
    $moyenne = $total_notes / 4;

    // Assigner un statut basé sur la moyenne
    $statut = $moyenne > 10 ? 'admis' : 'recalé';

    try {
        // Préparer la requête SQL pour mettre à jour les notes de l'étudiant
        $stmt = $pdo->prepare('UPDATE etudiants SET note1 = :note1, note2 = :note2, note3 = :note3, note4 = :note4, total_notes = :total_notes, moyenne = :moyenne, statut = :statut WHERE id = :etudiant_id');

        // Exécuter la requête avec les données du formulaire
        $stmt->execute([
            ':note1' => $note1,
            ':note2' => $note2,
            ':note3' => $note3,
            ':note4' => $note4,
            ':total_notes' => $total_notes,
            ':moyenne' => $moyenne,
            ':statut' => $statut,
            ':etudiant_id' => $etudiant_id
        ]);

        // Ajouter un message de succès dans la session
        $_SESSION['message'] = 'Notes mises à jour avec succès.';
        header('Location: accueil.php'); // Redirection après ajout
        exit();
    } catch (PDOException $e) {
        echo 'Erreur lors de la mise à jour des notes : ' . htmlspecialchars($e->getMessage());
    }
} else {
    $_SESSION['error'] = 'Les données POST ne sont pas présentes.';
    header('Location: ajouter_note.php'); // Redirection en cas de problème
    exit();
}

