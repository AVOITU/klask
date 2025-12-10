<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Klask</title>
    <style>
        body { font-family: sans-serif; background-color: #ecf0f1; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .admin-panel { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid #c0392b; max-width: 400px; }
        h1 { color: #c0392b; margin-top: 0; }
        .btn-danger { background-color: #c0392b; color: white; border: none; padding: 15px 30px; font-size: 1.1rem; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px; transition: 0.3s; }
        .btn-danger:hover { background-color: #e74c3c; }
        .success { color: green; font-weight: bold; margin-top: 15px; padding: 10px; background: #d4edda; border-radius: 5px; }
        .error { color: red; margin-top: 15px; }
        .back-link { display: block; margin-top: 20px; color: #7f8c8d; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="admin-panel">
    <h1>⚠️ Zone Admin</h1>
    <p>Cette action est irréversible.<br>Elle supprimera tous les élèves et l'historique des validations.</p>

    <?php
    // TRAITEMENT PHP
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_reset'])) {
        
        // 1. Connexion BDD (Indépendante pour ce fichier)
        $host = 'localhost'; $dbname = 'klask'; $user = 'root'; $pass = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 2. Suppressions (Enfants d'abord, Parents ensuite)
            $pdo->exec("DELETE FROM validation");
            $pdo->exec("DELETE FROM utilisateur");

            // 3. Remise à zéro des compteurs
            $pdo->exec("ALTER TABLE validation AUTO_INCREMENT = 1");
            $pdo->exec("ALTER TABLE utilisateur AUTO_INCREMENT = 1");

            echo "<div class='success'>✅ Base de données remise à zéro avec succès !</div>";

        } catch (PDOException $e) {
            echo "<div class='error'>❌ Erreur : " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <form method="POST">
        <input type="hidden" name="confirm_reset" value="1">
        <input type="submit" class="btn-danger" value="🗑️ TOUT EFFACER" 
               onclick="return confirm('CONFIRMATION ULTIME : Êtes-vous sûr de vouloir tout supprimer ?');">
    </form>

    <a href="../formulaireklask.php" class="back-link">← Retour au formulaire élève</a>
</div>

</body>
</html>