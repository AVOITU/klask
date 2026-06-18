/* J'ai essayé de compartimenter l'auto deconnection pour que ce soit plus propre
 en faisant le timeout.js mais il y a un conflit avec l'assetMapper pour l'instant,
je ne sais pas comment optimiser ça, donc le code est pour l'instant en dur sur base.html.twig en <script>
ce qui le rend un peu dégueulasse mais marche...

 */


// On attend que tout le HTML soit lu par le navigateur
document.addEventListener('DOMContentLoaded', function() {

    // 1. On cherche la balise de configuration globale
    const configEl = document.getElementById('global-timeout-config');

    if (!configEl) {
        return; // Pas de balise = on est déconnecté ou élève, on s'arrête.
    }

    console.log("🛡️ Sécurité globale (Fichierisé) active !");

    // 2. On extrait les variables fournies par le HTML
    const logoutUrl = configEl.dataset.logoutUrl;
    const timeoutMs = parseInt(configEl.dataset.timeoutMs, 10);

    if (!logoutUrl || isNaN(timeoutMs)) return;

    let idleTimer;

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        // console.log("Mouvement détecté, timer remis à 0 !"); // Décommente pour tester

        idleTimer = setTimeout(() => {
            console.log("🚨 Temps JS écoulé ! Déconnexion...");
            window.location.replace(logoutUrl);
        }, timeoutMs);
    }

    // Événements tactiles (mobile) + souris/clavier (ordi)
    const events = ['touchstart', 'touchmove', 'mousedown', 'mousemove', 'wheel', 'keydown', 'scroll'];
    events.forEach(evt => window.addEventListener(evt, resetIdleTimer, true));

    resetIdleTimer();
});
