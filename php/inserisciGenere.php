<?php

require_once("sessione.php");
require_once("regex_checker.php");
require_once('DBConnection.php');
require_once('setupPage.php');

$page=setup("../HTML/inserisciGenere.html");

$message="";
$error="";
$genre="";
if(isset($_POST["addGenre"]))
{
    if(isset($_POST["genreName"])){
        $genre=$_POST["genreName"];
        //check with the name regex checker: can be more than a word
        if(check_nome($genre)) {
            $obj_connection = new DBAccess();
            if ($obj_connection->openDBConnection()) {
                $genre = $obj_connection->escape_string(trim(htmlentities($genre)));
                $genreUpper=strtoupper($genre);
                //non case sensitive research
                $queryResult=$obj_connection->queryDB("SELECT * FROM generi WHERE upper(nome)=\"$genreUpper\"");
                if (!$queryResult) {
                    $queryInsert=$obj_connection->insertDB("INSERT INTO generi VALUES (NULL, \"$genre\")");
                    if($queryInsert){
                        $genre="";
                        $message="<div class=\"successMessage\">Inserimento avvenuto con successo.</div>";
                    }
                    else{
                        $error = $error . "<div class=\"msg_box error_box\">L'inserimento del genere non è andato a buon fine</div>";
                    }
                } else {
                    $error = $error . "<div class=\"msg_box error_box\">Il genere che vuoi inserire esiste già</div>";
                }
                $obj_connection->closeDBConnection();
            }
            else{
                $error = $error . "<div class=\"msg_box error_box\">Impossibile stabilire la connessione con il <span xml:lang=\"en\" lang=\"en\">database</span></div>";
            }
        }
        else{
            $error=$error."<div class=\"msg_box error_box\">Il nome del genere deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
        }
    }
    else {
        $error=$error."<div class=\"msg_box error_box\"> Inserisci il nome del genere da inserire</div>";
    }
}
$page = str_replace("<SUCCESSO/>", "$message", $page);
$page = str_replace("<GENERE/>", "$genre", $page);
$page = str_replace("<ERROR/>", "$error", $page);

echo($page);

?>
