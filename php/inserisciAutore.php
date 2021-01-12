<?php
require_once("regex_checker.php");
require_once('DBConnection.php');

$page = file_get_contents("../html/inserisciAutore.html");
$error = "";
$name = "";
$surname = "";
$birthDate = "";
$deathDate = "NULL";
if (isset($_POST["addAuthor"])) {
    if (isset($_POST["authorName"])) {
        $name = $_POST["authorName"];
    }
    if (isset($_POST["authorSurname"])) {
        $surname = $_POST["authorSurname"];
    }
    if (isset($_POST["birthDate"])) {
        $birthDate = $_POST["birthDate"];
    }
    if (isset($_POST["deathDate"])) {
        $deathDate = $_POST["deathDate"];
    }
}
    //check with the name regex checker: can be more than a word
    if (!check_nome($name)) {
        $error = $error . "<div class=\"msg_box error_box\">Il nome dell'autore deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
    }
    if (!check_nome($surname)) {
        $error = $error . "<div class=\"msg_box error_box\">Il cognome dell'autore deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
    }
    if ($birthDate > $deathDate) {
        $error = $error . "<div class=\"msg_box error_box\">La data di nascita deve essere precedente alla data di morte.</div>";
    }
    if(empty($error)){
    $obj_connection = new DBAccess();
    if ($obj_connection->openDBConnection()) {
        //can't exist 2 author with the same name & surname
        $queryResult = $obj_connection->queryDB("SELECT * FROM generi WHERE nome=\"$name\" AND cognome=\"$surname\"");
        if (empty($queryResult)) {
            $queryInsert = $obj_connection->insertDB("INSERT INTO autori VALUES(NULL, \"$name\", \"$surname\", \"$birthDate\", \"$deathDate\")");
            if ($queryInsert) {
                header('location: index.php');
                exit;
            } else {
                $error = $error . "<div class=\"msg_box error_box\">l'inserimento non è andato a buon fine</div>";
            }
        } else {
            $error = $error . "<div class=\"msg_box error_box\"> Esiste già un autore con questo nome e cognome</div>";
        }
        $obj_connection->closeDBConnection();
    } else {
        $error = $error . "<div class=\"msg_box error_box\">Impossibile stabilire la connessione con il database</div>";
    }
    }
$page = str_replace("<DATA_MORTE/>", "$deathDate", $page);
$page = str_replace("<DATA_NASCITA/>", "$birthDate", $page);
$page = str_replace("<COGNOME/>", "$surname", $page);
$page = str_replace("<NOME/>", "$name", $page);
$page = str_replace("<ERROR/>", "$error", $page);
echo($page);

?>