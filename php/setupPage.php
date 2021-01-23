<?php

// $htmlPath: pagina da fare il setup
function setup($htmlPath)
{

    $pageContent = file_get_contents($htmlPath);
    addHeader($pageContent);
    addMenu($pageContent);
    addFooter($pageContent);
    return $pageContent;
}

function addHeader(&$page)
{

    // header con tutto
    $header = file_get_contents("../HTML/template/header.html");

    //se loggato: mostra logout
    if (isset($_SESSION['logged']) && $_SESSION['logged']) {
        $obj_connection = new DBAccess();
        if ($obj_connection->openDBConnection()) {

            $id = $_SESSION["ID"];
            $queryResult = $obj_connection->queryDB('SELECT username FROM utenti WHERE ID=' . $id);
            $username = $queryResult[0]['username'];

            if (isset($_SESSION['permesso'])) {
                if ($_SESSION['permesso'] == 0) { //se utente

                    // metto area utente al posto del login
                    $header = str_replace('<a href="../php/login.php" class="hdrButton">LOGIN</a>',
                        '<a href="../php/utente.php" class="hdrButton">Benvenut*, ' . $username . '! Vai alla tua area utente </a>'
                        , $header);

                } else if ($_SESSION['permesso'] == 1) { //se admin
                    $header = str_replace('<a href="../php/login.php" class="hdrButton">LOGIN</a>',
                        '<a href="../php/utente.php" class="hdrButton">Benvenut*, admin! Vai al pannello di amministrazione </a>'
                        , $header);
                }

                //tolgo registrazione
                $header = str_replace('<a href="../php/registrazione.php" class="hdrButton">REGISTRAZIONE</a>',
                    "", $header);
            } else {
                //errore connessione db
                echo 'errore connessione db'; //TODO
            }
        } else {
            //not set
        }
    } // se non loggato
    else {

        // rimuovi logout
        $header = str_replace('<a href="../php/logout.php" class="hdrButton">LOGOUT</a>',
            "", $header);

        // se si è nella pagina di login
        if (preg_match("/^login\.php\?id_libro=\d+$|^login\.php$/", basename($_SERVER["REQUEST_URI"]))) {
            //rimuovi login
            $header = str_replace('<a href="../php/login.php" class="hdrButton">LOGIN</a>',"", $header);
        }
        // se si è nella pagina di registrazione
        if (basename($_SERVER["REQUEST_URI"]) == "registrazione.php") {
            //rimuovi registrazione
            $header = str_replace('<a href="../php/registrazione.php" class="hdrButton">REGISTRAZIONE</a>',
                "", $header);
        }
    }


    $page = str_replace("<HEADER/>", $header, $page);
}

function addMenu(&$page)
{
    // menù con tutto
    $menu = file_get_contents("../HTML/template/menu.html");
    
    // rimozione dei link circolari
    if(basename($_SERVER["REQUEST_URI"]) == "index.php") {
        $menu = str_replace('<a href="index.php">Home</a>','Home',$menu);
    }
    if(basename($_SERVER["REQUEST_URI"]) == "chisiamo.php") {
        $menu = str_replace('<a href="chisiamo.php">Chi Siamo</a>','Chi Siamo',$menu);
    } 
    if(basename($_SERVER["REQUEST_URI"]) == "contatti.php") {
        $menu = str_replace('<a href="contatti.php">Contattaci</a>','Contattaci',$menu);
    } 

    $page = str_replace("<MENU/>", $menu, $page);
}

function addFooter(&$page)
{

    // footer con tutto
    $footer = file_get_contents("../HTML/template/footer.html");

    $page = str_replace("<FOOTER/>", $footer, $page);
}


    
