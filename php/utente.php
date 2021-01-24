<?php

require_once("sessione.php");
require_once('DBConnection.php');
require_once('setupPage.php');

$page = setup("../HTML/utente.html");

//if the user isn't logged 401 error
if ($_SESSION["logged"] == false) {
    header('location: 401.php');
    exit;
}

/* crea connessione al DB */
$id = $_SESSION['ID'];
$obj_connection = new DBAccess();
$error = "";
$userInfo="";
if ($obj_connection->openDBConnection()) {
    $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE ID=$id");
    if (!isset($queryResult)) {
        $error = "<div class=\"errorMessage\"> La <span xml:lang=\"en\">query</span> non è andata a buon fine</div>";
    } else {
        $username = $queryResult[0]["username"];
        $email = $queryResult[0]["mail"];
        $idPic = $queryResult[0]["id_propic"];
        $proPic = $obj_connection->queryDB("SELECT * FROM foto_profilo WHERE ID=\"$idPic\" ");
        $pathPic = $proPic[0]["path_foto"];
        $altPic = $proPic[0]["alt_text"];
        $userInfo = "<div class=\"imgWrapper\"><img class=\"userPic\" src=\"$pathPic\" alt=\"$altPic\" /></div>
        <h2 class=\"userName\"> $username </h2>
        <p class=\"email\"> La tua <span xml:lang=\"en\">e-mail</span>: $email </p>";
    }
    $obj_connection->closeDBConnection();
} else {
    $error = "<div class=\"errorMessage\"> Impossibile connettersi al <span xml:lang=\"en\">database</span> </div>";
}

//elimina utente
if (isset($_POST["deleteUser"])) {
    $pwd = $_POST["userPwd"];
    if ($obj_connection->openDBConnection()) {
        $result = $obj_connection->insertDB("DELETE FROM utenti WHERE username= \"$username\" AND password=\"$pwd\"");
        if ($result) {
            header('location: logout.php'); //handle logout
            exit();
        } else {
            $error = $error . "<div class=\"errorMessage\"> La <span xml:lang=\"en\">password</span> inserita è errata </div>";
        }
    } else {
        $error = "<div class=\"errorMessage\"> Impossibile connettersi al <span xml:lang=\"en\">database</span> </div>";
    }
}
$page = str_replace("<ERRORI/>", "$error", $page);
$page = str_replace("<INFO_UTENTE/>", "$userInfo", $page);

//if the user is admin he can access the admin functions
$buttons = '';
if ($_SESSION['permesso'] == 1) {
    $buttons = "<ul class=\"newAuthorGenre\">
        <li><a href=\"inserisciAutore.php\">Aggiungi un nuovo autore</a></li>
        <li><a href=\"inserisciGenere.php\">Aggiungi un nuovo genere</a></li>
    </ul>
    <a id=\"addBook\" href=\"inserisciLibro.php\" > Aggiungi un nuovo libro </a>";
}
$page = str_replace("<BOTTONI_ADMIN/>", $buttons, $page);
//if the user is not admin he can delete his account
$delete = '';
if ($_SESSION['permesso'] == 0) {
    $delete = "<form id=\"deleteAccount\" class=\"print-hide cambio-info\"
          method=\"post\"
          action=\"../php/utente.php\">
        <fieldset class=\"formF\">
            <legend class=\"legend\">Eliminazione <span xml:lang=\"en\">account</span></legend>
            <ERRORI/>
            <label for=\"userPwd\">Per confermare inserisci la tua <span xml:lang=\"en\">password</span>:</label>
            <input type=\"password\" id=\"userPwd\" name=\"userPwd\"                 />
            <input type=\"submit\" name=\"deleteUser\" value=\"Elimina\" class=\"button\" />
        </fieldset>
        <p id=\"warningMessage\">
            <strong>Attenzione!</strong> L&apos;eliminazione dell&apos;<span xml:lang=\"en\">account</span> &egrave; irreversibile e comporta la rimozione di tutte le recensioni associate ad esso.
        </p>
    </form>";
}
$page = str_replace("<ELIMINA_ACCOUNT/>", $delete, $page);
echo($page);

?>


