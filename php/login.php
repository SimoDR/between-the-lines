<?php
require_once('sessione.php');
//TODO: se sei già loggato non è che puoi tornare qui come ti pare ooh: errore o reindirizzamento in home?
/*Aggiunta header e menu*/
$page = file_get_contents("../html/login.html");

$error = '';
$username = '';
$pwd = '';

$formLibro = '';
 if(isset($_GET['id_libro'])) {
    $formLibro = '<input type="hidden" name="id_libro" value = "' . $_GET['id_libro'] . '"/> ';
}



/* se ci sono valori in _POST cerca di fare il login o stampa errore */
if(isset($_POST['login'])) {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }
    if (isset($_POST['password'])) {
        $pwd = $_POST['password'];
    }
    
    

    /* crea connessione al DB */
    require_once('DBConnection.php');
    $obj_connection = new DBAccess();
    if ($obj_connection->openDBConnection()) {
        $username = $obj_connection->escape_string(trim(htmlentities($username)));
        $pwd = $obj_connection->escape_string(trim(htmlentities($pwd)));

        //check to the db
        $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE username=\"$username\" AND password=\"$pwd\"");
        if (!isset($queryResult)) {
            $error = "<div class=\"msg_box error_box\"> La <span xml:lang=\"en\" lang=\"en\">query</span> non è andata a buon fine</div>";
        } else if (empty($queryResult)) {
                $error = "<div class=\"msg_box error_box\"> Le credenziali inserite non sono corrette</div>";
            } else {
                $_SESSION['logged'] = true;
                $_SESSION['ID'] = $queryResult[0]['ID'];
                //permesso is bool: 0 user, 1 admin
                $_SESSION['permesso'] = $queryResult[0]['is_admin'];
                
                if(isset($_POST['id_libro'])) {
                    $destinazione = $_POST['id_libro'];
                    header('location: dettagliLibro.php?id_libro='. $destinazione);
                    exit;
                }
                else {
                    header('location: index.php');
                    exit;
                }
            }
        $obj_connection->closeDBConnection();
    } else {
        $error = "<div class=\"msg_box error_box\"> Impossibile connettersi al <span xml:lang=\"en\" lang=\"en\">database</span> </div>";
    }
}


$page = str_replace("<FORM_LIBRO/>", $formLibro, $page);
$page = str_replace("<ERROR/>", $error, $page);
$page = str_replace("<USERNAME/>", $username, $page);
$page = str_replace("<PASSWORD/>", $pwd, $page);

echo $page;

?>