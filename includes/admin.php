<?php
session_start(); // Démarrer la session
include 'function.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Message de succès
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); // Effacer le message après l'avoir affiché

// Ajouter un nouvel administrateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash du mot de passe
    $role_id = (int)$_POST['role_id']; // Assigner le rôle

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && $role_id > 0) {
        // Insertion de l'administrateur dans la base de données
        $stmt = $pdo->prepare('INSERT INTO admins (nom, prenom, email, password, role_id) VALUES (:nom, :prenom, :email, :password, :role_id)');
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $password, 'role_id' => $role_id]);

        // Message de succès
        $_SESSION['success_message'] = "Administrateur ajouté avec succès.";
        header('Location: admin.php'); // Redirection pour éviter la soumission du formulaire
        exit();
    } else {
        $_SESSION['error_message'] = 'Tous les champs doivent être remplis.';
        header('Location: admin.php');
        exit();
    }
}

// Récupérer les administrateurs existants
$admins = [];
try {
    $stmt = $pdo->query('SELECT * FROM admins');
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gestion des erreurs de la requête
    $_SESSION['error_message'] = 'Erreur lors de la récupération des administrateurs: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        /* Styles pour les rôles */
        .admin-role {
            background-color: #1e90ff; /* Bleu */
            color: #343a40;
        }

        .user-role {
            background-color: #1e90ff; /* Tomate */
            color: #343a40;
        }
        
        /* Styles pour les actions */
        .actions {
            text-decoration: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }
        
        .actions.modify {
            background-color: #28a745; /* Vert */
        }
        
        .actions.delete {
            background-color: #dc3545; /* Rouge */
        }
        
        .actions:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header>
        <h1>Page d'Administration</h1>
        <nav>
            <ul>
                <li><a href="accueil.php">Gérer les Étudiants</a></li>
                <li><a href="admin.php">Gérer les Administrateurs</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="manage-admins">
        <h2>Gérer les Administrateurs</h2>

        <!-- Message de succès -->
        <?php if (!empty($success_message)): ?>
            <div class="message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <!-- Message d'erreur -->
        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Bouton pour afficher/masquer le formulaire -->
        <a href="ajouter_admin.php"><button id="toggle-admin-form">Ajouter un Administrateur</button></a>

        <!-- Formulaire pour ajouter un nouvel administrateur -->
        <div id="add-admin-form" class="hidden">
            <h3>Ajouter un Administrateur</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_admin">
                <label>Nom:
                    <input type="text" name="nom" required>
                </label>
                <label>Prénom:
                    <input type="text" name="prenom" required>
                </label>
                <label>Email:
                    <input type="email" name="email" required>
                </label>
                <label>Mot de passe:
                    <input type="password" name="password" required>
                </label>
                <label>Rôle:
                    <select name="role_id" required>
                        <option value="">Sélectionnez un rôle</option>
                        <?php
                        // Récupérer les rôles depuis la base de données
                        $roleStmt = $pdo->query('SELECT id, role_name FROM roles');
                        while ($role = $roleStmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['role_name']) . '</option>';
                        }
                        ?>
                    </select>
                </label>
                <input type="submit" value="Ajouter">
            </form>
        </div>

        <!-- Liste des administrateurs -->
        <div id="admin-list">
            <h3>Liste des Administrateurs</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <?php
                        // Récupérer le nom du rôle
                        $roleId = $admin['role_id'];
                        $roleStmt = $pdo->prepare('SELECT role_name FROM roles WHERE id = :id');
                        $roleStmt->execute(['id' => $roleId]);
                        $role = $roleStmt->fetch(PDO::FETCH_ASSOC);
                        $roleName = $role ? $role['role_name'] : 'Inconnu';

                        // Définir la classe en fonction du rôle
                        $roleClass = ($roleName === 'Admin') ? 'admin-role' : 'user-role';
                        ?>
                        <tr class="<?php echo $roleClass; ?>">
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td><?php echo htmlspecialchars($admin['nom']); ?></td>
                            <td><?php echo htmlspecialchars($admin['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars($roleName); ?></td>
                            <td>
                                <a class="actions modify" href="<?php echo generateUrl('modifier_admin', ['id' => $admin['id']]); ?>">Modifier</a>
                                <a class="actions delete" href="supprimer_admin.php?id=<?php echo htmlspecialchars($admin['id']); ?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    </main>

    <footer>
    <p>&copy; <?php echo date("Y"); ?> SIMPLON</p>
    </footer>

    <script src="../js/admin.js"></script>
</body>
</html>
