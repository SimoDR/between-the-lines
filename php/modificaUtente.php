<?php
require_once('DBConnection.php');
require_once('sessione.php');
require_once('regex_checker.php');
require_once('setupPage.php');

//if the user isn't logged 401 error
if ($_SESSION["logged"] == false) {
    header('location: 401.php');
    exit;
}

$page = setup("../HTML/modificaUtente.html");
//connection errors
$error = "";
//other errors
$errorUsername = "";
$errorEmail = "";
$errorPassword = "";
//data fields value
$id=NULL;
$username = "";
$email = "";
$idPropic = "";
//the new pwd
$pwd1 = "";
$pwd2 = "";
//the old pwd from user
$pwd = "";
//the old data from db
$pwdOld = "";
$usernameOld="";
$emailOld="";

$obj_connection = new DBAccess();
if (!$obj_connection->openDBConnection()) {
    $error = $error . "<div class=\"errorMessage\">Errore di connessione al <span xml:lang=\"en\">database</span></div>";
} else {
    $id = $_SESSION["ID"];
    $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE ID=\"$id\"");
    $username = $queryResult[0]["username"];
    $email = $queryResult[0]["mail"];
    $usernameOld = $queryResult[0]["username"];
    $emailOld = $queryResult[0]["mail"];
    $idPropic = $queryResult[0]["id_propic"];
    $pwdOld = $queryResult[0]["password"];
    $id=$queryResult[0]["ID"];

//profile picture search and rendering
    $pictures = '';
    $result = $obj_connection->queryDB("SELECT * FROM foto_profilo");
    for ($i = 0; $i < count($result); $i++) {
        $checked = "";
        $path = $result[$i]['path_foto'];
        $alt = $result[$i]['alt_text'];
        $idPhoto=$result[$i]['ID'];
        if ($idPhoto == $idPropic)
            $checked = "checked=\"checked\"";
        $pictures = $pictures . "<li>
                            <input type=\"radio\" id=\"img$idPhoto\" name=\"propic\" value=\"$idPhoto\" $checked />
                            <label for=\"img$idPhoto\"><img src=\"$path\" alt=\"$alt\"/></label>
                        </li>";
    }
}
//set modifiche
if (isset($_POST["submitModifiche"])) {
    if (isset($_POST["oldPassword"])) {
        $pwd = $_POST["oldPassword"];
        $pwd = $obj_connection->escape_string(trim(htmlentities($pwd)));
        if ($pwdOld != $pwd) {
            $error = $error . "<div class=\"errorMessage\"><span xml:lang=\"en\">Password</span> attuale errata.</div>";
        }
    }
    if (isset($_POST["propic"])) {
        $idPropic = $_POST["propic"];
    }
    if (isset($_POST['user-email'])) {
        $email = $_POST['user-email'];
    }
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }
    if (isset($_POST['newPassword1'])) {
        $pwd1 = $_POST['newPassword1'];
    }
    if (isset($_POST['newPassword2'])) {
        $pwd2 = $_POST['newPassword2'];
    }

    if (!$obj_connection->openDBConnection()) {
        $error = $error . "<div class=\"errorMessage\">Errore di connessione al <span xml:lang=\"en\">database</span>.</div>";
    } else {
        $email = $obj_connection->escape_string(trim(htmlentities($email)));
        $username = $obj_connection->escape_string(trim(htmlentities($username)));
        $pwd1 = $obj_connection->escape_string(trim(htmlentities($pwd1)));
        $pwd2 = $obj_connection->escape_string(trim(htmlentities($pwd2)));
        //check mail
        if (!check_email($email)) {
            $errorEmail = $errorEmail . "<div class=\"errorMessage\">'L'<span xml:lang=\"en\">e-mail</span> inserita non è valida.</div>";
        }
        //check mail existence
        if ($obj_connection->queryDB("SELECT * FROM utenti WHERE mail=\"$email\" AND mail<>\"$emailOld\"")) {
            $errorEmail = $errorEmail . "<div class=\"errorMessage\">Esiste già un utente con questa <span xml:lang=\"en\">e-mail</span>.</div>";
        }
        //check username
        if (!check_username($username)) {
            $errorUsername = $errorUsername . "<div class=\"errorMessage\">Il nome utente deve essere lungo tra i 5 e i 30 caratteri e deve contenere solo lettere e numeri.</div>";
        }
        //check username existance
        if ($obj_connection->queryDB("SELECT * FROM utenti WHERE username=\"$username\" AND username<>\"$usernameOld\"")) {
            $errorUsername = $errorUsername . "<div class=\"errorMessage\">Esiste già un utente con questo <span xml:lang=\"en\">username</span>.</div>";
            //check password equality
            if ($pwd1 != $pwd2) {
                $errorPassword = $errorPassword . "<div class=\"errorMessage\">Le <span xml:lang=\"en\">password</span> non coincidono.</div>";
                //check password
                if (!check_pwd($pwd1)) {
                    $errorPassword = $errorPassword . "<div class=\"errorMessage\">La <span xml:lang=\"en\">password</span> deve essere lunga almeno 8 caratteri, contenere almeno una lettera maiuscola una minuscola e un numero.</div>";
                }
            }
        }
        if(empty($error) && empty($errorUsername) && empty($errorPassword) && empty($errorEmail)){
            if($pwd1==""){
                $pwd1=$pwdOld;
            }
            $idPropic=(int)$idPropic;
            $insertResult=$obj_connection->insertDB("UPDATE utenti SET username=\"$username\", password=\"$pwd1\", id_propic=$idPropic, mail=\"$email\"  WHERE ID=$id");
            if($insertResult){
                header('location: utente.php');
                exit;
            }
            else{
                $error = $error . "<div class=\"errorMessage\">Non hai effettuato nessuna modifica.</div>";
            }
        }
    }
}
$obj_connection->closeDBConnection();
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
