<?php
	
	require_once('DBConnection.php');

	$pagHTML = file_get_contents("../HTML/ricerca.html");

	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	//TO DO: dividere i risultati per pagina
	//TO DO: fissare un max di ricerche per pagina	

	$results_per_page = 3;
	if ($connectionSuccess == false) {
		$pagHTML=str_replace("<RISULTATI/>", "<div class=\"errorMessage\">Errore d'accesso al database</div>
", $pagHTML);
		echo $pagHTML;
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

		$querySearch = "SELECT L.ID AS id, L.titolo AS titolo, A.nome AS nome, A.cognome AS cognome, G.nome AS genere, COALESCE( AVG(R.valutazione),'Nessun voto') AS media, C.path_img AS path_img, C.alt_text AS alt_text 
		FROM libri L 
		LEFT JOIN recensioni R ON  R.id_libro=L.ID 
		INNER JOIN autori A ON L.id_autore=A.ID 
		INNER JOIN generi G ON G.ID=L.id_genere 
		INNER JOIN copertine C ON C.id_libro=L.ID AND ";

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
        $rowCount=count($resultSearch);
        $dbAccess->closeDBConnection();
        $bookList = "";

        if ($resultSearch != null) {
			//stampo la ricerca

			//TODO: SE 1 un risultato

			$bookList = "<p>La ricerca ha prodotto " . $rowCount . " risultati</p>";

			$bookList .= '<dl id="book_list">';
			foreach ($resultSearch as $book) {
				$bookList .= '<dt>' . $book['titolo'] . '</dt>';
				$bookList .= '<dd><img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /> </dd>' ;
				$bookList .= '<dd>' . $book['nome'] . ' ' . $book['cognome'] . '</dd>' ;
				$bookList .= '<dd>' . $book['genere']. '</dd>';
				$bookList .= '<dd>' . $book['media']. '</dd>';
				$bookList .= 
				'<dd><form action="dettagli_libro.php " method="get"> 
					<input type="hidden" name="id_libro" value =' . $book['id'] . '/>
					<input type="submit" value="Dettagli" class="button"/>
 				</form></dd>';
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