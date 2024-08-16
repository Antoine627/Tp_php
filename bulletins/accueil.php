<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=tp_php', 'root', '');

// Requête pour obtenir les étudiants non archivés
$stmt_non_archive = $pdo->query('SELECT * FROM etudiants WHERE archive = 0');

// Requête pour obtenir les étudiants archivés
$stmt_archive = $pdo->query('SELECT * FROM etudiants WHERE archive = 1');

// Récupérer le message de succès de la session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); // Effacer le message après l'avoir affiché
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Page d'Administration</h1>
        <nav>
            <ul>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>



    <main>
        <!-- Section pour gérer les étudiants -->
        <section id="manage-students">
            <h2>Gérer Les Bulletins de Notes</h2>
            <a href="../includes/accueil.php" id="toggle-student-form"><button>Retour</button></a>
            <a href="statistique.php" id="toggle-student-form" style="position: relative; left: 1100px;"><button>Statistiques</button></a>

            <!-- Liste des étudiants -->
            <div id="student-list">
                <!-- Tableau des étudiants non archivés -->
                <div id="non-archived-list">
                    <h3>Bulletins de Note Des Etudiants</h3>
                    <table>
                        <thead>
                            <tr>
        
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date Naissance</th>
                                <th>Email</th>
                                <th>niveau</th>
                                <th>Total Note</th>
                                <th>Moyenne</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt_non_archive->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                            
                                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_naiss']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['niveau']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_notes']); ?></td>
                                <td><?php echo htmlspecialchars($row['moyenne']); ?></td>
                                <td class="<?php echo strtolower($row['statut']); ?>">
                                <?php echo htmlspecialchars($row['statut']); ?>
                                <td>
                                    <a class="btn btn-primary" href="ajouter_note.php?id=<?php echo htmlspecialchars($row['id']); ?>">Ajouter note</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main><br><br><br><br>

    <div id="timerDisplay">Inactivité : <span id="timer">25</span> secondes</div>
    

    <footer>
    <div class="footer-content">
        <div class="footer-left">
            <h3>Gestion Etudiant</h3>
            <p>&copy; 2024 Tous droits réservés.</p>
        </div>
        <div class="footer-middle">
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <p>Suivez-nous sur :</p>
            <a href="#" class="social-icon">Facebook</a>
            <a href="#" class="social-icon">Twitter</a>
            <a href="#" class="social-icon">Instagram</a>
        </div>
    </div>
</footer>

    

<script src="../js/accueil.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
