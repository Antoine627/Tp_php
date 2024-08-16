<?php
session_start();
include 'config.php'; // Inclure votre fichier de connexion à la base de données

// Récupérer la liste des étudiants pour le menu déroulant
$students = [];
try {
    $stmt = $pdo->prepare('SELECT id, nom, prenom FROM etudiants WHERE archive = 0');
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des étudiants : ' . htmlspecialchars($e->getMessage());
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
