<?php 
    require_once('sessione.php');
    require_once('setupPage.php');
    require_once('connessione.php');
    
    $page=add("../html/inserisciRecensione.html");   

    if($_SESSION['logged']==true){
        if($_SESSION['permesso']==0){
                          
            $DBconnection = new DBAccess();
            if($DBconnection->openDBConnection()) {
                
                // RECUPERO DATI SUL LIBRO
                
                $libro = "";
                $ID_libro = "";
                $nomeLibro = "";
                $nomeAutore = "";
                $erroreTesto = "";
                $messaggioSuccesso = "";
                $ID_utente = $_SESSION['ID'];
                
                if ($queryResult = $DBconnection->queryDB(" 
                    SELECT l.ID as ID, l.titolo AS titolo, a.nome AS nomeAutore, c.path_img AS path_img, c.alt_text AS alt_img
                    FROM libri AS l,autori AS a, copertine AS c
                    WHERE l.ID=$ID_libro AND l.id_autore=a.ID AND l.ID=c.id_libro"
                )) {
                    
                    $libro = $queryResult[0];
                    $ID_libro = $queryResult[0]['ID'];
                    $nomeLibro = $queryResult[0]['titolo'];
                    $nomeAutore = $queryResult[0]['nomeAutore'];
                    
                    //BREADCRUMBS
                    
                    $breadcrumb = 'Home &raquo; ' . $libro['titolo'] . '&raquo; Inserisci recensione' ;
                   
                    // RECUPERO DATI FORM
                    
                    $contenuto = "";
                    $stelle = 1;
                    $data = "";
                    if(isset($_POST['review_content'])){
                        
                        $contenuto=$_POST['review_content'];

                        if(isset($_POST['n_stars'])){
                            $stelle=$_POST['n_stars'];
                        }
                        
                        date_default_timezone_set("Europe/Rome");
                        $data=date("Y-m-d H");

                        // CONTROLLO DATI RECENSIONE
                        
                        if($contenuto != '' && strlen($contenuto) > 50 && strlen($contenuto) > 500) { //TODO: necessari controlli per sanificare?
                            
                            if ($queryResult = $DBconnection->insertDB( " 
                                    INSERT INTO recensioni(ID, dataora, valutazione, id_libro, id_utente, testo)
                                    VALUES (NULL, $id_recensione??, \"$data\", $stelle, $ID_libro, $ID_utente, \"$contenuto\")                
                                    ")) { //TODO: check ID autoincrement
                            
                                header('location: dettagliLibro.php?id='.$_POST['ID_libro']); //TODO: messaggio di successo
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
                        
                         
                        $page=str_replace('<NOME_LIBRO/>',$nomeLibro,$page);
                        $page=str_replace('<AUTORE/>',$nomeAutore,$page);

                        $page=str_replace('<ERRORE_CONTENUTO/>',$erroreTesto,$page);

                        $page=str_replace('<CONTENUTO_RECENSIONE/>',$contenuto,$page);                  
                        
                    }
                    else {
                        // non è settato il POST recensione
                        echo 'post non settato';
                    }
               
                    
                }
                else {
                    //errore query
                    echo 'errore query';
                }
                
            } else {
                //errore connessione a db
                echo 'errore connessione a db';
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
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                


            
            
         