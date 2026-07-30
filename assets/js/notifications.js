const pushModal = document.getElementById('push-notification-modal');

if (pushModal) {
    const pushTitle = document.getElementById('push-title');
    const pushMessage = document.getElementById('push-message');
    const pushContent = document.getElementById('push-modal-content');
    const pushTimes = document.getElementById('push-times');
    const pushCountdown = document.getElementById('push-countdown');
    const pushActionBtn = document.getElementById('push-action-btn');
    const btnClosePush = document.getElementById('btn-close-push');
    let countdownInterval;

    const mercureUrl = new URL('http://127.0.0.1:3000/.well-known/mercure');
    mercureUrl.searchParams.append('topic', 'klask/notifications');
    const eventSource = new EventSource(mercureUrl);

    eventSource.onmessage = function (event) {
        try {
            const data = JSON.parse(event.data);

            // 1. Titre et message
            pushTitle.textContent = data.title || 'Nouvelle Notification';
            pushMessage.textContent = data.message || '';
            pushTimes.textContent = '';
            pushCountdown.textContent = '';

            // 2. Couleur personnalisée
            if (data.colorCode) {
                pushContent.style.borderTopColor = data.colorCode;
                pushTitle.style.color = data.colorCode;
            }

            // 3. Gestion des horaires
            const formatTime = (dateString) => {
                const cleanDate = dateString.substring(0, 19);
                const date = new Date(cleanDate);
                return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }).replace(':', 'h');
            };

            if (data.startTime && data.endTime) {
                pushTimes.textContent = `Événement prévu de ${formatTime(data.startTime)} à ${formatTime(data.endTime)}.`;
            } else if (data.startTime) {
                pushTimes.textContent = `L'événement débutera à ${formatTime(data.startTime)}.`;
            } else if (data.endTime) {
                pushTimes.textContent = `L'événement se terminera à ${formatTime(data.endTime)}.`;
            }

            // 4. Compte à rebours
            if (data.startTime) {
                const targetDate = new Date(data.startTime.substring(0, 19)).getTime();
                clearInterval(countdownInterval);

                countdownInterval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = targetDate - now;

                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        pushCountdown.textContent = "C'est parti !";
                    } else {
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        pushCountdown.textContent = `Commence dans : ${minutes}m ${seconds}s`;
                    }
                }, 1000);
            }

            // 5. Gestion du bouton d'action (Corrigé et forcé)
            if (pushActionBtn) {
                pushActionBtn.onclick = null;

                if (data.actionText && data.actionText.trim() !== '') {
                    pushActionBtn.textContent = data.actionText;
                    pushActionBtn.style.setProperty('display', 'block', 'important');

                    if (data.colorCode) {
                        pushActionBtn.style.backgroundColor = data.colorCode;
                        pushActionBtn.style.color = "white";
                    }

                    pushActionBtn.onclick = function() {
                        pushModal.hidden = true;
                        clearInterval(countdownInterval);

                        if (data.actionType === 'close') {
                            console.log("Fermeture simple.");
                        } else if (data.actionType === 'open_sidebar') {
                            console.log("Ouverture de la sidebar...");
                        } else if (data.actionType === 'show_path') {
                            console.log("Affichage du chemin...");
                        }
                    };
                } else {
                    pushActionBtn.style.setProperty('display', 'none', 'important');
                }
            }

            // Affichage final de la modale
            pushModal.hidden = false;
        } catch (error) {
            console.error("Erreur lors de la lecture du JSON :", error);
        }
    };

    // 6. Fermeture manuelle
    if (btnClosePush) {
        btnClosePush.addEventListener('click', function() {
            pushModal.hidden = true;
            clearInterval(countdownInterval);
        });
    }
}
