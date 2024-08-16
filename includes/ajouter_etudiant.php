<?php
session_start(); // Démarrer la session
include '../db.php';

function generateMatricule($pdo) {
    do {
        $matricule = 'ETU' . strtoupper(bin2hex(random_bytes(4))); // Génère un matricule unique
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM etudiants WHERE matricule = :matricule');
        $stmt->execute(['matricule' => $matricule]);
        $count = $stmt->fetchColumn();
    } while ($count > 0); // Vérifie si le matricule existe déjà

    return $matricule;
}



// Initialiser la variable $matricule
$matricule = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_student') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naiss = trim($_POST['date_naiss']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $niveau = trim($_POST['niveau']);
    $matricule = generateMatricule($pdo); // Génère le matricule

    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        // Vérifiez si l'email existe déjà
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM etudiants WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = 'Un étudiant avec cet email existe déjà.';
        } else {
                // Ajouter un message de succès dans la session
                 $_SESSION['message'] = 'Étudiant ajouté avec succès.';

            try {
                $stmt = $pdo->prepare('INSERT INTO etudiants (nom, prenom, email, date_naiss, tel, niveau, matricule) VALUES (:nom, :prenom, :email, :date_naiss, :tel, :niveau, :matricule)');
                $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'date_naiss' => $date_naiss, 'email' => $email, 'tel' => $tel, 'niveau' => $niveau, 'matricule' => $matricule]);

                header('Location: accueil.php'); // Redirection après ajout
                exit();
            } catch (PDOException $e) {
                echo 'Erreur lors de l\'ajout de l\'étudiant : ' . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $error = 'Tous les champs doivent être remplis.';
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Étudiant</title>
    <link rel="stylesheet" href="../css/ajouter_etudiant.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un Étudiant</h1>


        <?php if (isset($_SESSION['message'])): ?>
            <div class="success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); // Efface le message après affichage ?>
        <?php endif; ?>



        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); // Efface l'erreur après affichage ?>
        <?php endif; ?>



        <form method="post">
            <label>Nom:
                <input type="text" name="nom" required>
            </label>
            <label>Prénom:
                <input type="text" name="prenom" required>
            </label>
            <label>Date Naissance:
                <input type="date" name="date_naiss" min="1980-01-01" max="2006-01-01" required>
            </label>
            <label>Email:
                <input type="email" name="email"  required>
            </label>
            <label>Téléphone:
                <input type="text" name="tel" minlenght="9" maxlenght="9" placeholder="776021578"">
            </label>
            <label>Niveau:
                <select name="niveau" required>
                    <option value="">Sélectionnez un niveau</option>
                    <option value="L1">L1</option>
                    <option value="L2">L2</option>
                    <option value="L3">L3</option>
                    <option value="M1">M1</option>
                    <option value="M2">M2</option>
                </select>
            </label>
            <input type="submit" value="Ajouter">
            <input type="hidden" name="action" value="add_student">
        </form>
        <a href="accueil.php"><button id="retour">Annuler</button></a>
    </div>



</body>
</html>
