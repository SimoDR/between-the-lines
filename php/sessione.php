<?php
session_start();

if(!isset($_SESSION['logged'])){
    $_SESSION['logged']=false;
}
if(!isset($_SESSION['ID'])){
    $_SESSION['ID']='';
}
if(!isset($_SESSION['permesso'])){
    $_SESSION['permesso']='guest';
}

?>