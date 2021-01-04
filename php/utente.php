<?php

require_once("sessione.php");
require_once('DBConnection.php');

$page = file_get_contents("../html/utente.html");

//TODO: error handling di tutta la pagina e formttazione corretta

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
            $idPic=$queryResult[0]["id_propic"];
            $proPic = $obj_connection->queryDB("SELECT * FROM foto_profilo WHERE ID=\"$idPic\" ");
            $pathPic = $proPic[0]["path_foto"];
            $altPic = $proPic[0]["alt_text"];
            $userInfo = "<img class=\"userPic\" src=\"$pathPic\" alt=\"$altPic\" \>
        <h2 class=\"userName\"> $username </h2>
        <p class=\"email\"> La tua <span xml:lang=\"en\" lang=\"en\">E-mail</span>: $email </p>";
        }
    }
    $obj_connection->closeDBConnection();
}
//elimina utente
$message="";
if(isset($_POST["deleteUser"])) {
    $pwd=$_POST["userPwd"];
    if($obj_connection->openDBConnection()) {

        $result = $obj_connection->insertDB("DELETE * FROM utenti WHERE password=\"$pwd\"");
        if($result){
            //TODO: effettuare il logout: creare funzione apposta
            header('location: login.php');
            exit;
        }
        else{
            $message="la password inserita è errata";
        }
    }
    else{

    }
}

$page=str_replace("<INFO_UTENTE/>", "$userInfo", $page);
echo($page);

?>


