// script.js

document.addEventListener('DOMContentLoaded', function() {
    // Obtenir les éléments du formulaire
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    const loginButton = document.getElementById('login-button');

    // Fonction pour vérifier si tous les champs sont remplis
    function checkFormCompletion() {
        if (emailField.value.trim() && passwordField.value.trim()) {
            loginButton.disabled = false; // Activer le bouton si tous les champs sont remplis
        } else {
            loginButton.disabled = true; // Désactiver le bouton si un champ est vide
        }
    }

    // Ajouter des événements pour vérifier les champs à chaque modification
    emailField.addEventListener('input', checkFormCompletion);
    passwordField.addEventListener('input', checkFormCompletion);
});





// Fonction pour vérifier les champs de formulaire
function checkForm() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const submitButton = document.getElementById('login-button');

    // Activer le bouton si les deux champs sont remplis, sinon le désactiver
    submitButton.disabled = !(email && password);
}

// Écouter les événements de saisie sur les champs de formulaire
document.getElementById('email').addEventListener('input', checkForm);
document.getElementById('password').addEventListener('input', checkForm);

// Vérifier le formulaire au chargement de la page
window.onload = checkForm;