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
    $$DBconnection = new DBAccess();
    if ($$DBconnection->openDBConnection()) {
        //TODO: capire se altri controlli debbano essere fatti backend per sanificare l'input
        $email = $$DBconnection->escape_string(trim($email));
        $hashed_pwd = $$DBconnection->escape_string(hash("sha256", trim($pwd)));

        //check to the db
        $queryResult = $$DBconnection->queryDB("SELECT * FROM utenti WHERE mail=\"$email\" AND password=\"$hashed_pwd\"");
        if (!isset($queryResult)) {
            $error = "[La query non è andata a buon fine]";
        } else {
            if (empty($queryResult)) {
                $error = "[Le credenziali inserite non sono corrette]";
            } else {
                $_SESSION['logged'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['ID'] = $queryResult[0]['ID'];
                //TODO: refactor in db
                $_SESSION['permesso'] = $queryResult[0]['is_admin'];
                header('location: index.php');
                exit;
            }
        }
        $$DBconnection->closeDBConnection();
    } else {
        //TODO: gestire in modo diverso l'errore di connessione al db
        $error = "[La query non è andata a buon fine]";
    }
}
//TODO: riguardare le 2 righe successive
$error = str_replace("[", '<p class="msg_box error_box">', $error);
$error = str_replace("]", "</p>", $error);

$page = str_replace("<ERROR/>", $error, $page);
$page = str_replace("<EMAIL/>", $email, $page);
$page = str_replace("<PASSWORD/>", $pwd, $page);

echo $page;
?>