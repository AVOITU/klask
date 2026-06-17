export function initSessionTimeout(logoutUrl, maxIdleTimeMs) {
    let idleTimer;


    const events = ['mousemove', 'keydown', 'mousedown', 'touchstart'];

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        console.log("Activité détectée, reset du timer"); // Tu verras ça bouger sans arrêt si le polling parasite !

        idleTimer = setTimeout(() => {
            console.log("Temps écoulé, redirection vers :", logoutUrl);
            window.location.replace(logoutUrl);
        }, maxIdleTimeMs);
    }

    events.forEach(evt => {
        window.addEventListener(evt, resetIdleTimer, { passive: true });
    });


    // Écoute les interactions utilisateur
    ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evt => {
        window.addEventListener(evt, resetIdleTimer, { passive: true });
    });

    resetIdleTimer();
}


