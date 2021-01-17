<?php 

require_once('sessione.php');
require_once('DBConnection.php');
require_once("setupPage.php");

    
    $page=add("../html/inserisciRecensione.html");   

    if($_SESSION['logged']==true){
        if($_SESSION['permesso']==0){
                          
            $DBconnection = new DBAccess();
            if($DBconnection->openDBConnection()) {
                
                // RECUPERO DATI SUL LIBRO
                
                $libro = "";
                $ID_libro = $_POST['ID_libro'];
                $nomeLibro = "";
                $nomeAutore = "";
                $erroreTesto = "";
                $messaggioSuccesso = "";
                $ID_utente = $_SESSION['ID'];
                
                if (!is_null($queryResult = $DBconnection->queryDB(" 
                    SELECT l.titolo AS titolo, a.nome AS nomeAutore, a.cognome AS cognomeAutore, c.path_img AS path_img, c.alt_text AS alt_img
                    FROM libri AS l,autori AS a, copertine AS c
                    WHERE l.ID=$ID_libro AND l.id_autore=a.ID AND l.ID=c.id_libro "
                    ))) {
                    
                    $libro = $queryResult[0];
                    $nomeLibro = $libro['titolo'];
                    $nomeAutore = $libro['nomeAutore'];
                    $cognomeAutore = $libro['cognomeAutore'];
                    $path_img = $libro['path_img'];
                    $alt_img = $libro['alt_img'];
                    
                    
                    //BREADCRUMBS
                    
                    $breadcrumb = 'Home &raquo; ' . $libro['titolo'] . '&raquo; Inserisci recensione' ;
                   
                    // RECUPERO DATI FORM
                                            

                    $contenuto = '';
                    $stelle = 1;
                    $data = "";
                    if(isset($_POST['review_content'])){
                        
                        $contenuto=$_POST['review_content'];
                        
                        if(isset($_POST['n_stars'])){
                            $stelle=$_POST['n_stars'];
                        }
                        
                        date_default_timezone_set("Europe/Rome");
                        $data=date("Y-m-d H:i");
                        

                        // CONTROLLO DATI RECENSIONE
                        if($contenuto != '' && strlen($contenuto) > 50 && strlen($contenuto) < 500) { //TODO: necessari controlli per sanificare?
                            
                            if (!is_null($queryResult = $DBconnection->insertDB( " 
                                    INSERT INTO recensioni(ID, dataora, valutazione, id_libro, id_utente, testo)
                                    VALUES (NULL, \"$data\", $stelle, $ID_libro, $ID_utente, \"$contenuto\")                
                                    "))) { //TODO: check ID autoincrement
                                header('location: dettagliLibro.php?id_libro='. $ID_libro); //TODO: messaggio di successo
                                exit;                      
                            
                            }
                                                       
                        }
                        else {
                            $erroreTesto .= '<p>Errore durante l\'inserimento della recensione:<p>';
                            
                            if($contenuto == '') {
                                $erroreTesto .= '<p>Attenzione: campo recensione obbligatorio</p>';
                            }
                            else if(strlen($contenuto) < 50) {
                                $erroreTesto .= '<p>Attenzione: la recensione dev\'essere composta da almeno 50 caratteri</p>';
                            }
                            else if(strlen($contenuto) > 500) {
                                $erroreTesto .= '<p>Attenzione: la recensione dev\'essere composta da al massimo 500 caratteri</p>';
                            }
                            
                        }
                        
                         
                                        
                        
                    }
                    else {
                        
                        $contenuto = '';
                    }
                    $page=str_replace('<LIBRO_COPERTINA/>',$path_img,$page);
                    $page=str_replace('<LIBRO_COPERTINA_ALT/>',$alt_img,$page);
                    $page=str_replace('<ID_LIBRO/>',$ID_libro,$page);
                    $page=str_replace('<NOME_LIBRO/>',$nomeLibro,$page);
                    $page=str_replace('<AUTORE_NOME/>',$nomeAutore,$page);
                    $page=str_replace('<AUTORE_COGNOME/>',$cognomeAutore,$page);
                    
                    $page=str_replace('<ERRORE_CONTENUTO/>',$erroreTesto,$page);

                    $page=str_replace('<CONTENUTO_RECENSIONE/>',$contenuto,$page);  
                    
                    
                    $selezioneStelle = '';
                    for($i = 1; $i<6; $i++)
                    {
                        $singolarePlurale = $i > 1 ? 'e' : 'a';
                        if($i == $stelle) {
                            
                            $selezioneStelle .= ' <option selected="selected" value="'. $i . '">' . $i . ' stell'. $singolarePlurale . '</option>';
                        }
                        else {
                            $selezioneStelle .= ' <option value="'. $i . '">' . $i . ' stell'. $singolarePlurale . '</option>';
                        }
    
                    }
                    
                    $page=str_replace('<SELEZIONE_STELLE/>',$selezioneStelle,$page);  
                    
                    
                }
                else {
                    //errore query
                    $erroriPagina .= "<div class=\"msg_box error_box\"> Errore durante la <span xml:lang=\"en\" lang=\"en\">query</span> sul libro</div>";
                }
                
            } else {
                //errore connessione a db
                $erroriPagina .= "<div class=\"msg_box error_box\"> Errore durante la connessione al <span xml:lang=\"en\" lang=\"en\">database</span></div>";
            }
            
        } else {
            //errore permesso
            header('location: 404.php');
            exit;
        }
    } else {
        //errore loggato
        header('location: index.php');
        exit;
    }
                    
    echo $page;
    
?>
                

    