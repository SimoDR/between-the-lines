<?php
require_once("regex_checker.php");
require_once('DBConnection.php');

$page=file_get_contents("../html/inserisciGenere.html");
$error="";
if(isset($_POST["addAuthor"]))
{
    if(isset($_POST["genre"])){
        $genre=$_POST["genre"];
        //check with the name regex checker: can be more than a word
        if(check_nome($genre)) {
            if ($obj_connection = new DBAccess()) {
                $genre=strtoupper($genre);
                $queryresult=$obj_connection->queryDB("SELECT * FROM generi WHERE upper()");
                if (doesntexist) {


                } else {
                    $error = $error . "<div class=\"msg_box error_box\">Il genere che vuoi inserire esiste già</div>";
                }
            }
            else{
                $error = $error . "<div class=\"msg_box error_box\">Impossibile stabilire la connessione con il database</div>";
            }
        }
        else{
            $error=$error."<div class=\"msg_box error_box\">Il nome del genere deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
        }
    }
    else {
        $error=$error."<div class=\"msg_box error_box\"> Inserire il nome del genere da inserire</div>";
    }
}

echo($page);

?>
