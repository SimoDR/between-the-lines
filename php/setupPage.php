<?php

// $htmlPath: pagina da fare il setup
function add($htmlPath) {
    
    $pageContent = file_get_contents($htmlPath);
    disableMagicQuotes();
    addHeader($pageContent);

    return $pageContent;
}

function disableMagicQuotes() {
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
}

function addHeader(&$page) {
    
    // header con tutto
    $header = file_get_contents("../HTML/template/header.html");

    //se loggato: mostra logout
    if (isset($_SESSION['logged']) && $_SESSION['logged']) {
        $obj_connection = new DBAccess();
        if($obj_connection->openDBConnection()) {
                
        $id = $_SESSION["ID"];
        $queryResult = $obj_connection->queryDB("SELECT * FROM utenti WHERE ID=\"$id\"");
        $username = $queryResult[0]["username"];

        if(isset($_SESSION['permesso'])) {
            if($_SESSION['permesso'] == 0) { //se utente

                // metto area utente al posto del login
                $header = str_replace('<a href="../php/login.php" class="button">LOGIN</a>',
                        '<a href="../php/utente.php" class="button">Benvenuto,' . $username . '! Ecco la tua area utente </a>'
                        , $header);

            }
            else if($_SESSION['permesso'] == 1) { //se admin
                $header = str_replace('<a href="../php/login.php" class="button">LOGIN</a>',
                        '<a href="../php/utente.php" class="button">Benvenuto, admin! Ecco il pannello di amministrazione </a>'
                        , $header);
            }

            //tolgo registrazione
            $header = str_replace('<a href="../php/registrazione.php" class="button">REGISTRAZIONE</a>',
                    "", $header);
            }
            else {
                //errore connessione db
                echo 'errore connessione db'; //TODO
            }
        }
        else {
            //not set
        }
    }
    // se non loggato
    else {

        // rimuovi logout
        $header = str_replace('<a href="../php/logout.php" class="button">LOGOUT</a> ',
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

    
