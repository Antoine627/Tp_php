<?php
session_start(); // Démarrer la session
include '../db.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Connexion à MySQL pour gérer les utilisateurs
//$pdo_mysql = new PDO('mysql:host=localhost', 'root', '');


// Récupérer tous les administrateurs
$stmt = $pdo->query('SELECT * FROM admins');
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);





// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_id = (int)$_POST['role_id'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && $role_id > 0) {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = 'Un administrateur avec cet email existe déjà.';
        } else {
            try {
                // Hashage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Ajouter l'administrateur avec le rôle sélectionné
                $stmt = $pdo->prepare('INSERT INTO admins (nom, prenom, email, password, role_id) VALUES (:nom, :prenom, :email, :password, :role_id)');
                $stmt->execute(['nom' => $nom, 'prenom' => $prenom,'email' => $email,'password' => $hashedPassword,'role_id' => $role_id ]);

                // Ajouter un message de succès dans la session
                $_SESSION['message'] = 'Administrateur ajouté avec succès.';
                
                // Rediriger après ajout
                header('Location: admin.php');
                exit();
            } catch (PDOException $e) {
                echo 'Erreur lors de l\'ajout de l\'administrateur : ' . htmlspecialchars($e->getMessage());
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
    <title>Ajouter un Admin</title>
    <link rel="stylesheet" href="../css/ajouter_etudiant.css">

    <style>           
        .small
        {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Admin</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Nom: <small class="small" id="nom-desc">*</small>
                <input type="text" name="nom" required aria-required="true" aria-describedby="nom-desc">
                
            </label>
            <label>Prénom: <small class="small" id="prenom-desc">*</small>
                <input type="text" name="prenom" required aria-required="true" aria-describedby="prenom-desc">
                
            </label>
            <label>Email: <small class="small" id="email-desc">*</small>
                <input type="email" name="email" required aria-required="true" aria-describedby="email-desc">
                
            </label>
            <label>Password: <small class="small" id="password-desc">*</small> 
                <input type="password" name="password" aria-required="true" aria-describedby="password-desc">
                
            </label>
            <label>Rôle: <small class="small" id="role-desc">*</small> 
                <select name="role_id" required aria-required="true" aria-describedby="role-desc">
                    <option value="">Sélectionnez un rôle</option>
                    <?php
                    // Récupérer les rôles depuis la base de données
                    $stmt = $pdo->query('SELECT id, role_name FROM roles');
                    while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['role_name']) . '</option>';
                    }
                    ?>
                </select>
            </label>
            <input type="submit" value="Ajouter">
            <input type="hidden" name="action" value="add_admin">
        </form>
        <a href="admin.php"><button id="retour">Annuler</button></a>
    </div>
</body>
</html>
