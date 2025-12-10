<?php
// 1. CONFIGURATION BASE DE DONNÉES
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "stagiaire625";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. SIMULATION UTILISATEUR (REMPLACEMENT DE WORDPRESS)
// Sans WordPress, nous devons dire au script "qui" utilise la page.
// Remplacez l'email ci-dessous par celui du technicien à tester.
$email = 'maximecarpentier@tystand.fr'; 

// Vérification basique
if (empty($email)) {
    die("Erreur : Aucun email utilisateur défini.");
}

// 3. TRAITEMENT DU FORMULAIRE (UPDATE)
if (isset($_POST['tid']) && isset($_POST['statut']) && isset($_POST['actions'])) {
    $tid = $_POST['tid'];
    $statut = $_POST['statut'];
    $action = htmlspecialchars($_POST['actions'], ENT_QUOTES, 'UTF-8');

    // Sécurité : Requête préparée
    $stmt_update = $conn->prepare("UPDATE vtiger_troubletickets SET status=?, solution=? WHERE ticketid=?");
    $stmt_update->bind_param("ssi", $statut, $action, $tid);

    if ($stmt_update->execute()) {
        // Succès - On peut rediriger ou afficher un message
        echo "<div style='color:green; font-weight:bold;'>Mise à jour effectuée.</div>";
    } else {
        echo "Error updating record: " . $stmt_update->error;
    }
    $stmt_update->close();
}

// 4. RÉCUPÉRATION CONTACT ID (SÉCURISÉE)
$idc = null;
$stmt_contact = $conn->prepare("SELECT contactid FROM vtiger_contactdetails WHERE email = ?");
$stmt_contact->bind_param("s", $email);
$stmt_contact->execute();
$result = $stmt_contact->get_result();

if ($result->num_rows >= 1) {
    while ($row = $result->fetch_assoc()) {
        $idc = $row["contactid"];
    }
}
$stmt_contact->close();

// Si aucun contact trouvé pour cet email
if (!$idc) {
    die("Aucun contact Vtiger trouvé pour l'email : " . htmlspecialchars($email));
}

// 5. RÉCUPÉRATION DES TICKETS
$stmt_tickets = $conn->prepare("SELECT * FROM vtiger_troubletickets WHERE contact_id = ?");
$stmt_tickets->bind_param("s", $idc); // "s" car contact_id est souvent stocké en string ou int selon version
$stmt_tickets->execute();
$result2 = $stmt_tickets->get_result();

