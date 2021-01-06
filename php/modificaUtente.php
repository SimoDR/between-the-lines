<?php

$page=file_get_contents("../html/modificaUtente.html");
//connection errors
$error="";
//other errors
$errorUsername="";
$errorEmail="";
$errorPassword="";
//data fields value
$username="";
$email="";
//TODO: construct the logic of the page: fullfill form
$obj_connection = new DBAccess();
if (!$obj_connection->openDBConnection()) {
    $error = $error . "<div class=\"msg_box error_box\">Errore di connessione al database</div>";
}
else {
    $id=$_SESSION["ID"];
    $queryResult=$obj_connection->queryDB("SELECT * FROM utenti WHERE ID=\"$id\"");
    $username=$queryResult[0]["username"];
    $email=$queryResult[0]["email"];
}

//TODO: add pro pics


//TODO: build the query to modify the user data in the db

//value of the data fields replacing
$page=str_replace("<USERNAME/>", "$username", $page);
$page=str_replace("<EMAIL/>", "$email", $page);
//error replacing
$page=str_replace("<ERRORI/>", "$error", $page);
$page=str_replace("<ERRORI_USERNAME/>", "$errorUsername", $page);
$page=str_replace("<ERRORI_EMAIL/>", "$errorEmail", $page);
$page=str_replace("<ERRORI_PASSWORD/>", "$errorPassword", $page);
echo($page);
?>
