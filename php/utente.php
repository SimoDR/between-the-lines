<?php

$page=file_get_contents("../html/utente.html");
//TODO: start conection to db & retrieve info formatted in this layout
$username;
$email;
$proPic;
$altPic;
$userInfo="<img class=\"userPic\"
             src=\"../img/utenti/def1.png\"
             alt=\"la tua immagine del profilo\" \>
        <h1 class=\"userName\">
            admin1
        </h1>
        <p class=\"info-attuale\"> <span xml:lang=\"en\" lang=\"en\">E-mail</span> attuale:
            admin@gmail.com
        </p>";

str_replace("<INFO_UTENTE/>","$userInfo",$page);

?>


