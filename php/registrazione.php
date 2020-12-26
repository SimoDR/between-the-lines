<?php

require_once("sessione.php");
//require_once('connessione.php');
require_once("regex_checker.php");


if ($_SESSION['logged'] == true) {
    header('location:index.php');
    exit();
}

/*Aggiunta header,menu e footer*/
$page = file_get_contents("../html/registrazione.html");

$mail = '';
$username = '';
$pwd = '';
$pwd2 = '';
$propic = 0;
$error = "";

if (isset($_POST['registrati'])) {
    if (isset($_POST['email'])) {
        $mail = $_POST['email'];
    }
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }
    if (isset($_POST['password'])) {
        $pwd = $_POST['password'];
    }
    if (isset($_POST['repeatpassword'])) {
        $pwd2 = $_POST['repeatpassword'];
    }
    if (isset($_POST['propic'])) {
        $propic = $_POST['propic'];
    }
    //db connection
    $obj_connection = new DBConnection();
    if (!$obj_connection->create_connection()) {
        $error = $error . "<div class=\"msg_box error_box\">Errore di connessione al database</div>";
    }
    //controllo input
    if (!check_email($mail)) {
        $error = $error . "<div class=\"msg_box error_box\">'La mail inserita non è valida.</div>";
    }
    if ($obj_connection->queryDB("SELECT * FROM utente WHERE Mail='" . $mail . "'")) {
        $error = $error . "<div class=\"msg_box error_box\">Esiste già un utente con questa mail.</div>";
    }
    if (!check_username($username)) {
        $error = $error . "<div class=\"msg_box error_box\">Il nome utente deve essere lungo tra i 5 e i 30 cratteri e deve contenere solo lettere e numeri</div>";
    }
    if ($pwd != $pwd2) {
        $error = $error . "<div class=\"msg_box error_box\">Le password non coincidono.</div>";
    }
    if (!check_pwd($pwd)) {
        $error = $error . "<div class=\"msg_box error_box\">La password deve essere lunga almeno 8 caratteri, contenere almeno una lettera maiuscola una minuscola e un numero.</div>";
    }

    if ($error = "") {
        $mail = $obj_connection->escape_str(trim(htmlentities($mail)));
        $username = $obj_connection->escape_str(trim(htmlentities($username)));
        $hashed_pwd = hash("sha256", $obj_connection->escape_str(trim($pwd)));
        //TODO: refactor the insert query
        $obj_connection->connessione->query("INSERT INTO `utente` (`ID`, `PWD`, `Mail`, `Nome`, `Cognome`, `Data_Nascita`, `ID_Foto`, `Ragione_Sociale`, `P_IVA`, `Permessi`, `Sesso`) VALUES (NULL,\"$hashed_pwd\", \"$mail\", \"$nome\", \"$cognome\", \"$datan\", \"$id_foto\", NULL, NULL, \"$permessi\", \"$sesso\")");
    }

    //check dati inseriti
    if (!$obj_connection->queryDB("SELECT * FROM utente WHERE Mail='" . $mail . "'")) {
        $error = "<div class=\"msg_box error_box\">Errore nell'inserimento dei dati</div>";
    } else {
        $obj_connection->close_connection();
        header('location: login.php');
        exit;
    }
    $obj_connection->close_connection();
}

$page = str_replace("<EMAIL/>", $mail, $page);
$page = str_replace("<USERNAME/>", $username, $page);
$page = str_replace("<PWD/>", $pwd, $page);
$page = str_replace("<PWD_CONFIRMATION/>", $pwd2, $page);
$page = str_replace("<ERROR/>", $error, $page);
echo $page;

?>