/**
 * Logique d'interaction pour la page Carte (Map)
 * Gère la modale d'aide, les bulles d'information des stands et le déplacement de la carte.
 */
document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. GESTION DE LA MODALE D'AIDE
    // ==========================================
    const btnHelp = document.getElementById('btn-help');
    const helpModal = document.getElementById('help-modal');
    const btnCloseHelp = document.getElementById('btn-close-help');

    if (btnHelp && helpModal && btnCloseHelp) {
        btnHelp.addEventListener('click', () => {
            helpModal.style.display = 'flex';
        });

        btnCloseHelp.addEventListener('click', () => {
            helpModal.style.display = 'none';
        });

        // Fermer la modale si on clique en dehors du contenu (sur l'overlay sombre)
        helpModal.addEventListener('click', (e) => {
            if (e.target === helpModal) {
                helpModal.style.display = 'none';
            }
        });
    }

    // ==========================================
    // 2. GESTION DES BULLES D'ACTIVITÉS
    // ==========================================
    const activityDots = document.querySelectorAll('.activity-dot');
    const bubble = document.getElementById('activity-bubble');
    const bubbleText = document.getElementById('bubble-text');
    const btnCloseBubble = document.getElementById('btn-close-bubble');
    const mapContainer = document.querySelector('.map-background');

    if (bubble && bubbleText && btnCloseBubble && mapContainer) {
        activityDots.forEach(dot => {
            dot.addEventListener('click', () => {
                // Récupération de la description
                const description = dot.getAttribute('data-description');
                bubbleText.textContent = description;

                // Positionnement de la bulle à côté du point cliqué
                const dotRect = dot.getBoundingClientRect();
                const mapRect = mapContainer.getBoundingClientRect();
                
                // Calcul de la position relative à la carte pour suivre le mouvement
                const relativeLeft = dotRect.left - mapRect.left;
                const relativeTop = dotRect.top - mapRect.top;

                bubble.style.left = `${relativeLeft + 15}px`; 
                bubble.style.top = `${relativeTop + 15}px`;
                bubble.style.display = 'block';
            });
        });

        btnCloseBubble.addEventListener('click', () => {
            bubble.style.display = 'none';
        });
    }

    // ==========================================
    // 3. GESTION DU DÉPLACEMENT DE LA CARTE (PANNING)
    // ==========================================
    const mapBg = document.getElementById('map-background');
    
    if (mapBg) {
        let isDragging = false;
        let startX, startY;
        
        // Initialisation : On centre la carte au chargement de la page
        // La carte fait 1200x800, on la centre par rapport à la taille de la fenêtre
        let translateX = (window.innerWidth - 1200) / 2;
        let translateY = (window.innerHeight - 800) / 2;
        mapBg.style.transform = `translate(${translateX}px, ${translateY}px)`;

        function startDrag(e) {
            // On empêche le déplacement si l'utilisateur clique sur un point ou la bulle
            if (e.target.closest('.activity-dot') || e.target.closest('.activity-bubble')) {
                return;
            }
            
            isDragging = true;
            
            // Supporte à la fois le tactile et la souris
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            
            startX = clientX - translateX;
            startY = clientY - translateY;
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault(); // Évite le rafraîchissement ou le défilement natif sur mobile
            
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            
            translateX = clientX - startX;
            translateY = clientY - startY;
            
            mapBg.style.transform = `translate(${translateX}px, ${translateY}px)`;
        }

        function endDrag() {
            isDragging = false;
        }

        // Écouteurs pour la Souris
        mapBg.addEventListener('mousedown', startDrag);
        window.addEventListener('mousemove', drag);
        window.addEventListener('mouseup', endDrag);

        // Écouteurs pour le Tactile
        mapBg.addEventListener('touchstart', startDrag, { passive: false });
        window.addEventListener('touchmove', drag, { passive: false });
        window.addEventListener('touchend', endDrag);
    }
    // ==========================================
    // 4. GESTION DE LA SIDEBAR UTILISATEUR
    // ==========================================
    const sidebar = document.getElementById('sidebar-panel');
    const btnOpenSidebar = document.getElementById('sidebar-trigger-zone');
    const btnCloseSidebar = document.getElementById('btn-close-sidebar');

    if (sidebar && btnOpenSidebar && btnCloseSidebar) {
        btnOpenSidebar.addEventListener('click', () => {
            sidebar.classList.add('open');
            btnOpenSidebar.style.display = 'none'; // Cache la flèche d'ouverture
        });

        btnCloseSidebar.addEventListener('click', () => {
            sidebar.classList.remove('open');
            setTimeout(() => {
                btnOpenSidebar.style.display = 'flex'; // Réaffiche la flèche après l'animation
            }, 400);
        });
    }
    
    // ==========================================
    // 5. GESTION DE LA CARTE (Façon Google Maps)
    // ==========================================
    // On met toute notre logique dans une fonction qu'on pourra appeler à volonté
    function initInteractiveMap() {
        const mapBackground = document.getElementById('map-background');
    
        // Si on n'est pas sur la page de la carte, on arrête tout silencieusement
        if (!mapBackground) return; 

        // --- VARIABLES D'ÉTAT ---
        let scale = 1;         // Niveau de zoom (1 = 100%)
        let panX = 0;          // Position X de la carte
        let panY = 0;          // Position Y de la carte
    
        let isDragging = false;
        let startX = 0;
        let startY = 0;

        // --- FONCTION DE MISE À JOUR VISUELLE ---
        const updateMapTransform = () => {
            mapBackground.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
        };

        // ==========================================
        // 1. LE ZOOM (Molette de la souris)
        // ==========================================
        mapBackground.addEventListener('wheel', (e) => {
            e.preventDefault(); 
            const zoomSensitivity = 0.1;
            const minScale = 0.3; 
            const maxScale = 4.0; 

            if (e.deltaY < 0) {
                scale += zoomSensitivity; 
            } else {
                scale -= zoomSensitivity; 
            }

            scale = Math.min(Math.max(minScale, scale), maxScale);
            updateMapTransform();
        }, { passive: false });

        // ==========================================
        // 2. LE DÉPLACEMENT (Souris / Ordinateur)
        // ==========================================
        mapBackground.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.clientX - panX;
            startY = e.clientY - panY;
            mapBackground.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            panX = e.clientX - startX;
            panY = e.clientY - startY;
            updateMapTransform();
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
            mapBackground.style.cursor = 'grab';
        });

        // ==========================================
        // 3. LE DÉPLACEMENT (Tactile / Téléphone)
        // ==========================================
        mapBackground.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) { 
                isDragging = true;
                startX = e.touches[0].clientX - panX;
                startY = e.touches[0].clientY - panY;
            }
        });

        window.addEventListener('touchmove', (e) => {
            if (!isDragging || e.touches.length !== 1) return;
            panX = e.touches[0].clientX - startX;
            panY = e.touches[0].clientY - startY;
            updateMapTransform();
        }, { passive: false });

        window.addEventListener('touchend', () => {
            isDragging = false;
        });

        // Initialisation
        updateMapTransform();
    }

    // ==========================================
    // DÉCLENCHEURS (Compatibilité Symfony Turbo)
    // ==========================================
    // 1. Pour les chargements de page classiques
    document.addEventListener('DOMContentLoaded', initInteractiveMap);

    // 2. Pour les navigations via Symfony UX Turbo
    document.addEventListener('turbo:load', initInteractiveMap);

    // 3. Si le script est exécuté alors que le DOM est déjà prêt
    if (document.readyState !== 'loading') {
        initInteractiveMap();
    }
});