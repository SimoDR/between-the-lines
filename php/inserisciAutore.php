<?php

require_once("sessione.php");
require_once("regex_checker.php");
require_once('DBConnection.php');
require_once('setupPage.php');

$page = setup("../HTML/inserisciAutore.html");

$message = "";
$error = "";
$name = "";
$surname = "";
$birthDate = "";
$deathDate = "";
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
    //check with the name regex checker: can be more than a word
    if (!check_nome($name)) {
        $error = $error . "<div class=\"msg_box error_box\">Il nome dell'autore deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
    }
    if (!check_nome($surname)) {
        $error = $error . "<div class=\"msg_box error_box\">Il cognome dell'autore deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
    }
    if ($deathDate != NULL) {
        if ($birthDate > $deathDate) {
            $error = $error . "<div class=\"msg_box error_box\">La data di nascita deve essere precedente alla data di morte.</div>";
        }
    }
    if (empty($error)) {
        $obj_connection = new DBAccess();
        if ($obj_connection->openDBConnection()) {
            $name = $obj_connection->escape_string(trim(htmlentities($name)));
            $surname = $obj_connection->escape_string(trim(htmlentities($surname)));
            //can't exist 2 author with the same name & surname (non case sensitive research)
            $nameUpper=strtoupper($name);
            $surnameUpper=strtoupper($surname);
            $queryResult = $obj_connection->queryDB("SELECT * FROM autori WHERE upper(nome)=\"$nameUpper\" AND upper(cognome)=\"$surnameUpper\"");
            if (empty($queryResult)) {
                //still in life :)
                if($deathDate!=NULL) {
                    $queryInsert = $obj_connection->insertDB("INSERT INTO autori VALUES(NULL, \"$name\", \"$surname\", \"$birthDate\", \"$deathDate\")");
                }
                //dead author :(
                else{
                    $queryInsert = $obj_connection->insertDB("INSERT INTO autori VALUES(NULL, \"$name\", \"$surname\", \"$birthDate\", NULL)");
                }
                if ($queryInsert) {
                    $name = "";
                    $surname = "";
                    $birthDate = "";
                    $deathDate = "";
                    $message = "<div class=\"msg_box success_box\">Inserimento avvenuto con successo.</div>";
                } else {
                    $error = $error . "<div class=\"msg_box error_box\">l'inserimento non è andato a buon fine</div>";
                }
            } else {
                $error = $error . "<div class=\"msg_box error_box\"> Esiste già un autore con questo nome e cognome</div>";
            }
            $obj_connection->closeDBConnection();
        } else {
            $error = $error . "<div class=\"msg_box error_box\">Impossibile stabilire la connessione con il <span xml:lang=\"en\" lang=\"en\">database</span></div>";
        }
    }
}
$page = str_replace("<SUCCESSO/>", "$message", $page);
$page = str_replace("<DATA_MORTE/>", "$deathDate", $page);
$page = str_replace("<DATA_NASCITA/>", "$birthDate", $page);
$page = str_replace("<COGNOME/>", "$surname", $page);
$page = str_replace("<NOME/>", "$name", $page);
$page = str_replace("<ERROR/>", "$error", $page);
$page = str_replace("<PATH/>", '<a href="utente.php">Profilo utente</a>', $page);

echo($page);

?>