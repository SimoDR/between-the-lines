<?php

require_once('sessione.php');
require_once('DBConnection.php');
require_once("setupPage.php");
//require_once('errore.php');
require_once('regex_checker.php');
require_once("stelle.php");




$page = setup("../HTML/dettagliLibro.html");

$erroriPagina = '';
if (isset($_GET['id_libro']) && check_num($_GET['id_libro'])) {
    
    
    
    $ID_libro = $_GET['id_libro'];

    $DBconnection = new DBAccess();
    
    

    if ($DBconnection->openDBConnection()) {
        if (!is_null($queryResult = $DBconnection->queryDB(" 
                    SELECT l.ID as ID, l.titolo AS titolo, l.trama AS trama, g.nome AS genere, a.nome AS nomeAutore, a.cognome AS cognomeAutore, anno_nascita AS nascitaAutore, anno_morte AS morteAutore
                    FROM libri AS l,autori AS a, generi AS g
                    WHERE l.ID=$ID_libro AND l.id_autore=a.ID AND l.id_genere=g.ID "
                ))) {
            // libro presente 
            
            if (!empty($queryResult)) {

                $libro = $queryResult[0];

                // BREADCRUMBS
                
                $breadcrumb = $libro['titolo'];
                $page = str_replace('<PATH/>', $breadcrumb, $page);

                // COPERTINA 

                if (!is_null($queryCopertina = $DBconnection->queryDB("
                            SELECT copertine.path_img AS path_img, copertine.alt_text AS alt_img
                            FROM libri, copertine
                            WHERE libri.ID=copertine.id_libro AND libri.ID = $ID_libro"))) {

                    
                    if (!empty($queryCopertina)) {
                        $copertina = $queryCopertina[0];
                        $page = str_replace('<LIBRO_COPERTINA/>', $copertina['path_img'], $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', $copertina['alt_img'], $page);
                    } else {
                        $page = str_replace('<LIBRO_COPERTINA/>', "Errore: immagine non trovata", $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', "Errore: alt non trovato", $page);
                    }
                } else {
                    $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> di raccolta dei dati del libro</div>";
                }

                $page = str_replace('<LIBRO_TITOLO/>', $libro['titolo'], $page);
                $page = str_replace('<GENERE/>', $libro['genere'], $page);
                $page = str_replace('<NOME_AUTORE/>', $libro['nomeAutore'], $page);
                $page = str_replace('<COGNOME_AUTORE/>', $libro['cognomeAutore'], $page);               
                $page = str_replace('<AUTORE_NASCITA/>', $libro['nascitaAutore'], $page);
                $page = str_replace('<AUTORE_MORTE/>', $libro['morteAutore'] == null ? '' : $libro['morteAutore'], $page);
                $page = str_replace('<RIASSUNTO/>', $libro['trama'], $page);

                // STELLE
                                
                if (!is_null($queryNumRecensioni = $DBconnection->queryDB("
                            SELECT AVG(valutazione) AS avg_stars, COUNT(valutazione) AS num_recensioni
                            FROM recensioni
                            WHERE $ID_libro=id_libro "))) {
                    
                } else {
                    $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> sull'apprezzamento del libro</div>";
                }
                $numeroStelle = 'Ancora nessuna stella per questo libro!';
                $drawStars = '';
                if($queryNumRecensioni[0]['avg_stars'] != null) {
                    $numeroStelle = round($queryNumRecensioni[0]['avg_stars'],1) . '/5' ;
                    $drawStars = printStars($queryNumRecensioni[0]['avg_stars']);
                }
                
                
                                
                $page = str_replace('<NUMERO_STELLE/>', $numeroStelle  , $page);
                $page = str_replace('<NUMERO_RECENSIONI/>', $queryNumRecensioni[0]['num_recensioni'], $page);
                $page = str_replace('<DISEGNO_STELLE/>', $drawStars, $page);


                // RECENSIONI

                $resultsInPage = 3;
                $totalRecensioni = $queryNumRecensioni[0]['num_recensioni'];
                $totalPages = ceil($totalRecensioni / $resultsInPage);
                $pagesList = "";
                

                if (!is_null($queryRecensioni = $DBconnection->queryDB("
                            SELECT r.ID AS id_recensione, u.ID AS ID_utente, u.username AS username, f.path_foto AS path_foto_profilo, f.alt_text AS alt_foto_profilo, r.dataora as rec_dataora, r.valutazione as rec_valutazione, r.testo as rec_testo
                            FROM recensioni AS r, utenti AS u, foto_profilo AS f
                            WHERE r.id_libro = $ID_libro AND u.id_propic = f.ID AND r.id_utente = u.ID
                            ORDER BY rec_dataora DESC
                            "))) {
                    
                    
                    // controllo se non sono fuori dai limiti
                    if (isset($_GET['npage']) && (!check_number($_GET['npage']) || $_GET['npage'] < 1 || $_GET['npage'] > $totalPages)) {
                        header('location: 400.php'); //bad request
                        exit;
                    }
                    
                    
                    //recupero lista recensioni
                    if (isset($_GET["npage"])) {
                        $npage = $_GET["npage"];
                    } else {
                        $npage = 1;
                    }
                    
                    $listaRecensioni = "";
                    if(empty($queryRecensioni))
                    {
                        if($_SESSION['permesso'] == 0) {
                            $listaRecensioni .=  ' 
                            <div>
                                <span class ="no_item">Ancora nessuna recensione: sii tu il primo a recensire questo libro!</span>
                            </div>';     
                        }
                    } else {
                    
                    $startIndex = ($npage - 1) * $resultsInPage; // indice della recensioni iniziale nella pagina
                    $endIndex = 0; // indice della recensione finale nella pagina
                    if($npage == $totalPages) {
                        $endIndex = $totalRecensioni;                        
                    }
                    else {
                        $endIndex = $npage * $resultsInPage;
                    }

                    // STAMPA DELLE RECENSIONI
                    
                    if ($startIndex == $endIndex) {
                        $listaRecensioni = "Nessuna recensione presente per questo libro";
                    } else {
                        $listaRecensioni .= '<ol class="reviewList">';
                        for ($i = $startIndex; $i < $endIndex; $i++) {

                            $drawStarsUtente = printStars($queryRecensioni[$i]['rec_valutazione']);
                            
                            // eliminazione recensione 
                            
                            $eliminazioneRecensione = '';
                            if($_SESSION['ID'] == $queryRecensioni[$i]['ID_utente'] || $_SESSION['permesso'] == 1) {
                                $eliminazioneRecensione = '<form action="dettagliLibro.php?id_libro=' . $ID_libro .'" method="post">
                                                        <div>
                                                            <input type="hidden" name="ID_recensione" value="' . $queryRecensioni[$i]['id_recensione'] .'"/>
                                                            <input type="submit" value="Elimina recensione" class="button"/>
                                                        </div>
                                                        </form>';
                            }
                            
                            //stampa recensione
                            
                            $listaRecensioni = $listaRecensioni . '
                            <li class = "review">
                                    <div class="reviewDetails">
                                        ' . $eliminazioneRecensione . '
                                        <img src="' . $queryRecensioni[$i]['path_foto_profilo'] . '" alt="' . $queryRecensioni[$i]['alt_foto_profilo'] . '" />
                                        <span class="username">' . $queryRecensioni[$i]['username'] . '</span>
                                        <span class="reviewDatetime">' . substr($queryRecensioni[$i]['rec_dataora'],0,16) . '</span> 
                                    </div>
                                    <div class="reviewContent">
                                        <p class="reviewText">' . $queryRecensioni[$i]['rec_testo'] . '</p>
                                        <p class="stelle">Stelle: ' . $queryRecensioni[$i]['rec_valutazione'] . ' ' . $drawStarsUtente .  '</p>
                                    </div>
                            </li>
                            ';
                        }
                        $listaRecensioni .= '</ol>';
                    }
                    }
                    $page = str_replace('<LISTA_RECENSIONI/>', $listaRecensioni, $page); // perchè da problemi questa variabile?


                    $pagineRecensioni = '<div class="pageNumbers">';

                    // pulizia dell'imput
                    $address = $_SERVER['REQUEST_URI'];
                    $address = preg_replace("/\&npage=\d/","",$address);
                    
                    // pagine precedenti e successive 
                    if ($npage > 1) {
                        $prec = $npage - 1;
                        $address .= '&amp;npage=' . $prec . '#recensioni';
                        $pagineRecensioni = $pagineRecensioni . "<div class=\"notCurrentPage\"><a href= \"$address\">Precedente</a></div>";
                    }
                    $pagineRecensioni = $pagineRecensioni . '<div class="currentPage"><span>' . $npage . '</span></div>';
                    if ($npage < $totalPages) {
                        $succ = $npage + 1;
                        $address .= '&amp;npage=' . $succ . '#recensioni';
                        $pagineRecensioni = $pagineRecensioni . "<div class=\"notCurrentPage\"><a href= \"$address\">Successivo</a></div>";
                    }
                    $pagineRecensioni = $pagineRecensioni . '</div>';

                    $page = str_replace('<PAGINE_RECENSIONI/>', $pagineRecensioni, $page);
                } else {
                    // errore db query recensioni
                    $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> di raccolta delle recensioni</div>";
                }



                // FORM INSERIMENTO RECENSIONE
                
                $inserimentoForm = '';
                if (isset($_SESSION['logged']) && $_SESSION['logged']) { // se loggato
                    if ($_SESSION['permesso'] == 0) { // se non admin
                        $inserimentoForm = '<div id="insertReviewButton"><form action="inserisciRecensione.php" method="post">
                                            <div>
                                                <input type="hidden" name="ID_libro" value="' . $ID_libro .'"/>
                                                <input id="insertReviewButton" type="submit" value="Inserisci recensione" class="button"/>
                                            </div>
                                            </form></div>';
                    } else { // se admin
                        $inserimentoForm = "<p id=\"insertReviewButton\">Spiacente, l'<span xml:lang=\"en\" lang=\"en\">admin</span> non può effettuare recensioni</p>";
                    }
                } else { // non loggato
                    
                    $inserimentoForm = '<form action="login.php" method="get">
                                        <div>
                                            <input type="hidden" name="id_libro" value ="' . $ID_libro . '"/>
                                            <input id="insertReviewButton" type="submit" value="Accedi per inserire una recensione" class="button"/>
                                        </div>
                                        </form>';
                }

                $page = str_replace('<FORM_INSERIMENTO_RECENSIONE/>', $inserimentoForm, $page);
                
                
                // ELIMINAZIONE LIBRO
                
                $eliminazioneLibro = '';
                if ($_SESSION['permesso'] == 1) {
                    $eliminazioneLibro .= ' <form action="dettagliLibro.php?id_libro=' . $ID_libro .'" method="post">
                                            <div>
                                                <input type="hidden" name="ID_libro_eliminazione" value="' . $ID_libro .'"/>
                                                <input type="submit" value="Elimina libro" class="button"/>
                                            </div>
                                            </form>';
                }    
                $page = str_replace('<ELIMINA_LIBRO/>', $eliminazioneLibro, $page);
                
                
                // SE LIBRO ELIMINATO
                
                if(isset($_POST['ID_libro_eliminazione'])) {
                    
                    $libroDaEliminare = $_POST['ID_libro_eliminazione'];
                    if(!is_null($queryResult = $DBconnection->insertDB(" 
                        DELETE 
                        FROM libri
                        WHERE ID = $libroDaEliminare "
                        ))) {
                    
                            header('location: index.php'); //TODO: messaggio di successo                            
                            exit;                      
                        }
                        else {
                            $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> di eliminazione del libro</div>";
                        }  
                    
                }
                
                // SE RECENSIONE ELIMINATA
                
                if(isset($_POST['ID_recensione'])) {
                    $recensioneDaEliminare = $_POST['ID_recensione'];
                    if(!is_null($queryResult = $DBconnection->insertDB(" 
                        DELETE 
                        FROM recensioni
                        WHERE ID = $recensioneDaEliminare "
                        ))) {
                            header('location: dettagliLibro.php?id_libro='. $ID_libro); //TODO: messaggio di successo
                            exit;                      
                        }
                        else {
                            $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> di eliminazione della recensione</div>";
                        }
                }
                
                

            }          
            else { // libro non presente

                header('location: 404.php');
                exit;
            }
        } 
        
        
        else {
            $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> sul libro</div>";
        }
        
        $DBconnection->closeDBConnection();
    }

    else { // non si connette al db
        $erroriPagina .= "<div class=\"errorMessage\"> Errore durante la connessione al <span xml:lang=\"en\" lang=\"en\">database</span></div>";
    }

} 



else { // se non GET[ID] not set
    header('location: 400.php');
    exit;

}

$page = str_replace('<ERRORI_PAGINA/>', $erroriPagina, $page);


echo $page;
?>