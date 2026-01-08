<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vue Sidebar user map
// Reçoit les données du controller.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sidebar user map</title>
    <link rel="stylesheet" href="/css/pages/sidebarUserMap.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap"
          rel="stylesheet">
</head>
<body>

<h1>Page Sidebar user map</h1>

<div id="sidebar-trigger-zone" class="trigger-tab">
    <span class="arrow-icon">◀</span>
</div>

<div id="sidebar-panel" class="sidebar-container">
    
    <div class="sidebar-header">
        <div class="header-icon">?</div>
        <h2>PAGE ANNEXE</h2>
    </div>

    <div class="sidebar-body">
        
        <div class="left-strip">
            <span class="vertical-text">CARTE</span>
            <button id="btn-close-sidebar" class="tab-close">▶</button>
        </div>

        <div class="user-card">
            <h3>UTILISATEUR</h3>

<?php
/** @var \Model\User|null $currentUser */


$pseudo = "test";  
$score  = 0;         


if (isset($currentUser) && $currentUser !== null) {

    $pseudo = $currentUser->getPseudoUser(); 

}
?>

<div class="info-group">
    <span class="label">PSEUDO :</span>
    <span class="value pseudo"><?= htmlspecialchars($pseudo) ?></span>
</div>


            </div>
        </div>
    </div>
</div>

<script src="/js/pages/sidebarUserMap.js"></script>
</body>
</html>