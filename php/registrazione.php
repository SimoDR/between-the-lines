<?php

require_once("sessione.php");
require_once('DBConnection.php');
require_once("regex_checker.php");
require_once('setupPage.php');


//se l'utente è già loggato
if ($_SESSION['logged'] == true) {
    header('location:index.php');
    exit();
}

$page = setup("../HTML/registrazione.html");

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
    $obj_connection = new DBAccess();

    if (!$obj_connection->openDBConnection()) {
        $error = $error . "<div class=\"errorMessage\">Errore di connessione al <span xml:lang=\"en\">database</span></div>";
    } else {
        //input sanification
        $mail = $obj_connection->escape_string(trim(htmlentities($mail)));
        $username = $obj_connection->escape_string(trim(htmlentities($username)));
        $pwd = $obj_connection->escape_string(trim(htmlentities($pwd)));
        //check mail
        if (!check_email($mail)) {
            $error = $error . "<div class=\"errorMessage\">L'<span xml:lang=\"en\">e-mail</span> inserita non è valida.</div>";
        }
        //check email existence
        if ($obj_connection->queryDB("SELECT * FROM utenti WHERE mail=\"$mail\"")) {
            $error = $error . "<div class=\"errorMessage\">Esiste già un utente con questa <span xml:lang=\"en\">e-mail</span>.</div>";
        }
        //check username
        if (!check_username($username)) {
            $error = $error . "<div class=\"errorMessage\">Il nome utente deve essere lungo tra i 5 e i 30 caratteri e deve contenere solo lettere e numeri</div>";
        }
        //check username existance
        if ($obj_connection->queryDB("SELECT * FROM utenti WHERE username=\"$username\"")) {
            $error = $error . "<div class=\"errorMessage\">Esiste già un utente con questo <span xml:lang=\"en\">username</span>.</div>";
        }
        //check password equality
        if ($pwd != $pwd2) {
            $error = $error . "<div class=\"errorMessage\">Le <span xml:lang=\"en\">password</span> non coincidono.</div>";
        }
        //check password
        if (!check_pwd($pwd)) {
            $error = $error . "<div class=\"errorMessage\">La <span xml:lang=\"en\">password</span> deve essere lunga almeno 8 caratteri, contenere almeno una lettera maiuscola una minuscola e un numero.</div>";
        }
        //insert new user
        if ($error == "") {
            $query = "INSERT INTO utenti(ID,username, password, id_propic, mail, is_admin) VALUES (NULL, \"$username\",\"$pwd\", $propic , \"$mail\", 0)";
            $queryResult = $obj_connection->insertDB($query);

            //check dati inseriti
            if (!$queryResult) {
                $error = "<div class=\"errorMessage\">Errore nell'inserimento dei dati</div>";
            } else {
                $user=$obj_connection->queryDB("SELECT * FROM utenti WHERE username=\"$username\"");
                $_SESSION['logged'] = true;
                $_SESSION['ID'] = $user[0]['ID'];
                $_SESSION['permesso'] = 0;
                header('location: index.php');
                exit;
            }
        }
        $obj_connection->closeDBConnection();
    }
}

//profile picture search and rendering
$pictures = '';
$obj_connection = new DBAccess();
if (!$obj_connection->openDBConnection()) {
    $error = $error . "<div class=\"errorMessage\">Errore di connessione al <span xml:lang=\"en\">database</span></div>";
} else {
    $result = $obj_connection->queryDB("SELECT * FROM foto_profilo");
    for ($i = 0; $i < count($result); $i++) {
        $checked = "";
        $path = $result[$i]['path_foto'];
        $alt = $result[$i]['alt_text'];
        $id = $result[$i]['ID'];
        if ($id == 1) {
            $checked = "checked=\"checked\"";
        }
        $pictures = $pictures . "<li>
                            <input type=\"radio\" id=\"img$id\" name=\"propic\" value=\"$id\" $checked />
                            <label for=\"img$id\"><img src=\"$path\" alt=\"$alt\"/></label>
                        </li>";

    }
    $obj_connection->closeDBConnection();;
}
//php tag replacement
$page = str_replace("<EMAIL/>", $mail, $page);
$page = str_replace("<USERNAME/>", $username, $page);
$page = str_replace("<PWD/>", $pwd, $page);
$page = str_replace("<PWD_CONFERMA/>", $pwd2, $page);
$page = str_replace("<ERROR/>", $error, $page);
$page = str_replace("<FOTO_PROFILO/>", $pictures, $page);
echo $page;

?>