<?php
session_start();

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

$error = '';
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    header('Location: includes/accueil.php');

                     // Stocker le message de succès dans la session
                    $_SESSION['success_message'] = 'Vous êtes connecté avec succès.';
                    header('Location: includes/accueil.php'); // Redirection après connexion
                    exit();
                } else {
                    $error = 'Mot de passe incorrect.';
                }
            } else {
                $error = 'Aucun utilisateur trouvé avec cet email.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la connexion : ' . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = 'Tous les champs doivent être remplis.';
    }
}






// Vérifier si l'email et le mot de passe sont fournis
if (!empty($email) && !empty($password)) {
    try {
        // Préparer la requête pour récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare('
            SELECT a.id, r.role_name
            FROM admins a
            JOIN roles r ON a.role_id = r.id
            WHERE a.email = :email AND a.password = :password
        ');
        $stmt->execute(['email' => $email, 'password' => $password]); // Assurez-vous que le mot de passe est hashé

        // Vérifier si l'utilisateur existe
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Sauvegarder les informations de l'utilisateur dans la session
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['role_name'] = $user['role_name'];

            // Rediriger l'utilisateur en fonction de son rôle
            if ($user['role_name'] === 'Admin') {
                header('Location: includes/accueil.php');
            } elseif ($user['role_name'] === 'User') {
                header('Location: includes/accueil2.php');
            } else {
                // Rediriger vers une page d'erreur si le rôle n'est pas reconnu
                header('Location: error.php');
            }
            exit();
        } else {
            $error = 'Identifiants invalides.';
        }
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . htmlspecialchars($e->getMessage());
    }
} else {
    $error = 'Email ou mot de passe manquant.';
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        /* Style pour le bouton désactivé */
        #login-button:disabled {
            background-color: #d3d3d3; /* Gris clair */
            cursor: not-allowed; /* Curseur non autorisé */
            color: #a0a0a0; /* Gris foncé pour le texte */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <label>Email:
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </label>
            <label>Mot de passe:
                <input type="password" name="password" id="password" required>
            </label>
            <input type="submit" value="Se Connecter" id="login-button" disabled>
        </form>
    </div>


    <script src="js/login.js"></script>
</body>
</html>
