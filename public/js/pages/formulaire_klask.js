document.addEventListener('DOMContentLoaded', function() {
    console.log("🟢 Le Script JS est chargé !");

    // 1. On récupère les éléments par leur ID exact
    const selectEcole = document.getElementById('choix_ecole');
    const selectClasse = document.getElementById('choix_classe');

    // 2. On vérifie si la base de données est bien arrivée du PHP
    if (typeof bddClasses === 'undefined') {
        console.error("🔴 ERREUR : La variable bddClasses n'existe pas. Vérifiez le bas de votre fichier PHP.");
        return;
    } else {
        console.log("🟢 Données chargées : " + bddClasses.length + " classes trouvées.");
    }

    // 3. Si les menus existent, on lance la logique
    if (selectEcole && selectClasse) {
        
        selectEcole.addEventListener('change', function() {
            const ecoleChoisie = this.value;
            console.log("👉 École choisie : ", ecoleChoisie);

            // On vide la liste des classes
            selectClasse.innerHTML = '<option value="">-- Sélectionnez votre classe --</option>';
            selectClasse.disabled = true;

          if (ecoleChoisie !== "") {
                // FILTRAGE : On cherche les classes qui correspondent au nom de l'école
                // CORRECTION ICI : on utilise 'item.school' car c'est le nom dans la BDD
                const classesFiltrees = bddClasses.filter(function(item) {
                    return item.school.trim() === ecoleChoisie.trim();
                });

                console.log("👉 Classes trouvées pour cette école : ", classesFiltrees.length);

                // Si on a trouvé des classes, on les affiche
                if (classesFiltrees.length > 0) {
                    classesFiltrees.forEach(function(classe) {
                        const option = document.createElement('option');
                        
                        // CORRECTION ICI : on utilise les noms anglais de la BDD
                        option.value = classe.id_class;     // C'était id_classe
                        option.textContent = classe.name_class; // C'était nom_classe
                        
                        selectClasse.appendChild(option);
                    });
                    
                    // ON DÉVERROUILLE LA LISTE ICI
                    selectClasse.disabled = false;
                    selectClasse.style.backgroundColor = "white"; 
                } else {
                    console.warn(" Aucune classe trouvée.");
                }
            }});

    } else {
        console.error(" ERREUR : Impossible de trouver les menus déroulants 'choix_ecole' ou 'choix_classe' dans le HTML.");
    }

    // --- PARTIE IDENTITÉ SECRÈTE (Ne change pas) ---
    const btnRandom = document.getElementById('btn_random');
    const inputPseudo = document.getElementById('pseudo_input');

    if (btnRandom && inputPseudo) {
        const jsAnimaux = [
        
            'Tardigrade', 'Loutre', 'Panda', 'Aigle', 'Renard', 'Loup', 'Hibou', 
            'Dauphin', 'Faucon', 'Lynx', 'Salamandre', 'Koala', 
            'Suricate', 'Ours', 'Lémurien', 'Ornithorynque', 'Caméléon', 'Iguane', 
            'Jaguar', 'Panthère', 'Requin', 'Baleine', 'Orque',
            'Hamster', 'Castor', 'Hérisson', 'Ecureuil', 'Kangourou', 'Lama', 'Zèbre',
            'Dragon', 'Phoenix', 'Griffon', 'Pégase', 'Sphinx', 'Yéti', 'Kraken', 
            'Chimère', 'Hydre', 'Titan', 'Cyclope', 'Gargouille', 'Licorne', 'Axolotl', 'Scarabée', 'Alpaga',
            'Pingouin', 'Mouette' , 'Wombat', 'Wapiti', 'Gecko', 'Kangourou', 'Tortue', 'Papillon', 'Girafe',
            'Anchoix', "Canard", 
        ];


        const jsAdjectifs = [
            
            'Cosmique', 'Galactique', 'Solaire', 'Lunaire', 'Stellaire', 'Polaire', 
            'Volcanique', 'Aquatique', 'Electrique', 'Magnétique', 'Bionique', 'Cyber',
            'Intrépide', 'Brave', 'Sage', 'Zen', 'Fidèle', 'Rebelle', 'Sauvage', 'Anarchiste' ,
            'Libre', 'Solitaire', 'Sympathique', 'Drôle', 'Excentrique', 'Artiste',
            'Habile', 'Agile', 'Rapide', 'Véloce', 'Tenace', 'Robuste', 'Stoïque',
            'Diplomate', 'Pacifique', 'Terrible', 'Redoutable', 'Invincible', 
            'Invisible', 'Mystique', 'Magique', 'Enigmatique', 'Fantastique', 
            'Légendaire', 'Mythique', 'Héroïque', 'Epique', 'Titanesque',
            'Incroyable', 'Imprévisible', 'Inarrêtable', 'Insaisissable', 
            'Timide', 'Scientifique', 'Altruiste', 'Romantique', 'Chevaleresque', 'Mécanique'
        ];
        btnRandom.addEventListener('click', function(e) {
            e.preventDefault();
            inputPseudo.style.opacity = '0.5';
            const animal = jsAnimaux[Math.floor(Math.random() * jsAnimaux.length)];
            const adj = jsAdjectifs[Math.floor(Math.random() * jsAdjectifs.length)];
            setTimeout(() => {
                inputPseudo.value = animal + ' ' + adj;
                inputPseudo.style.opacity = '1';
            }, 200);
        });
    }
});
// --- PARTIE 3 : ACCESSIBILITÉ (Changement de thème) ---
function changerTheme(theme) {
    document.body.setAttribute('data-theme', theme);
}