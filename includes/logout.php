<?php
session_start(); // Démarrer la session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Détruire toutes les variables de session
$_SESSION = array();

// Si vous utilisez des cookies de session, les supprimer
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: ../login.php');
exit();
?>
