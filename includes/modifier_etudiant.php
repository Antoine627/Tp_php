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

    // Récupérer les informations de l'étudiant
    $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header('Location: accueil.php?error=Étudiant non trouvé');
        exit();
    }
} else {
    header('Location: accueil.php?error=ID non fourni');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_student') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naiss = trim($_POST['date_naiss']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $niveau = trim($_POST['niveau']);

    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        // Mettre à jour les informations de l'étudiant
        $stmt = $pdo->prepare('UPDATE etudiants SET nom = :nom, prenom = :prenom, date_naiss = :date_naiss, email = :email, tel = :tel, niveau = :niveau WHERE id = :id');
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'date_naiss' => $date_naiss, 'email' => $email, 'tel' => $tel, 'niveau' => $niveau, 'id' => $id]);

        header('Location: accueil.php'); // Redirection après mise à jour
        exit();
    } else {
        $error = 'Tous les champs doivent être remplis.';
    }
}





?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant</title>
    <link rel="stylesheet" href="../css/ajouter_etudiant.css">
</head>
<body>
    <header>
        <h1>Modifier Étudiant</h1>
    </header>

    <main>
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_student">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                
                <label>Nom:
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($student['nom']); ?>" required>
                </label>
                <label>Prénom:
                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($student['prenom']); ?>" required>
                </label>
                <label>Date Naissance:
                    <input type="date" name="date_naiss" min="1980-01-01" max="2006-01-01" required>
                </label>
                <label>Email:
                    <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </label>
                <label>Téléphone:
                    <input type="text" name="tel" value="<?php echo htmlspecialchars($student['tel']); ?>">
                </label>
                <label>Niveau:
                    <select name="niveau" required>
                        <option value="">Sélectionnez un niveau</option>
                        <option value="L1" <?php echo $student['niveau'] == 'L1' ? 'selected' : ''; ?>>L1</option>
                        <option value="L2" <?php echo $student['niveau'] == 'L2' ? 'selected' : ''; ?>>L2</option>
                        <option value="L3" <?php echo $student['niveau'] == 'L3' ? 'selected' : ''; ?>>L3</option>
                        <option value="M1" <?php echo $student['niveau'] == 'M1' ? 'selected' : ''; ?>>M1</option>
                        <option value="M2" <?php echo $student['niveau'] == 'M2' ? 'selected' : ''; ?>>M2</option>
                    </select>
                </label>
                
                <input type="submit" value="Mettre à jour">
            </form>
            <a href="accueil.php"><button id="retour">Retour</button></a>
        </div>
    </main>
</body>
</html>
