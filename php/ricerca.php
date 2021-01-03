<?php
	
	require_once('DBConnection.php');

	$pagHTML = file_get_contents("../HTML/ricerca.html");

	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	//TO DO: dividere i risultati per pagina
	//TO DO: cambiare query base
	//TO DO: fissare un max di ricerche per pagina	

	if ($connectionSuccess == false) {
	// TO DO: NON VA BENE: bisogna sollevare eccezioni con try e catch
		die ("Errore nell'apertura del DB");
	}
	else{

		// creo menù a tendina con generi
		$genreList="";
		$queryGenre=$dbAccess->queryDB("SELECT nome FROM generi");
        if ($queryGenre != null) {
			//stampo la ricerca
			foreach ($queryGenre as $genre) {
				$genreList.= '<option value='. '"' . $genre['nome'] . '">' . $genre['nome'] . '</option>';
			}
			$pagHTML=str_replace("<GENERI/>", $genreList, $pagHTML);
		}

		if( empty($_GET['search_bar']) )
		{
			// TO DO: NON VA BENE: bisogna sollevare eccezioni con try e catch
			$dbAccess->closeDBConnection();
			die ("Errore inserire un valore");
		}

		// TODO: MANCA IL VOTO del libro - se non ci sono recensioni voto medio = 0
		$querySearch = "SELECT L.titolo AS titolo, A.nome AS nome, A.cognome AS cognome, G.nome AS genere, AVG(IFNULL(R.valutazione,0)) AS media FROM libri L LEFT JOIN recensioni R ON  R.id_libro=L.ID INNER JOIN autori A ON L.id_autore=A.ID INNER JOIN classificazioni C ON C.id_libro=L.ID INNER JOIN generi G ON G.ID=C.id_genere AND ";

		$titleOrAuthor=$_GET['filter'];
		$genre=$_GET['genre'];
		$search=$_GET['search_bar'];

		if($titleOrAuthor == 0){ // ricerca per titolo
			$querySearch .= "L.titolo LIKE '%$search%' ";
        }
        else{ //ricerca per autore 
        	$querySearch .= "(A.nome LIKE '%$search%' OR A.cognome LIKE '%$search%' )";
        }
        if($genre!=="Qualsiasi"){ // filtro per genere
                $querySearch .= "AND G.nome='$genre' ";
        }

        $querySearch .="GROUP BY L.id";



        $resultSearch=$dbAccess->queryDB($querySearch);
        $dbAccess->closeDBConnection();
        $bookList = "";

        if ($resultSearch != null) {
			//stampo la ricerca
			$bookList = '<dl id="book_list">';
			foreach ($resultSearch as $book) {
				$bookList .= '<dt>' . $book['titolo'] . '</dt>';
				$bookList .= '<dd>' . $book['nome'] . ' ' . $book['cognome'] . '</dd>' ;
				$bookList .= '<dd>' . $book['genere']. '</dd>';
				$bookList .= '<dd>' . $book['media']. '</dd>';
			}
			$bookList .= '</dl>';
		} else {
		// messaggio che dice che non ci sono risultati del DB
		$bookList = "<p>Nessun risultato corrispondente ai criteri di ricerca</p>";
		}

		// sostituisco il segnaposto
		$pagHTML= str_replace("<RISULTATI/>", $bookList, $pagHTML);
		echo $pagHTML;
	}

?>