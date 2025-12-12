<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stagiaire625";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tid = $_POST['tid'];
$statut = $_POST['statut'];
$action = htmlspecialchars($_POST['actions'], ENT_QUOTES, 'UTF-8');

$current_user = wp_get_current_user($conn);
$email = $current_user->user_email;

function wp_get_current_user($conn)
{
    $sql = "SELECT * FROM vtiger_contactdetails WHERE email = 'maximecarpentier@tystand.fr'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error: " . $conn->error;
        return null;
    }

    return $result->fetch_assoc();
}

if (isset($tid) && isset($statut) && isset($action)) {
    $sql_maj = "UPDATE vtiger_troubletickets SET status='$statut',solution='$action' WHERE ticketid='$tid'";
    if ($conn->query($sql_maj) === FALSE) {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM vtiger_contactdetails WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows >= 1) {
    while ($identifiant_correspondance = $result->fetch_assoc()) {
        $idc = $identifiant_correspondance["contactid"];
    }

    $sql2 = "SELECT * FROM vtiger_troubletickets WHERE contact_id='$idc'";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        echo '
<style>
    .modal_ticket {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content_ticket {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 0;
        border: 1px solid #888;
        width: 80%;
    }

    .modal-header_ticket {
        padding: 15px 15px;
        background-color: #8F3700;
        color: white;
    }

    .modal-body_ticket {
        padding: 2px 16px;
    }
</style>';

        echo '
<hr>';
        while ($identification_ticket = $result2->fetch_assoc()) {
            $m_identifiant = $identification_ticket["ticketid"];
            $m_numero = $identification_ticket["ticket_no"];
            $priorite = $identification_ticket["priority"];
            $status = $identification_ticket["status"];
            $sujet = $identification_ticket["title"];
            $travaux = $identification_ticket["solution"];

            if ($status == "Closed") {
                $message_statut = '<span style="color: #5CCC00;">Ce ticket est cloturé</span>';
            } else if ($status == "Wait For Response") {
                $message_statut = '<span
        style="color: #0A00CF;">Le support valide votre ticket</span>';
            } else if ($status == "In Progress") {
                $message_statut = '<span
        style="color: #7700B3;">Vous travaillez sur ce ticket</span>';
            } else if ($status == "Open") {
                $message_statut = '<span style="color: #CF007C;">Ce ticket vous attend</span>';
            }

            if ($priorite == "Urgent") {
                $message_priorite = '<span style="color: #F70000;">Traitement immédiat</span>';
            } else if ($priorite == "High") {
                $message_priorite = '<span style="color: #ED4F00;">Traitement rapide</span>';
            } else if ($priorite == "Normal") {
                $message_priorite = '<span style="color: #ED8B00;">Traitement régulier</span>';
            } else if ($priorite == "Low") {
                $message_priorite = '<span style="color: #1B8C00;">Traitement non prioritaire</span>';
            }

            $sql3 = "SELECT * FROM vtiger_crmentity WHERE crmid='$m_identifiant'";
            $result3 = $conn->query($sql3);

            if ($result3->num_rows > 0) {
                while ($sub_infos_ticket = $result3->fetch_assoc()) {
                    $mission = $sub_infos_ticket["description"];
                }
            }

            echo '
<div style="margin: 15px 0 15px;">
    <button id="myBtn' . $m_identifiant . '">Ticket N°' . $m_numero . ' - ' . $sujet . '</button>
    <br><br><b>Statut : ' . $message_statut . '</b><br><b>Priorité : ' . $message_priorite . '</b></div>
<hr>';

            echo '
<style>
    .close

    ' . $m_numero . '
    {
        color: #aaa
    ;
        float: right
    ;
        font-size: 28px
    ;
        font-weight: bold
    ;
    }

    .close

    ' . $m_numero . '
    :hover,
    .close

    ' . $m_numero . ',
    :focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<div id="myModal' . $m_identifiant . '" class="modal_ticket">
    <div class="modal-content_ticket">
        <div class="modal-header_ticket">
            <span class="close' . $m_numero . '">&times;</span>
            <h2>Ticket N°' . $m_numero . ' - ' . $sujet . '</h2>
        </div>
        <div class="modal-body_ticket">
            <br>
            <b>Statut : ' . $message_statut . '</b>
            <br><b>Priorité : ' . $message_priorite . '</b>
            <br><br>
            <b>Description des travaux à effectuer : </b><br>' . $mission . '
            <br><br>
            <p>';

            if ($status == "Open") {
                echo '
            <form action="" method="POST">
                <hr>
                <br>
                Vous allez prendre en charge ce ticket.
                <br><b>Faites attention à correctement planifier vos activités en fonction des tickets assignés et ne
                    pas vous surcharger de travail.</b>
                <br>Pour confirmer cette prise en charge, veuillez cliquer sur le bouton <b>Prendre en charge ce
                    ticket</b>.
                <br><br>
                <input type="hidden" id="tid" name="tid" value="' . $m_identifiant . '">
                <input type="hidden" id="actions" name="actions" value="">
                <input type="hidden" id="statut" name="statut" value="In Progress">
                <input type="submit" value="Prendre en charge ce ticket">
            </form>
            ';
            } else if ($status == "In Progress") {
                echo '
            <form action="" method="POST">
                <hr>
                <br>
                Vous allez marquer ce ticket comme terminé.
                <br><b>La clôture du ticket implique une vérification administrative systématique.</b>
                <br>Pour confirmer, veuillez saisir vos <b>Notes de fin de travaux</b> et cliquer sur le bouton <b>Enregistrer
                    et fermer ce ticket</b>.
                <br><br>
                <label for="fname">Saisir les notes de fin de travaux : </label><br>
                <textarea required id="actions" name="actions" rows="4" cols="50"></textarea>
                <input type="hidden" id="tid" name="tid" value="' . $m_identifiant . '">
                <input type="hidden" id="statut" name="statut" value="Wait For Response">
                <input type="submit" value="Enregistrer et fermer ce ticket">
            </form>
            ';
            } else if ($status == "Wait For Response") {
                echo '<b>Actions effectuées par le technicien :</b>
            <br>' . $travaux . '
            <br><br>
            <hr>
            <br><b>Ce ticket est en cours de contrôle par le gestionnaire.</b>
            <br>Une fois vos travaux validés, ce ticket sera archivé et un email sera envoyé au client.
            <br><b>Dans le cas contraire, le gestionnaire vous contactera pour faire des modifications.</b>
            <br><br>';
            } else if ($status == "Closed") {
                echo '<b>Actions effectuées par le technicien :</b>
            <br>' . $travaux . '
            <br><br>';
            }

            echo '</p>
        </div>
    </div>
</div>';

            $m_numero      = (int) $m_numero; // on sécurise
            $m_identifiant = htmlspecialchars($m_identifiant, ENT_QUOTES);

            echo "<script>
    (function() {
        let fenetre = document.getElementById('myModal{$m_identifiant}');
        let btn     = document.getElementById('myBtn{$m_identifiant}');
        let span    = document.getElementsByClassName('close{$m_numero}')[0];

        if (!fenetre || !btn || !span) return;

        btn.onclick = function () {
            fenetre.style.display = 'block';
        };

        span.onclick = function () {
            fenetre.style.display = 'none';
        };

        window.onclick = function (event) {
            if (event.target === fenetre) {
                fenetre.style.display = 'none';
            }
        };
    })();
</script>";


        }
    }
} else {
    echo "Aucun ticket ne vous a été attribué pour l'instant.";
}
$conn->close();