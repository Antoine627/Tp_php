<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Administration</title>
    <link rel="stylesheet" href="../css/accueil.css">
    <style>
        /* Griser les boutons */
        .disabled-button {
            background-color: #d3d3d3; /* Gris clair */
            border: none;
            color: #a0a0a0; /* Gris foncé pour le texte */
            cursor: not-allowed; /* Curseur de non-interaction */
        }

        /* Ajouter une surcharge pour les boutons spécifiques */
        #toggle-student-form,
        #bulletins-button {
            pointer-events: none; /* Désactiver les interactions */
        }
    </style>
</head>
<body>
    <header>
        <h1>Page d'Administration</h1>
        <nav>
            <ul>
                <li><a href="accueil.php" class="disabled-button">Gérer les Étudiants</a></li>
                <li><a href="admin.php" class="disabled-button">Gérer les Administrateurs</a></li>
                <li><a href="logout.php" class="disabled-button">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Section pour gérer les étudiants -->
        <section id="manage-students">
            <h2>Gérer les Étudiants</h2>
            <a href="ajouter_etudiant.php"><button id="toggle-student-form" class="disabled-button">Ajouter un Étudiant</button></a>
            <a href="../bulletins/accueil.php" id="bulletins-button"><button class="disabled-button">Bulletins Étudiants</button></a>

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
                                <th>ID</th>
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
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
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
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date Naissance</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Niveau</th>
                                <th>Matricule</th>
                                <th>Actions<
