<?php

require_once("sessione.php");
require_once('DBConnection.php');

$page = file_get_contents("../html/utente.html");

//TODO: error handling della pagina: se un utente non è loggato che succ? 404?

/* crea connessione al DB */
if ($_SESSION['logged'] == true) {
    $email = $_SESSION['email'];
    $obj_connection = new DBAccess();
    if ($obj_connection->openDBConnection()) {
        $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE mail=\"$email\" ");
        if (!isset($queryResult)) {
            $error = "[La query non è andata a buon fine]";
        } else {
            $username = $queryResult[0]["username"];
            $idPic = $queryResult[0]["id_propic"];
            $proPic = $obj_connection->queryDB("SELECT * FROM foto_profilo WHERE ID=\"$idPic\" ");
            $pathPic = $proPic[0]["path_foto"];
            $altPic = $proPic[0]["alt_text"];
            $userInfo = "<img class=\"userPic\" src=\"$pathPic\" alt=\"$altPic\" \>
        <h2 class=\"userName\"> $username </h2>
        <p class=\"email\"> La tua <span xml:lang=\"en\" lang=\"en\">E-mail</span>: $email </p>";
        }
        $obj_connection->closeDBConnection();
    }
    else{
        //no db connection
    }
}
else {
    //404?
}

//elimina utente
$error = "";
if (isset($_POST["deleteUser"])) {
    $pwd = $_POST["userPwd"];
    if ($obj_connection->openDBConnection()) {
        $result = $obj_connection->insertDB("DELETE FROM utenti WHERE username= \"$username\" AND password=\"$pwd\"");
        if ($result) {
            header('location: logout.php'); //handle logout
            exit();
        } else {
            $error = $error . "<div class=\"msg_box error_box\"> la password inserita è errata </div>";
        }
    } else {
         //NO db connection
    }
}
$page = str_replace("<ERRORI/>", "$error", $page);
$page = str_replace("<INFO_UTENTE/>", "$userInfo", $page);

//if the user is admin he can access the admin functions
$buttons = '';
if ($_SESSION['permesso'] == 1) {
    $buttons = "<ul class=\"newAuthorGenre\">
        <li><a href=\"aggiungiAutore.php\">Aggiungi un nuovo autore</a></li>
        <li><a href=\"aggiungiGenere.php\">Aggiungi un nuovo genere</a></li>
    </ul>
    <a id=\"addBook\" href=\" ../php/stepWizard\" > Aggiungi un nuovo libro </a>";
    $page = str_replace("<BOTTONI_ADMIN/>", $buttons, $page);
}

//if the user is not admin he can delete his account
$delete = '';
if ($_SESSION['permesso'] == 0) {
    $delete = "<form id=\"deleteAccount\" class=\"print-hide cambio-info\"
          method=\"post\"
          action=\"../php/utente.php\">
        <fieldset class=\"form-fieldset fieldset-elimina-account\">
            <legend class=\"legend\">Eliminazione <span xml:lang=\"en\" lang=\"en\">account</span></legend>
            <ERRORI/>
            <label for=\"userPwd\">Per confermare inserisci la tua <span xml:lang=\"en\" lang=\"en\">password</span>:</label>
            <input type=\"password\"
                   id=\"userPwd\"
                   name=\"userPwd\"
                   class=\"barra-input\" />
            <input type=\"submit\"
                   name=\"deleteUser\"
                   value=\"Elimina\"
                   class=\"deleteUserBtn\" />
        </fieldset>
        <p id=\"WarningMessage\">
            <strong>Attenzione!</strong> L&apos;eliminazione
            dell&apos;<span xml:lang=\"en\" lang=\"en\">account</span> &egrave; irreversibile e comporta
            la rimozione di tutte le recensioni associate ad esso.
        </p>
    </form>";
    $page = str_replace("<ELIMINA_ACCOUNT/>", $delete, $page);
}

echo($page);

?>


