<?php
require_once('sessione.php');

/*Aggiunta header e menu*/
$page = file_get_contents("../html/login.html");

$error = '';
$email = '';
$pwd = '';

/* se ci sono valori in _POST cerca di fare il login o stampa errore */
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (isset($_POST['password'])) {
        $pwd = $_POST['password'];
    }

    /* crea connessione al DB */
    require_once('DBConnection.php');
    $obj_connection = new DBAccess();
    if ($obj_connection->openDBConnection()) {
        //TODO: capire se altri controlli debbano essere fatti backend per sanificare l'input
        $email = $obj_connection->escape_string(trim(htmlentities($email)));
        $pwd = $obj_connection->escape_string(trim(htmlentities($pwd)));

        //check to the db
        $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE mail=\"$email\" AND password=\"$pwd\"");
        if (!isset($queryResult)) {
            $error = "<div class=\"msg_box error_box\"> La query non è andata a buon fine</div>";
        } else {
            if (empty($queryResult)) {
                $error = "<div class=\"msg_box error_box\"> Le credenziali inserite non sono corrette</div>";
            } else {
                $_SESSION['logged'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['ID'] = $queryResult[0]['ID'];
                //permesso is bool: 0 user, 1 admin
                $_SESSION['permesso'] = $queryResult[0]['is_admin'];
                header('location: index.php');
                exit;
            }
        }
        $obj_connection->closeDBConnection();
    } else {
        $error = "<div class=\"msg_box error_box\"> Impossibile connettersi al database </div>";
    }
}

$page = str_replace("<ERROR/>", $error, $page);
$page = str_replace("<EMAIL/>", $email, $page);
$page = str_replace("<PASSWORD/>", $pwd, $page);

echo $page;
?>