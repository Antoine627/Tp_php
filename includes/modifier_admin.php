<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Vérifier si l'identifiant de l'administrateur est passé en GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir l'identifiant en entier pour des raisons de sécurité

    // Récupérer les informations de l'administrateur
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        header('Location: admin.php?error=Administrateur non trouvé');
        exit();
    }
} else {
    header('Location: admin.php?error=ID non fourni');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_admin') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_id = (int)$_POST['role_id'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && $role_id > 0) {
        $sql = 'UPDATE admins SET nom = :nom, prenom = :prenom, email = :email, role_id = :role_id';
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role_id' => $role_id,
            'id' => $id
        ];

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Hash du mot de passe
            $sql .= ', password = :password';
            $params['password'] = $passwordHash;
        }

        $sql .= ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Vérifier si la mise à jour a été effectuée avec succès
        if ($stmt->rowCount() > 0) {
            header('Location: admin.php?success=Admin modifié avec succès'); // Redirection après mise à jour
            exit();
        } else {
            $error = 'Aucune modification effectuée. Vérifiez les données.';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Administrateur</title>
    <link rel="stylesheet" href="../css/ajouter_etudiant.css">
</head>
<body>
    <header>
        <h1>Modifier Administrateur</h1>
    </header>

    <style>
        /* Styliser le texte small sous l'input */
        small {
            display: block;
            margin-top: 0.8em;
            font-size: 0.9em; /* Taille légèrement plus petite que le texte par défaut */
            color: #d9534f; /* Couleur de texte plus douce */
        }
    </style>

    <main>
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_admin">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                
                <label>Nom:
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($admin['nom']); ?>" required>
                </label>
                <label>Prénom:
                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($admin['prenom']); ?>" required>
                </label>
                <label>Email:
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </label>
                <label>Mot de passe:
                    <input type="password" name="password">
                    <small>Laissez vide si vous ne souhaitez pas modifier le mot de passe</small>
                </label>

                <label>Rôle: <small class="small" id="role-desc">*</small> 
                    <select name="role_id" required aria-required="true" aria-describedby="role-desc">
                        <option value="">Sélectionnez un rôle</option>
                        <?php
                        // Récupérer les rôles depuis la base de données
                        $stmt = $pdo->query('SELECT id, role_name FROM roles');
                        while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = $role['id'] == $admin['role_id'] ? ' selected' : '';
                            echo '<option value="' . htmlspecialchars($role['id']) . '"' . $selected . '>' . htmlspecialchars($role['role_name']) . '</option>';
                        }
                        ?>
                    </select>
                </label>
                
                <input type="submit" value="Mettre à jour">
            </form>
            <a href="admin.php"><button id="retour">Retour</button></a>
        </div>
    </main>
</body>
</html>
