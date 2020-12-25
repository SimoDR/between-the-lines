<?php

require_once("sessione.php");
require_once('connessione.php');
require_once("regex_checker.php");


if($_SESSION['logged']==true){
    header('location:index.php');
    exit();
}

/*Aggiunta header,menu e footer*/
$page= file_get_contents("../html/registrazione.html");

$mail='';
$pwd='';
$pwd2='';
$id_foto=NULL;
$no_error=true;
$error="";

if(isset($_POST['registrati'])){
    if(isset($_POST['email'])){
        $mail=$_POST['email'];
    }
    if(isset($_POST['password'])){
        $pwd=$_POST['password'];
    }
    if(isset($_POST['repeatpassword'])){
        $pwd2=$_POST['repeatpassword'];
    }

    //connessione db
    $obj_connection = new DBConnection();
    if(!$obj_connection->create_connection()){
        $error=$error."<div class=\"msg_box error_box\">Errore di connessione al database</div>";
        $no_error=false;
    }
    //controllo input
    if(!check_email($mail)){
        $error=$error."<div class=\"msg_box error_box\">'La mail inserita non è valida.</div>";
        $no_error=false;
    }
    if($obj_connection->queryDB("SELECT * FROM utente WHERE Mail='".$mail."'")){
        $error=$error."<div class=\"msg_box error_box\">Questa mail è già in uso.</div>";
        $no_error=false;
    }
    if($pwd!=$pwd2){
        $error=$error."<div class=\"msg_box error_box\">Password e Ripeti Password non coincidono.</div>";
        $no_error=false;
    }
    if(!check_pwd($pwd)){
        $error=$error."<div class=\"msg_box error_box\">La password deve essere lunga almeno 8 caratteri, contenere almeno una lettera maiuscola una minuscola e un numero.</div>";
        $no_error=false;
    }

    if($no_error){
        $mail=$obj_connection->escape_str(trim(htmlentities($mail)));
        $nome=$obj_connection->escape_str(trim(htmlentities($nome)));
        $hashed_pwd=hash("sha256",$obj_connection->escape_str(trim($pwd)));

        $obj_connection->connessione->query("INSERT INTO `utente` (`ID`, `PWD`, `Mail`, `Nome`, `Cognome`, `Data_Nascita`, `ID_Foto`, `Ragione_Sociale`, `P_IVA`, `Permessi`, `Sesso`) VALUES (NULL,\"$hashed_pwd\", \"$mail\", \"$nome\", \"$cognome\", \"$datan\", \"$id_foto\", NULL, NULL, \"$permessi\", \"$sesso\")");
        }

        //check dati inseriti
        if(!$obj_connection->queryDB("SELECT * FROM utente WHERE Mail='".$mail."'")){
            $error="<div class=\"msg_box error_box\">Errore nell'inserimento dei dati</div>";
        }else{
            $obj_connection->close_connection();
            header('location: login.php');
            exit;
        }
        $obj_connection->close_connection();
    }

}
if($tipo==1){
    $page=str_replace('checked="%REC_CHECKED%"',"",$page);
    $page=str_replace('checked="%RIST_CHECKED%"',"checked=\"checked\"",$page);
}
else{
    $page=str_replace('checked="%REC_CHECKED%"',"checked=\"checked\"",$page);
    $page=str_replace('checked="%RIST_CHECKED%"',"",$page);
}
$page=str_replace("%MAIL_VALUE%",$mail,$page);
$page=str_replace("%PWD1_VALUE%",$pwd,$page);
$page=str_replace("%PWD2_VALUE%",$pwd2,$page);
$page=str_replace("%ERROR%",$error,$page);
echo $page;

?>