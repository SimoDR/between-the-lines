<?php
require_once('DBConnection.php');
require_once('sessione.php');

$page = file_get_contents("../html/modificaUtente.html");
//connection errors
$error = "";
//other errors
$errorUsername = "";
$errorEmail = "";
$errorPassword = "";
//data fields value
$username = "";
$email = "";
//TODO: construct the logic of the page: fullfill form
$obj_connection = new DBAccess();
if (!$obj_connection->openDBConnection()) {
    $error = $error . "<div class=\"msg_box error_box\">Errore di connessione al database</div>";
} else {
    $id = $_SESSION["ID"];
    $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE ID=\"$id\"");
    $username = $queryResult[0]["username"];
    $email = $queryResult[0]["mail"];
//profile picture search and rendering
    $pictures = '';
    $result = $obj_connection->queryDB("SELECT * FROM foto_profilo");
    for ($i = 0; $i < count($result); $i++) {
        $checked="";
        $path = $result[$i]['path_foto'];
        $alt = $result[$i]['alt_text'];
        $id = $result[$i]['ID'];
        if($id==$queryResult[0]["id_propic"])
            $checked="checked=\"checked\"";
        //TODO: help needed! control id name & value
        $pictures = $pictures . "<div>
                            <input type=\"radio\" id=\"$id\" name=\"propic\" value=\"$id\" $checked />
                            <label for=\"$id\"><img src=\"$path\" alt=\"$alt\"></label>
                        </div>";

    }
    $obj_connection->closeDBConnection();;
}

//TODO: build the query to modify the user data in the db

//value of the data fields replacing
    $page = str_replace("<FOTO_PROFILO/>", "$pictures", $page);
$page = str_replace("<USERNAME/>", "$username", $page);
$page = str_replace("<EMAIL/>", "$email", $page);
//error replacing
$page = str_replace("<ERRORI/>", "$error", $page);
$page = str_replace("<ERRORI_USERNAME/>", "$errorUsername", $page);
$page = str_replace("<ERRORI_EMAIL/>", "$errorEmail", $page);
$page = str_replace("<ERRORI_PASSWORD/>", "$errorPassword", $page);
echo($page);
?>
