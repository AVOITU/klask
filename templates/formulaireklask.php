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

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" method="POST">

    <!-- 1) ECOLE -->
        <div class="form-group">
            <label for="choix_ecole">1. Mon Établissement</label>

            <div class="select-wrapper">
                <select id="choix_ecole" name="ecole" required onchange="this.form.submit()">
                    <option value="">👇 Touchez pour choisir</option>

                    <?php foreach ($schools as $nom): ?>
                        <option
                                value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                <?= ($selectedSchool !== '' && $selectedSchool === $nom) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- 2) CLASSE -->
        <div class="form-group">
            <label for="choix_classe">2. Ma Classe</label>

            <div class="select-wrapper">
                <select name="classe_final_id" id="choix_classe" required <?= empty($filteredClasses) ? 'disabled' : '' ?>>
                    <?php if (empty($filteredClasses)): ?>
                        <option value="">🔒 Choisissez d'abord l'école</option>
                    <?php else: ?>
                        <option value="">👇 Choisir la classe</option>

                        <?php $selectedClassId = isset($_POST['classe_final_id']) ? (int)$_POST['classe_final_id'] : 0; ?>

                        <?php foreach ($filteredClasses as $c): ?>
                            <?php $id = (int)$c['id_classe']; ?>
                            <option value="<?= $id ?>" <?= ($selectedClassId === $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nom_classe'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- 3) PSEUDO -->
        <div class="form-group">
            <label>3. Mon identité secrète</label>

            <div class="identity-box">
                <input
                        type="text"
                        name="pseudo_choisi"
                        id="pseudo_input"
                        value="<?= htmlspecialchars($nom_depart, ENT_QUOTES, 'UTF-8') ?>"
                        readonly
                >

                <!-- si tu as un JS qui change le pseudo, ok. Sinon ce bouton ne fera rien -->
                <button type="submit" name="action" value="regen" id="btn_random">🎲</button>
            </div>

            <small>Touchez le dé pour changer</small>
        </div>

        <input type="submit" value="Valider l'inscription">
    </form>
</div>

<script src="/js/formulaireklask.js"></script>
</body>
</html>