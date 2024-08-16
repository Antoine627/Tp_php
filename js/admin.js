// JavaScript pour basculer l'affichage du formulaire
document.getElementById('toggle-admin-form').addEventListener('click', function() {
    var form = document.getElementById('add-admin-form');
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }
});





let timerDisplay = document.getElementById('timer');
        let timeout;
        let timeLeft = 25; // Temps d'inactivité en secondes
        href='../login.php';
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
                window.location.href = '../login.php'; // Redirection après l'alerte
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