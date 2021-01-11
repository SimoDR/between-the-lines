<?php
require_once("regex_checker.php");
$error="";
if(isset($_POST["addGenre"]))
{
    if(isset($_POST["genre"])){
        $genre=$_POST["genre"];
        if(check_nome($genre)){
            if(doesntexist){

            }
            else{
                $error=$error."<div class=\"msg_box error_box\">Il genere che vuoi inserire esiste già</div>";
            }
        }
        else{
            $error=$error."<div class=\"msg_box error_box\">Il nome deve avere lunghezza minima di 2 caratteri e non può presentare numeri al proprio interno.</div>";
        }
    }
    else {
        $error=$error."<div class=\"msg_box error_box\"> Inserire il nome del genere da inserire</div>";
    }
}


?>
