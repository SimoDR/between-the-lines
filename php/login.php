<?php
require_once('sessione.php');

/*Aggiunta header e menu*/
$page = file_get_contents("../html/login.html");

$error = '';
/* se ci sono valori in _POST cerca di fare il login o stampa errore */
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (isset($_POST['password'])) {
        $pwd = $_POST['password'];
    }
    if (isset($_POST['remember_me'])) {
        $check = 'checked="checked"';
    }

    /* crea connessione al DB */
    require_once('DBConnection.php');
    $obj_connection = new DBAccess();

    if ($obj_connection->openDBConnection()) {

        $email = $obj_connection->trim($email);
        $hashed_pwd = hash("sha256", $obj_connection->trim($pwd));

        //check to the db
        $queryResult = $obj_connection->queryDB("SELECT * FROM utente WHERE Mail=\"$email\" AND PWD=\"$hashed_pwd\"")
        if (!isset($queryResult)) {
            $error = "[La query non è andata a buon fine]";
        } else {
            if (empty($queryResult)) {
                $error = "[Le credenziali inserite non sono corrette]";
            } else {
                $_SESSION['logged'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['ID'] = $queryResult[0]['ID'];
                $_SESSION['permesso'] = $queryResult[0]['Permessi'];
            }

            $obj_connection->close_connection();

            header('location: index.php');
            exit;
        }
} else {
    $error = (new errore('DBConnection'))->printHTMLerror();
}

}

$error = str_replace("[", '<p class="msg_box error_box">', $error);
$error = str_replace("]", "</p>", $error);
$page = str_replace("%ERROR%", $error, $page);
$page = str_replace("%VALUE_EMAIL%", $email, $page);
$page = str_replace("%VALUE_PASSWORD%", $pwd, $page);
$page = str_replace("%CHECKED%", $check, $page);

echo $page;
?>