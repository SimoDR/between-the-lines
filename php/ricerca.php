<?php
	
	require_once('DBConnection.php');

	$pagHTML = file_get_contents("../HTML/ricerca.html");

	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	if ($connectionSuccess == false) {
		$pagHTML=str_replace("<RISULTATI/>", "<div class=\"errorMessage\">Errore d'accesso al database</div>", $pagHTML);
	}
	else{

		// menù a tendina con generi
		$genreList="";
		$queryGenre=$dbAccess->queryDB("SELECT nome FROM generi");
        if ($queryGenre != null) {

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

        
        $querySearch.= "GROUP BY L.id ORDER BY L.ID ASC";
        $resultCount=count($dbAccess->queryDB($querySearch));

        // risultati per pagina
        $rowsPerPage = 3;

        $totalPages = ceil($resultCount / $rowsPerPage);


        if (isset($_GET['currentPage']) && is_numeric($_GET['currentPage'])) {
           $currentPage = (int) $_GET['currentPage'];
        } else {
           $currentPage = 1;
        }

        if ($currentPage > $totalPages) {
           $currentPage = $totalPages;
        } 
        if ($currentPage < 1) {
           $currentPage = 1;
        } 

        $offset = ($currentPage - 1) * $rowsPerPage;

        $querySearch .=" LIMIT $offset, $rowsPerPage"; // limito la query
        $resultSearch=$dbAccess->queryDB($querySearch);

        $dbAccess->closeDBConnection();
        $bookList = "";

        if ($resultSearch != null) {
			
			$bookList = "<p> La ricerca ha prodotto " . $resultCount . " risultat";
			if ($resultCount == 1)
				$bookList .= "o</p>";
			else{
				$bookList .= "i</p>";
			}

			$bookList .= '<dl id="book_list">';
			foreach ($resultSearch as $book) {
				$bookList .= '<dt>' . $book['titolo'] . '</dt>';
				$bookList .= '<dd><img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /> </dd>' ;
				$bookList .= '<dd>' . $book['nome'] . ' ' . $book['cognome'] . '</dd>' ;
				$bookList .= '<dd>' . $book['genere']. '</dd>';
				$bookList .= '<dd>' . $book['media']. ' voti</dd>';
				$bookList .= 

				'<dd><form action="dettagliLibro.php " method="get"> 
					<input type="hidden" name="id_libro" value ="' . $book['id'] . '"/>
					<input type="submit" value="Dettagli" class="button"/>
 				</form></dd>';
                                
			}
			$bookList .= '</dl>';
		} else {
		$bookList = "<p>Nessun risultato corrispondente ai criteri di ricerca</p>";
		}

			$i=1;
            $pagesList=" <div class=\"center\"> <div class=\"pagination\">";
            $ind=$_SERVER['REQUEST_URI'];
            if($currentPage>1){
                $prec=$currentPage-1;$ind=clearInd($ind,$totalPages);
                $pagesList= $pagesList."\n<a href=\"$ind&currentPage=$prec\">&laquo;Precedente</a>";
            }
            while($i<=$totalPages){                
            	$ind=clearInd($ind,$totalPages);
                if($i!=$currentPage){
                    $pagesList= $pagesList."\n<a href=\"$ind&currentPage=$i\">$i</a>";
                }
                else{
                    $pagesList= $pagesList."<span class=\"active\">$i</span>";
                }
                $i++;
            }
            if($currentPage<$totalPages){
                $succ=$currentPage+1;
                $pagesList= $pagesList."\n<a href=\"$ind&currentPage=$succ\">Successiva&raquo</a>";
            }
             $pagesList= $pagesList."</div></div>";

		$pagHTML= str_replace("<RISULTATI/>", $bookList, $pagHTML);
		$pagHTML= str_replace("<NUMERO_PAGINA/>", $pagesList, $pagHTML);
	}

	echo $pagHTML;

	//clean url
	function clearInd($ind,$totalPages){
        for($z=1;$z<=$totalPages;$z++){
            $ind=str_replace("&currentPage=$z","",$ind);
         }
        return $ind;
    }

?>
