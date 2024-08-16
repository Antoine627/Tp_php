// js/accueil.js

/*document.addEventListener('DOMContentLoaded', function() {
    const toggleListLink = document.getElementById('toggle-list');
    const nonArchivedList = document.getElementById('non-archived-list');
    const archivedList = document.getElementById('archived-list');

    toggleListLink.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        // Basculer l'affichage entre les listes
        if (archivedList.style.display === 'none') {
            archivedList.style.display = 'block';
            nonArchivedList.style.display = 'none';
            toggleListLink.textContent = 'Liste des Étudiants Non Archivés';
        } else {
            archivedList.style.display = 'none';
            nonArchivedList.style.display = 'block';
            toggleListLink.textContent = 'Liste des Étudiants Archivés';
        }
    });
});*/


document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner l'élément pour le bouton de bascule
    var toggleButton = document.getElementById('toggle-list');
    var archivedList = document.getElementById('archived-list');
    var nonArchivedList = document.getElementById('non-archived-list');

    // Vérifier que le bouton et les listes existent avant d'ajouter des écouteurs d'événements
    if (toggleButton && archivedList && nonArchivedList) {
        toggleButton.addEventListener('click', function() {
            if (archivedList.style.display === 'none' || archivedList.style.display === '') {
                archivedList.style.display = 'block';
                nonArchivedList.style.display = 'none';
            } else {
                archivedList.style.display = 'none';
                nonArchivedList.style.display = 'block';
            }
        });
    } else {
        console.error('Un ou plusieurs éléments nécessaires ne sont pas trouvés dans le DOM');
    }
});






document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('toggle-student-form');
    if (button) {
        button.addEventListener('click', function() {
            // Code à exécuter lors du clic sur le bouton
        });
    }
});





document.addEventListener('DOMContentLoaded', function() {
    const archiveLink = document.getElementById('archive');
    const archivedStudentList = document.getElementById('archived-student-list');
    const studentList = document.getElementById('student-list');

    archiveLink.addEventListener('click', function(event) {
        event.preventDefault(); // Empêcher le comportement par défaut du lien

        // Basculer l'affichage entre les listes
        if (archivedStudentList.style.display === 'none') {
            studentList.style.display = 'none';
            archivedStudentList.style.display = 'block';
            archiveLink.innerHTML = 'Liste des Étudiants non Archivés';
        } else {
            archivedStudentList.style.display = 'none';
            studentList.style.display = 'block';
            archiveLink.innerHTML = 'Liste des Étudiants Archivés';
        }
    });
});


let timerDisplay = document.getElementById('timer');
        let timeout;
        let timeLeft = 25; // Temps d'inactivité en secondes

        function resetTimer() {
            clearTimeout(timeout);
            timeLeft = 25;
            updateTimerDisplay();
            timeout = setTimeout(decrementTimer, 1000);
        }

        function decrementTimer() {
            timeLeft--;
            if (timeLeft <= 0) {
                timeLeft = 0;
                alert("Vous avez été inactif pendant 25 secondes.");
            }
            updateTimerDisplay();
            timeout = setTimeout(decrementTimer, 1000);
        }

        function updateTimerDisplay() {
            timerDisplay.textContent = `${timeLeft} secondes`;
            if (timeLeft < 10) {
                timerDisplay.classList.add('urgent');
            } else {
                timerDisplay.classList.remove('urgent');
            }
        }

        document.addEventListener('mousemove', resetTimer);
        document.addEventListener('keydown', resetTimer);
        
        // Initial call to start the timer
        resetTimer();


 


function showPopup() {
    var popup = document.getElementById('success-popup');
    var overlay = document.getElementById('popup-overlay');

    console.log(popup);  // Vérifier si l'élément est correctement sélectionné
    console.log(overlay); // Vérifier si l'élément est correctement sélectionné

    if (popup && overlay) {
        popup.style.display = 'block';  // Afficher le popup
        overlay.style.display = 'block'; // Afficher l'overlay
    }
}

function closePopup() {
    var popup = document.getElementById('success-popup');
    var overlay = document.getElementById('popup-overlay');

    if (popup && overlay) {
        popup.style.display = 'none';  // Masquer le popup
        overlay.style.display = 'none'; // Masquer l'overlay
    }
}

// Appeler showPopup si un message de succès est présent
document.addEventListener('DOMContentLoaded', function() {
    var successMessage = "<?php echo addslashes($success_message); ?>";
    if (successMessage) {
        showPopup();
        setTimeout(closePopup, 5000); // Fermer automatiquement après 5 secondes
    }
});









// script.js
/*document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));

            // Add active class to the clicked tab and corresponding content
            tab.classList.add('active');
            document.querySelector(`.content[data-tab="${tab.dataset.tab}"]`).classList.add('active');
        });
    });

    // Optionally, activate the first tab by default
    if (tabs.length > 0) {
        tabs[0].click();
    }
});*/




