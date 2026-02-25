<?php

/** @var array $teams */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Score des équipes</title>
</head>

<body>
    <div>

        <h2>La journée se termine</h2>
        <h2>Merci pour votre participation !</h2>

        <h1>Score des équipes</h1>

        <div class="teams-container">
            <?php if (!empty($teams)): ?>
                <?php foreach ($teams as $team): ?>
                    <div class="team-card">
                        <span class="team-name">
                            <?= htmlspecialchars($team->getclassroom()) ?>
                            (<?= htmlspecialchars($team->getschool()) ?>)
                        </span>
                        <span class="team-score">
                            <?= htmlspecialchars($team->getscoreTotal()) ?> points
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune équipe trouvée.</p>
            <?php endif; ?>
        </div>

        <h2>Meilleur score</h2>

        <button>Valider</button>

    </div>
</body>

</html>