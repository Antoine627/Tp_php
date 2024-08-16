<?php
session_start();

include 'function.php';



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
    <link rel="stylesheet" href="../css/accueil.css">
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
        <!-- Section pour gérer les étudiants -->
        <section id="manage-students">
            <h2>Gérer les Étudiants</h2>
            <a href="ajouter_etudiant.php"><button id="toggle-student-form">Ajouter un Étudiant</button></a>
            <a href="../bulletins/accueil.php" id="toggle-student-form" style="position: relative; left: 1100px;"><button>Bulletins Etudiants</button></a>


            <?php if (isset($_SESSION['message'])): ?>
                <div class="message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                <?php unset($_SESSION['message']); // Supprimer le message après affichage ?>
            <?php endif; ?>


            <!-- Overlay pour griser la page -->
            <div id="popup-overlay" class="overlay"></div>

             <!-- Popup message -->
                <?php if ($success_message): ?>
                    <div id="success-popup" class="popup">
                        <span class="close" onclick="closePopup()">&times;</span>
                        <p><?php echo htmlspecialchars($success_message); ?></p>
                    </div>
                <?php endif; ?>
            </div>




            <!-- Liens pour basculer entre les listes -->
            <h3>
                <a href="#" id="toggle-list">Liste des Étudiants Archivés</a>
            </h3>

            <!-- Liste des étudiants -->
            <div id="student-list">
                <!-- Tableau des étudiants non archivés -->
                <div id="non-archived-list">
                    <h3>Liste des Étudiants Non Archivés</h3>
                    <table>
                        <thead>
                            <tr>
                        
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date Naissance</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Niveau</th>
                                <th>Matricule</th>
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
                                <td><?php echo htmlspecialchars($row['tel']); ?></td>
                                <td><?php echo htmlspecialchars($row['niveau']); ?></td>
                                <td><?php echo htmlspecialchars($row['matricule']); ?></td>
                                <td>
                                    <a class="actions modify" href="modifier_etudiant.php?id=<?php echo htmlspecialchars($row['id']); ?>">Modifier</a>
                                    <a class="actions delete" href="supprimer_etudiant.php?id=<?php echo htmlspecialchars($row['id']); ?>">Supprimer</a>
                                    <a class="actions archive" href="archiver_etudiant.php?id=<?php echo htmlspecialchars($row['id']); ?>">Archiver</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tableau des étudiants archivés -->
                <div id="archived-list" style="display: none;">
                    <h3>Liste des Étudiants Archivés</h3>
                    <table>
                        <thead>
                            <tr>
                              
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date Naissance</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Niveau</th>
                                <th>Matricule</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt_archive->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                              
                                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_naiss']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['tel']); ?></td>
                                <td><?php echo htmlspecialchars($row['niveau']); ?></td>
                                <td><?php echo htmlspecialchars($row['matricule']); ?></td>
                                <td>
                                    <a class="actions archive" href="desarchiver_etudiant.php?id=<?php echo htmlspecialchars($row['id']); ?>">Desarchiver</a>
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
</body>
</html>
