<?php
session_start();
include 'config.php'; // Inclure votre fichier de connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = isset($_POST['etudiant_id']) ? (int) $_POST['etudiant_id'] : 0;
    $note1 = isset($_POST['note1']) ? trim($_POST['note1']) : null;
    $note2 = isset($_POST['note2']) ? trim($_POST['note2']) : null;
    $note3 = isset($_POST['note3']) ? trim($_POST['note3']) : null;
    $note4 = isset($_POST['note4']) ? trim($_POST['note4']) : null;

    // Vérifier si l'ID de l'étudiant est valide
    if ($etudiant_id > 0 && $note1 !== null && $note2 !== null && $note3 !== null && $note4 !== null) {
        // Calculer le total des notes et la moyenne
        $total_notes = $note1 + $note2 + $note3 + $note4;
        $moyenne = $total_notes / 4;

        // Assigner un statut basé sur la moyenne
        $statut = $moyenne > 10 ? 'admis' : 'recalé';

        // Mettre à jour les notes de l'étudiant
        try {
            $stmt = $pdo->prepare('UPDATE etudiants SET note1 = :note1, note2 = :note2, note3 = :note3, note4 = :note4, total_notes = :total_notes, moyenne = :moyenne, statut = :statut WHERE id = :id');
            $stmt->execute([
                ':note1' => $note1,
                ':note2' => $note2,
                ':note3' => $note3,
                ':note4' => $note4,
                ':total_notes' => $total_notes,
                ':moyenne' => $moyenne,
                ':statut' => $statut,
                ':id' => $etudiant_id
            ]);

            $_SESSION['message'] = 'Les notes ont été mises à jour avec succès.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Erreur lors de la mise à jour des notes : ' . htmlspecialchars($e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'Données invalides.';
    }

    // Rediriger vers la page d'accueil
    header('Location: accueil.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Ajouter des Notes</title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: #ffffff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter des Notes</h1>
        <form action="note.php" method="post">
            <fieldset>
                <legend>Ajouter des Notes</legend>
                
                <div class="form-group">
                    <label for="etudiant">Sélectionner un Étudiant</label>
                    <select id="etudiant" name="etudiant_id" class="form-control" required>
                        <option value="">Choisir un étudiant</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo htmlspecialchars($student['id']); ?>">
                                <?php echo htmlspecialchars($student['nom'] . ' ' . $student['prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="note1">Note Module 1</label>
                    <input type="number" class="form-control" id="note1" name="note1" step="any" placeholder="Note 1 /20" required>
                </div>
                
                <div class="form-group">
                    <label for="note2">Note Module 2</label>
                    <input type="number" class="form-control" id="note2" name="note2" step="any" placeholder="Note 2 /20" required>
                </div>
                
                <div class="form-group">
                    <label for="note3">Note Module 3</label>
                    <input type="number" class="form-control" id="note3" name="note3" step="any" placeholder="Note 3 /20" required>
                </div>
                
                <div class="form-group">
                    <label for="note4">Note Module 4</label>
                    <input type="number" class="form-control" id="note4" name="note4" step="any" placeholder="Note 4 /20" required>
                </div>
                
                <button type="submit" class="btn btn-custom">Ajouter les Notes</button>
                <a href="accueil.php" class="btn-btn">Retour</a>
            </fieldset>
        </form>
    </div>


</body>
</html>
