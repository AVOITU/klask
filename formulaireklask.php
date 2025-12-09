<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scanner Élève</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="formulaireklask.css"> 
</head>
<body data-theme="standard"> <div class="sphere sphere-1"></div>
    <div class="sphere sphere-2"></div>
    <div class="sphere sphere-3"></div>
    <div class="sphere sphere-4"></div>

    <div class="accessibility-bar">
        <button onclick="changerTheme('standard')" class="btn-access btn-standard" title="Mode Standard">A</button>
        <button onclick="changerTheme('chaud')" class="btn-access btn-warm" title="Filtre Lumière Bleue (Chaud)">☀</button>
        <button onclick="changerTheme('negatif')" class="btn-access btn-negative" title="Contraste Élevé / Négatif">👁</button>
    </div>

    <div class="container">

        <?php
        require_once __DIR__ . '/config/database.php';
        // --- 1. CONNEXION BDD ---
        $pdo = get_pdo();

        // --- 2. TRAITEMENT ---
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_classe = isset($_POST['classe_final_id']) ? intval($_POST['classe_final_id']) : 0;
            $pseudo = isset($_POST['pseudo_choisi']) ? htmlspecialchars($_POST['pseudo_choisi']) : "Anonyme";

            if ($id_classe > 0) {
                try {
                    // Insertion
                    $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo_utilisateur, role_utilisateur, autorite_utilisateur, id_classe) VALUES (:pseudo, 'Elève', 'Aucune', :id_classe)");
                    $stmt->execute(['pseudo' => $pseudo, 'id_classe' => $id_classe]);
                    
                    // Récupération infos
                    $info = $pdo->query("SELECT ecole, nom_classe FROM classe WHERE id_classe = $id_classe")->fetch();

                    echo "<div class='resultat success-anim'>";
                    echo "<h3>🎉 Inscription Validée !</h3>";
                    echo "🏫 " . htmlspecialchars($info['ecole']) . "<br>";
                    echo "📚 " . htmlspecialchars($info['nom_classe']) . "<br>";
                    echo "👤 <strong>$pseudo</strong>";
                    echo "</div>";
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) echo "<div class='resultat error'>⚠️ Ce pseudo est déjà pris ! Relancez le dé.</div>";
                }
            }
        }

        // --- 3. DONNÉES ---
        $sql = "SELECT id_classe, ecole, nom_classe FROM classe ORDER BY ecole ASC, nom_classe ASC";
        $classes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $ecoles = [];
        foreach ($classes as $row) { if ($row['ecole']) $ecoles[$row['ecole']] = $row['ecole']; }
        
        $animaux = ['Loutre', 'Panda', 'Renard', 'Loup', 'Hibou', 'Dauphin', 'Faucon', 'Lynx', 'Salamandre', 'Koala', 'Dragon', 'Phoenix', 'Griffon'];
        $adjectifs = ['Cosmique', 'Solaire', 'Zen', 'Rapide', 'Agile', 'Sage', 'Intrépide', 'Loyal', 'Magique', 'Epique'];
        ?>

        <h1>Création de profil</h1>
        
        <form action="" method="POST">
            
            <div class="form-group">
                <label for="choix_ecole">1. Mon Établissement</label>
                <div class="select-wrapper">
                    <select id="choix_ecole" required>
                        <option value="">👇 Touchez pour choisir</option>
                        <?php foreach($ecoles as $nom): ?>
                            <option value="<?= htmlspecialchars($nom) ?>"><?= htmlspecialchars($nom) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="choix_classe">2. Ma Classe</label>
                <div class="select-wrapper">
                    <select name="classe_final_id" id="choix_classe" required disabled>
                        <option value="">🔒 Choisissez d'abord l'école</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>3. Mon identité secrète</label>
                <div class="identity-box">
                    <?php $nom_depart = $animaux[array_rand($animaux)] . ' ' . $adjectifs[array_rand($adjectifs)]; ?>
                    <input type="text" name="pseudo_choisi" id="pseudo_input" value="<?= $nom_depart ?>" readonly>
                    <button type="button" id="btn_random" aria-label="Changer de nom">🎲</button>
                </div>
                <small>Touchez le dé pour changer</small>
            </div>

            <input type="submit" value="Valider l'inscription">
        </form>
    </div>

    <script>const bddClasses = <?php echo json_encode($classes); ?>;</script>
    <script src="formulaireklask.js"></script>
</body>
</html>