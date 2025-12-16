<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scanner Élève</title>

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="/css/pages/formulaireklask.css">
</head>
<body data-theme="standard">

<div class="sphere sphere-1"></div>
<div class="sphere sphere-2"></div>
<div class="sphere sphere-3"></div>
<div class="sphere sphere-4"></div>

<div class="accessibility-bar">
    <button onclick="changerTheme('standard')" class="btn-access btn-standard" title="Mode Standard">A</button>
    <button onclick="changerTheme('chaud')" class="btn-access btn-warm" title="Filtre Lumière Bleue (Chaud)">☀</button>
    <button onclick="changerTheme('negatif')" class="btn-access btn-negative" title="Contraste Élevé / Négatif">👁</button>
</div>

<div class="container">
    <h1>Création de profil</h1>

    <?php if (!empty($messageSuccess)): ?>
        <div class="resultat success-anim">
            <h3>🎉 Inscription Validée !</h3>
            🏫 <?= htmlspecialchars($messageSuccess['ecole']) ?><br>
            📚 <?= htmlspecialchars($messageSuccess['classe']) ?><br>
            👤 <strong><?= htmlspecialchars($messageSuccess['pseudo']) ?></strong>
        </div>
    <?php elseif (!empty($messageError)): ?>
        <div class="resultat error">
            <?= htmlspecialchars($messageError) ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="choix_ecole">1. Mon Établissement</label>
            <div class="select-wrapper">
                <select id="choix_ecole" required>
                    <option value="">👇 Touchez pour choisir</option>
                    <?php foreach ($schools as $nom): ?>
                        <option value="<?= htmlspecialchars($nom) ?>">
                            <?= htmlspecialchars($nom) ?>
                        </option>
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
                <input type="text" name="pseudo_choisi" id="pseudo_input"
                       value="<?= htmlspecialchars($nom_depart) ?>" readonly>
                <button type="button" id="btn_random" aria-label="Changer de nom">🎲</button>
            </div>
            <small>Touchez le dé pour changer</small>
        </div>

        <input type="submit" value="Valider l'inscription">
    </form>
</div>

<script>
    const bddClasses = <?= json_encode($classes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>;
</script>
<script src="/js/formulaireklask.js"></script>
</body>
</html>