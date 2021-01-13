<?php

require_once("sessione.php");
require_once('DBConnection.php');
require_once("regex_checker.php");

//se l'utente è già loggato
if ($_SESSION['logged'] == true) {
    header('location:index.php');
    exit();
}

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
    $$DBconnection = new DBAccess();
    if (!$$DBconnection->openDBConnection()) {
        $error = $error . "<div class=\"msg_box error_box\">Errore di connessione al database</div>";
    }
    //check mail
    if (!check_email($mail)) {
        $error = $error . "<div class=\"msg_box error_box\">'La mail inserita non è valida.</div>";
    }
    //check existence
    if ($$DBconnection->queryDB("SELECT * FROM utenti WHERE mail='" . $mail . "'")) {
        $error = $error . "<div class=\"msg_box error_box\">Esiste già un utente con questa mail.</div>";
    }
    //check username
    if (!check_username($username)) {
        $error = $error . "<div class=\"msg_box error_box\">Il nome utente deve essere lungo tra i 5 e i 30 caratteri e deve contenere solo lettere e numeri</div>";
    }
    //check password equality
    if ($pwd != $pwd2) {
        $error = $error . "<div class=\"msg_box error_box\">Le password non coincidono.</div>";
    }
    //check password
    if (!check_pwd($pwd)) {
        $error = $error . "<div class=\"msg_box error_box\">La password deve essere lunga almeno 8 caratteri, contenere almeno una lettera maiuscola una minuscola e un numero.</div>";
    }
    //insert new user
    if ($error == "") {
<<<<<<< HEAD
        $mail = $$DBconnection->escape_string(trim(htmlentities($mail)));
        $username = $$DBconnection->escape_string(trim(htmlentities($username)));
        $hashed_pwd = hash("sha256", $$DBconnection->escape_string(trim($pwd)));

        $query = "INSERT INTO utenti(ID,username, password, id_propic, mail, is_admin) VALUES (NULL, \"$username\",\"$hashed_pwd\", $propic , \"$mail\", 0)";
        $queryResult =$$DBconnection->insertDB($query);
        $$DBconnection->closeDBConnection();
=======
        $mail = $obj_connection->escape_string(trim(htmlentities($mail)));
        $username = $obj_connection->escape_string(trim(htmlentities($username)));
        $pwd = $obj_connection->escape_string(trim(htmlentities($pwd)));

        $query = "INSERT INTO utenti(ID,username, password, id_propic, mail, is_admin) VALUES (NULL, \"$username\",\"$pwd\", $propic , \"$mail\", 0)";
        $queryResult =$obj_connection->insertDB($query);
        $obj_connection->closeDBConnection();
>>>>>>> a6122d1b5890c13f751b8942b5ac6d95c3debb0d

        //check dati inseriti
        if (!$queryResult) {
            $error = "<div class=\"msg_box error_box\">Errore nell'inserimento dei dati</div>";
        } else {
            header('location: login.php');
            exit;
        }
    }
}
//profile picture search and rendering
$pictures='';
$$DBconnection = new DBAccess();
if(!$$DBconnection->openDBConnection()){
    $error=$error."<div class=\"msg_box error_box\">Errore di connessione al database</div>";
}
else{
<<<<<<< HEAD
    //TODO: nothing is checked by default. Is it a probem?
    $result=$$DBconnection->queryDB("SELECT * FROM foto_profilo");
=======
    //TODO: nothing is checked by default. Find a way to default check a radiob
    $result=$obj_connection->queryDB("SELECT * FROM foto_profilo");
>>>>>>> a6122d1b5890c13f751b8942b5ac6d95c3debb0d
    for ($i = 0; $i < count($result); $i++){
        $checked="";
        $path=$result[$i]['path_foto'];
        $alt=$result[$i]['alt_text'];
        $id=$result[$i]['ID'];
        if(i==0)
        {
            $checked="checked=\"checked\"";
        }
        $pictures=$pictures."<div>
                            <input type=\"radio\" id=\"$id\" name=\"propic\" value=\"$id\" \>
                            <label for=\"$id\"><img src=\"$path\" alt=\"$alt\"></label>
                        </div>";

    }
    $$DBconnection->closeDBConnection();;
}
//php tag replacement
$page = str_replace("<EMAIL/>", $mail, $page);
$page = str_replace("<USERNAME/>", $username, $page);
$page = str_replace("<PWD/>", $pwd, $page);
$page = str_replace("<PWD_CONFERMA/>", $pwd2, $page);
$page = str_replace("<ERROR/>", $error, $page);
$page=str_replace("<FOTO_PROFILO/>", $pictures, $page);
echo $page;

?>