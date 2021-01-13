<?php

require_once('sessione.php');
require_once('connessione.php');
require_once("setup_page.php");
//require_once('errore.php');
require_once('regex_page.php');
require_once('star.php');



$page = add("../html/dettagliLibro.html");

if (isset($_GET['ID']) && check_num($_GET['ID'])) {
    $ID_libro = $_GET['ID'];

    $DBconnection = new DBAccess();

    if ($DBconnection->openDBConnection()) {
        if ($queryResult = $DBconnection->queryDB(" 
                    SELECT l.ID as ID, l.titolo AS titolo, l.trama AS trama, g.nome AS genere, a.nome AS nomeAutore, a.cognome AS cognomeAutore, a.data_nascita AS nascitaAutore, a.data_morte AS morteAutore
                    FROM libri AS l,classificazioni AS c,autori AS a, generi AS g
                    WHERE ID=$ID_libro AND l.id_autore=a.ID AND l.ID=c.id_libro AND c.id_genere=g.ID"
                )) {
            // libro presente          
            if (count($queryResult) != 0) {

            

                $libro = $queryResult[0];

                // BREADCRUMBS
                
                $breadcrumb = 'Home &raquo; ' . $libro['titolo'];
                $page = str_replace('<PATH/>', $breadcrumb, $page);

                // COPERTINA 

                if ($queryCopertina = $DBconnection->queryDB("
                            SELECT copertine.path_img AS path_img, copertine.alt_text AS alt_text
                            FROM libri, copertine
                            WHERE libri.ID=copertine.id_libro AND libri.ID = $ID_libro")) {


                    if (count($queryCopertina) != 0) {
                        $copertina = $queryCopertina[0];
                        $page = str_replace('<LIBRO_COPERTINA/>', $copertina['path_img'], $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', $copertina['alt_text'], $page);
                    } else {
                        $page = str_replace('<LIBRO_COPERTINA/>', "Errore: immagine non trovata", $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', "Errore: alt non trovato", $page);
                    }
                } else {
                    echo 'Errore: impossibile eseguire query al database';
                }

                $page = str_replace('<LIBRO_TITOLO/>', $libro['titolo'], $page);
                $page = str_replace('<GENERE/>', $libro['genere'], $page);
                $page = str_replace('<RIASSUNTO/>', $libro['trama'], $page);



                // STELLE
                
                if ($queryNumRecensioni = $DBconnection->queryDB("
                            SELECT AVG(valutazione) AS avg_stars, COUNT(valutazione) AS num_recensioni
                            FROM recensioni
                            WHERE $ID_libro=id_libro ")) {
                    
                } else {
                    echo 'Errore: impossibile eseguire query al database';
                }
                $drawStars = printStars($queryNumRecensioni[0]['avg_stars']);
                $page = str_replace('<NUMERO_STELLE/>', round($queryNumRecensioni[0]['avg_stars'], 1), $page);
                $page = str_replace('<NUMERO_RECENSIONI/>', $queryNumRecensioni[0]['num_recensioni'], $page);
                $page = str_replace('<DISEGNO_STELLE/>', $drawStars, $page);


                // RECENSIONI

                $resultsInPage = 5;
                $totalRecensioni = $queryNumRecensioni[0]['num_recensioni'];  
                $totalPages = ceil($totalRecensioni / $resultsInPage);
                $pagesList = "";

                if ($queryRecensioni = $DBconnection->queryDB("
                            SELECT u.username as username, f.path_foto AS path_foto_profilo, f.alt_text AS alt_foto_profilo, r.dataora as rec_dataora, r.valutazione as rec_valutazione, r.testo as rec_testo
                            FROM recensioni AS r, utenti AS u, foto_profilo AS f
                            WHERE r.id_libro = $ID_libro AND u.id_propic = f.ID
                            ")) {
                    
                    // controllo se non sono fuori dai limiti
                    if (isset($_GET['pageN']) && (!check_number($_GET['pageN']) || $_GET['pageN'] < 1 || $_GET['pageN'] > $totalPages)) {
                        header('location: 404.php');
                        exit;
                    }
                    
                    //recupero lista recensioni
                    if (isset($_GET["pageN"])) {
                        $pageN = $_GET["pageN"];
                    } else {
                        $pageN = 1;
                    }

                    $startIndex = ($pageN - 1) * $resultsInPage; // indice della recensioni iniziale nella pagina
                    $endIndex = $startIndex + $totalRecensioni - ($pageN - 1) * $resultsInPage; // indice della recensione finale nella pagina

                    $listaRecensioni = "";
                    if ($startIndex == $endIndex) {
                        $listaRecensioni = "Nessuna recensione presente per questo libro";
                    } else {
                        for ($i = $startIndex; $i < $endIndex; $i++) {

                            $listaRecensioni = $listaRecensioni . '

                            <dl class="review_list">
                                <dt>
                                    <div class="user_details">
                                        <img src=' . $queryRecensioni[i]['path_foto_profilo'] . 'alt=' . $queryRecensioni[i]['alt_foto_profilo'] . ' />
                                        <span>' . $queryRecensioni[i]['username'] . '</span>
                                    </div>
                                    <span class="review_datetime">' . $queryRecensioni[i]['rec_dataora'] . '</span> 
                                </dt>
                                <dd>
                                    <div class="review_details">
                                        <p class="review_text">' . $queryRecensioni[i]['rec_testo'] . '</p>
                                        <span class="stelle_item">Stelle ' . $queryRecensioni[i]['rec_valutazione'] . '/5
                                            <span class="stelle_counter">' . printStar($queryRecensioni[i]['rec_valutazione']) . '</span>
                                        </span>

                                    </div>
                                </dd>
                            </dl>

                            ';
                        }
                    }

                    $page = str_replace('<LISTA_RECENSIONI/>', $listaRecensioni, $page); // perchè da problemi questa variabile?


                    $pagineRecensioni = '<div class="center">';


                    $address = $_SERVER['REQUEST_URI'];
                    if ($pageN > 1) {
                        $prec = $pageN - 1;
                        $pagineRecensioni = $pagineRecensioni . '<a href=' . $address . '&pagen=' . $prec . '>Precedente</a>';
                    }
                    $pagineRecensioni = $pagineRecensioni . '<span class="review_page_number">' . $pageN . '</span>';
                    if ($pageN < $totalPages) {
                        $prec = $pageN - 1;
                        $pagineRecensioni = $pagineRecensioni . '<a href=' . $address . '&pagen=' . $succ . '>Successivo</a>';
                    }
                    $pagineRecensioni = $pagineRecensioni . '</div>';

                    $page = str_replace('<PAGINE_RECENSIONI/>', $pagineRecensioni, $page);
                } else {
                    // errore db query recensioni
                    echo "Errore db query recensioni";
                }



                // FORM INSERIMENTO RECENSIONE
                
                $inserimentoForm = '';
                if (isset($_SESSION['logged']) && $_SESSION['logged']) { // se loggato
                    if ($_SESSION['permesso'] == 0) { // se visitatore
                        $inserimentoForm = '<form action="inserisciRecensione.php" method="post">
                                        <input type="hidden" name="ID_libro" value =' . $ID_libro . '/>
                                        <input type="submit" value="Inserisci recensione" class="btn"/>
                                        </form>';
                    } else { // se admin
                        $inserimentoForm = "<p>Spiacente, l'admin non può effettuare recensioni</p>";
                    }
                } else { // non loggato
                    $inserimentoForm = '<p><a href="login.php">Effettua il login per inserire una recensione</a></p>';
                }

                $page = str_replace('<FORM_INSERIMENTO_RECENSIONE/>', $inserimentoForm, $page);
                
            } 
            
            
            
            
            else { // libro non presente
                header('location: 404.php');
                exit;
            }
        } 
        
        
        else {
            echo "Errore query";
        }
    }
    
    
    
    else { // non si connette al db
        echo "Errore di connessione al database";
    }

} 



else { // se non GET[ID] not set
    header('location: 404.php');
    exit;
}

echo $page;
?>