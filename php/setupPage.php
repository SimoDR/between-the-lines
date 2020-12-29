<?php

// $htmlPath: pagina da fare il setup
function add($htmlPath) {

    $pageContent = file_get_contents($htmlPath);

    addMenu($pageContent);

    addHeader($pageContent);

    return $pageContent;
}

function addHeader(&$page) {
    
    // header con tutto
    $header = file_get_contents("../template/header.html");

    //se loggato: mostra logout
    if (isset($_SESSION['logged']) && $_SESSION['logged']) {
        //tolgo login
        $header = str_replace('<a href="../php/login.php" class="button">LOGIN</a>',
                "", $header);
        //tolgo registrazione
        $header = str_replace('<a href="../php/registrazione.php" class="button">REGISTRAZIONE</a>',
                "", $header);
    }
    // altrimenti
    else {

        // rimuovi logout
        $header = str_replace('<a href="../php/registrazione.php" class="button">LOGOUT</a> ',
                "", $header);

        // se si è nella pagina di login
        if (basename($_SERVER["REQUEST_URI"]) == "login.php") {
            //rimuovi login
            $header = str_replace('<a href="../php/login.php" class="button">LOGIN</a>',
                    "", $header);
        }
        // se si è nella pagina di registrazione
        if (basename($_SERVER["REQUEST_URI"]) == "registrazione.php") {
            //rimuovi registrazione
            $header = str_replace('<a href="../php/registrazione.php" class="button">REGISTRAZIONE</a>',
                    "", $header);
        }
    }

    $page = str_replace("<HEADER/>", $header, $page);
}

function addMenu(&$page) {

    // menù con tutto     
    $menu = file_get_contents("../template/menu.html");

    // se loggato
    if (isset($_SESSION['logged']) && $_SESSION['logged']) {

        // se è admin
        if (isset($_SESSION['permesso']) && ($_SESSION['permesso'] == 'is_admin')) {
            // rimuovi area personale
            $menu = str_replace('<li><a href="areapersonale.php">Area personale</a></li>',
                    "", $header);
        }
        // se è utente normale
        else {
            // rimuovi pannello admin
            $menu = str_replace('<li><a href="pannelloadmin.php">Pannello di amministrazione</a></li>',
                    "", $header);
        }
    }
    // se non loggato
    else {
        // rimuovi area personale
        $menu = str_replace('<li><a href="areapersonale.php">Area personale</a></li>',
                "", $header);
        // rimuovi pannello admin
        $menu = str_replace('<li><a href="pannelloadmin.php">Pannello di amministrazione</a></li>',
                "", $header);
    }
    
    $page = str_replace("<MENU/>", $menu, $page);
}