if ($result2->num_rows > 0) {
    // CSS ET HTML
    echo '
    <style>
        .modal_ticket { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4); }
        .modal-content_ticket { background-color: #fefefe; margin: 15% auto; padding: 0px; border: 1px solid #888; width: 80%; font-family: Arial, sans-serif; }
        .modal-header_ticket { padding: 15px 15px; background-color: #8F3700; color: white; }
        .modal-body_ticket { padding: 2px 16px; }
        /* Style boutons générique */
        button { padding: 10px; cursor: pointer; }
    </style>
    <hr>';

    while ($identificationticket = $result2->fetch_assoc()) {
        $m_identifiant = $identificationticket["ticketid"];
        $m_numero = $identificationticket["ticket_no"];
        $priorite = $identificationticket["priority"];
        $status = $identificationticket["status"];
        $sujet = $identificationticket["title"];
        $travaux = $identificationticket["solution"];

        // Gestion couleurs
        $messagestatut = '<span style="color: #CF007C;">Ce ticket vous attend</span>';
        if ($status == "Closed") $messagestatut = '<span style="color: #5CCC00;">Ce ticket est cloturé</span>';
        elseif ($status == "Wait For Response") $messagestatut = '<span style="color: #0A00CF;">Le support valide votre ticket</span>';
        elseif ($status == "In Progress") $messagestatut = '<span style="color: #7700B3;">Vous travaillez sur ce ticket</span>';

        $messagepriorite = '<span style="color: #1B8C00;">Traitement non prioritaire</span>';
        if ($priorite == "Urgent") $messagepriorite = '<span style="color: #F70000;">Traitement immédiat</span>';
        elseif ($priorite == "High") $messagepriorite = '<span style="color: #ED4F00;">Traitement rapide</span>';
        elseif ($priorite == "Normal") $messagepriorite = '<span style="color: #ED8B00;">Traitement régulier</span>';

        // Récupération Description
        $mission = "";
        $stmt_desc = $conn->prepare("SELECT description FROM vtiger_crmentity WHERE crmid = ?");
        $stmt_desc->bind_param("i", $m_identifiant);
        $stmt_desc->execute();
        $res_desc = $stmt_desc->get_result();
        if ($res_desc->num_rows > 0) {
            $subinfosticket = $res_desc->fetch_assoc();
            $mission = $subinfosticket["description"];
        }
        $stmt_desc->close();

        // Affichage Bouton Ouverture Modal
        echo '
        <div style="margin: 15px 0px 15px;">
            <button id="myBtn' . $m_identifiant . '">Ticket N°' . $m_numero . ' - ' . htmlspecialchars($sujet) . '</button>
            <br><br><b>Statut : ' . $messagestatut . '</b><br><b>Priorité : ' . $messagepriorite . '</b>
        </div>
        <hr>';

        // CSS Dynamique pour le bouton Close spécifique
        echo '
        <style>
            .close' . $m_numero . ' { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
            .close' . $m_numero . ':hover, .close' . $m_numero . ':focus { color: black; text-decoration: none; cursor: pointer; }
        </style>';

        // Modal Content
        echo '
        <div id="myModal' . $m_identifiant . '" class="modal_ticket">
            <div class="modal-content_ticket">
                <div class="modal-header_ticket">
                    <span class="close' . $m_numero . '">&times;</span>
                    <h2>Ticket N°' . $m_numero . ' - ' . htmlspecialchars($sujet) . '</h2>
                </div>
                <div class="modal-body_ticket">
                    <br><b>Statut : ' . $messagestatut . '</b>
                    <br><b>Priorité : ' . $messagepriorite . '</b>
                    <br><br><b>Description des travaux à effectuer : </b><br>' . nl2br(htmlspecialchars($mission)) . '
                    <br><br><p>';

        // Logique Formulaires
        if ($status == "Open") {
            echo '
            <form action="" method="POST">
                <hr><br>Vous allez prendre en charge ce ticket.<br>
                <b>Faites attention à correctement planifier vos activités...</b><br>
                Pour confirmer cette prise en charge, veuillez cliquer sur le bouton <b>Prendre en charge ce ticket</b>.<br><br>
                <input type="hidden" name="tid" value="' . $m_identifiant . '">
                <input type="hidden" name="actions" value="">
                <input type="hidden" name="statut" value="In Progress">
                <input type="submit" value="Prendre en charge ce ticket">
            </form>';
        } elseif ($status == "In Progress") {
            echo '
            <form action="" method="POST">
                <hr><br>Vous allez marquer ce ticket comme terminé.<br>
                <b>La clôture du ticket implique une vérification administrative systématique.</b><br>
                Pour confirmer, veuillez saisir vos <b>Notes de fin de travaux</b>...<br><br>
                <label>Saisir les notes de fin de travaux : </label><br>
                <textarea required name="actions" rows="4" style="width:100%;"></textarea><br><br>
                <input type="hidden" name="tid" value="' . $m_identifiant . '">
                <input type="hidden" name="statut" value="Wait For Response">
                <input type="submit" value="Enregistrer et fermer ce ticket">
            </form>';
        } elseif ($status == "Wait For Response" || $status == "Closed") {
            echo '<b>Actions effectuées par le technicien :</b><br>' . nl2br(htmlspecialchars($travaux)) . '<br><br>';
            if ($status == "Wait For Response") {
                echo '<hr><br><b>Ce ticket est en cours de contrôle par le gestionnaire.</b><br>...<br><br>';
            }
        }

        echo '</p></div></div></div>';

        // Script JS pour la modale
        // Utilisation de ID unique pour les variables JS pour éviter conflits
        echo '
        <script>
            (function() {
                var modal = document.getElementById("myModal' . $m_identifiant . '");
                var btn = document.getElementById("myBtn' . $m_identifiant . '");
                var span = document.getElementsByClassName("close' . $m_numero . '")[0];
                
                if(btn) {
                    btn.onclick = function() {
                        modal.style.display = "block";
                    }
                }
                if(span) {
                    span.onclick = function() {
                        modal.style.display = "none";
                    }
                }
                // Fermeture si on clique en dehors
                window.addEventListener("click", function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                });
            })();
        </script>';
    }
} else {
    echo "<div style='padding:20px; font-family:Arial;'>Aucun ticket ne vous a été attribué pour l'instant (Email: $email).</div>";
}

$stmt_tickets->close();
$conn->close();
?>