<?php

require_once('sessione.php');
require_once('connessione.php');
require_once("setup_page.php");
//require_once('errore.php');
require_once('regex_page.php');
require_once('star.php');



$page = add("../html/dettagliLibro.html");

if (isset($_GET['ID']) && check_num($_GET['ID'])) {
    $ID_libro = $_GET['id'];

    $DBconnection = new DBAccess();

    if ($DBconnection->openDBConnection()) {
        if ($queryResult = $DBconnection->queryDB(" 
                    SELECT l.ID as ID, l.titolo AS titolo, l.trama AS trama, g.nome AS genere, a.nome AS nomeAutore, a.cognome AS cognomeAutore, a.data_nascita AS nascitaAutore, a.data_morte AS morteAutore
                    FROM libri AS l,classificazioni AS c,autori AS a, generi AS g
W                   HERE ID=$ID_libro AND l.id_autore=a.ID AND l.ID=c.id_libro AND c.id_genere=g.ID"
                )) {
            // libro presente          
            if (count($queryResult) != 0) {

                //eliminazione recensione
                $msg = '';
                if (isset($_POST['eliminaRec'])) {
                    if ($DBconnection->connessione->query("DELETE FROM recensione WHERE ID=" . $_POST['ID_Recensione'])) {
                        $msg = '<p class="msg_box success_box">Recensione eliminata</p>';
                    } else {
                        $msg = '<p class="msg_box error_box">Eliminazione fallita</p>';
                    }
                }
                $page = str_replace('%MESSAGGIO%', $msg, $page);

                $libro = $queryResult[0];

                //Breadcrumb
                $breadcrumb = 'Home &raquo; ' . $libro['titolo'];
                $page = str_replace('%PATH%', $breadcrumb, $page);

                // il path e descrizione vanno cercate nel db

                if ($queryCopertina = $DBconnection->queryDB("
                            SELECT copertine.path_img AS path_img, copertine.alt_text AS alt_text
                            FROM libri, copertine
                            WHERE libri.ID=copertine.id_libro")) {


                    if (count($queryCopertina) != 0) {
                        $copertina = $queryCopertina[0];
                        $page = str_replace('<LIBRO_COPERTINA/>', $copertina['path_img'], $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', $copertina['alt_text'], $page);
                    } else {
                        $page = str_replace('<LIBRO_COPERTINA/>', "Errore: immagine non trovata", $page);
                        $page = str_replace('<LIBRO_COPERTINA_ALT/>', "Errore: alt non trovato", $page);
                    }
                } else {
                    echo 'Errore: impossibile connettersi al database';
                }


                $page = str_replace('<LIBRO_TITOLO/>', $libro['titolo'], $page);
                $page = str_replace('<GENERE/>', $libro['genere'], $page);
                $page = str_replace('<RIASSUNTO/>', $libro['trama'], $page);



                //stelle
                if ($queryNumRecensioni = $DBconnection->queryDB("
                            SELECT AVG(valutazione) AS avg_stars, COUNT(valutazione) AS num_recensioni
                            FROM recensioni
                            WHERE $ID_libro=id_libro ")) {

                    if (count($array_star_avg) != 0) {
                        $drawStars = printStars($queryNumRecensioni['avg_stars']);
                    }
                }
                $page = str_replace('<NUMERO_STELLE/>', round($queryNumRecensioni['avg_stars'], 1), $page);
                $page = str_replace('<NUMERO_RECENSIONI/>', $queryNumRecensioni['num_recensioni'], $page);
                $page = str_replace('<DISEGNO_STELLE/>', $drawStars, $page);


                // recensioni

                $resultsInPage = 5;
                $totalRecensioni = $queryNumRecensioni['num_recensioni'];
                $totalPages = 1;
                $pagesList = "";

                if ($queryRecensioni = $DBconnection->queryDB("
                            SELECT u.username as username, f.path_foto AS path_foto_profilo, f.alt_text AS alt_foto_profilo, r.dataora as rec_dataora, r.valutazione as rec_valutazione, r.testo as rec_testo
                            FROM recensioni AS r, utenti AS u, foto_profilo AS f
                            WHERE r.id_libro = $ID_libro AND u.id_propic = f.ID
                            ")) {
                    
                    $totalPages = ceil($totalRecensioni / $resultsInPage);

                    if (isset($_GET['pagen']) && (!check_number($_GET['pagen']) || $_GET['pagen'] < 1 || $_GET['pagen'] > $totalPages)) {
                        header('location: 404.php');
                        exit;
                    }    
                    
                     //recupero lista recensioni
                    if (isset($_GET["pagen"])) {
                        $pagen = $_GET["pagen"];
                    } 
                    else {
                        $pagen = 1;
                    };   
                        
                    $startIndex = ($pagen - 1) * $resultsInPage;                  
                    $endIndex = $startIndex + $totalRecensioni - ($pagen - 1) * $resultsInPage;
                                          
                    $listaRecensioni = "";
                    if($startIndex == $endIndex) {
                        $listaRecensioni = "Nessuna recensione presente per questo libro";
                    }
                    else {
                    for($i = $startIndex; $i<$endIndex; $i++)
                    {
                        
                        
                        $listaRecensioni = $listaRecensioni . '
                        
                        <dl class="review_list">
                            <dt>
                                <div class="user_details">
                                    <img src=' . $queryRecensioni[i]['path_foto_profilo'] . 'alt=' . $queryRecensioni[i]['alt_foto_profilo'] . ' />
                                    <span>' . $queryRecensioni[i]['username'] .'</span>
                                </div>
                                <span class="review_datetime">' . $queryRecensioni[i]['rec_dataora'] . '</span> 
                            </dt>
                            <dd>
                                <div class="review_details">
                                    <p class="review_text">' . $queryRecensioni[i]['rec_testo'] . '</p>
                                    <span class="stelle_item">Stelle ' .  $queryRecensioni[i]['rec_valutazione'] . '/5
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
                    if($pagen > 1)
                    {
                        $prec = $pagen - 1;
                        $pagineRecensioni = $pagineRecensioni . '<a href=' . $address . '&pagen=' . $prec .'>Precedente</a>';
                    }
                    $pagineRecensioni = $pagineRecensioni . '<span class="review_page_number">' . $pagen . '</span>';
                    if($pagen < $totalPages)
                    {
                        $prec = $pagen - 1;
                        $pagineRecensioni = $pagineRecensioni . '<a href=' . $address . '&pagen=' . $succ .'>Successivo</a>';
                    }
                    $pagineRecensioni = $pagineRecensioni . '</div>';
                    
                    $page = str_replace('<PAGINE_RECENSIONI/>', $pagineRecensioni, $page);

                    
                 
                        
                    
                } else {
                    // errore db query recensioni
                }


                
                
                
                //form inserimento recensione
                $ins_rec_form = '';
                if ($_SESSION['permesso'] == 'Utente') {
                    $ins_rec_form = '<form action="ins_recensione.php" method="post">
                                    <input type="hidden" name="id_ristorante" value="%ID_RIST%"/>
                                    <input type="submit" value="Inserisci recensione" class="btn"/>
                                    </form>';
                } else {
                    if ($_SESSION['permesso'] == 'Visitatore')
                        $ins_rec_form = '<p><a href="login.php">Effettua il login per inserire una recensione</a></p>';
                }
                $page = str_replace('%FORM_INSERIMENTO_RECENSIONE%', $ins_rec_form, $page);

                //form inserimento foto
                $ins_foto_form = '';
                if ($_SESSION['permesso'] == 'Ristoratore' && $_SESSION['ID'] == $libro['ID_Proprietario']) {
                    $ins_foto_form = '<form action="ins_new_photo.php" method="post">
                                            <fieldset>
                                            <input type="hidden" name="id_ristorante" value="%ID_RIST%"/>
                                            <input type="submit" value="Inserisci nuova foto" class="btn" id="new_photo_button" />
                                            </fieldset>
                                        </form>';
                }
                $page = str_replace('%FORM_INSERIMENTO_FOTO%', $ins_foto_form, $page);

                $page = str_replace('%ID_RIST%', $id_ristorante, $page);
            } else {
                //ristorante non presente
                header('location: 404.php');
                exit;
            }
        } else {
            //errore query DB
            $page = (new addItems)->add("../html/base.html");
            $page = str_replace('%PATH%', 'Ricerca', $page);
            $page = str_replace('%MESSAGGIO%', (new errore('query'))->printHTMLerror(), $page);
        }
    } else {
        //connessione fallita
        $page = (new addItems)->add("../html/base.html");
        $page = str_replace('%PATH%', 'Ricerca', $page);
        $page = str_replace('%MESSAGGIO%', (new errore('DBConnection'))->printHTMLerror(), $page);
    }
    $$DBconnection->close_connection();
} else {
    header('location: 404.php');
    exit;
}

echo $page;
?>