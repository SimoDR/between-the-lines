<?php
	
	require_once('DBConnection.php');
    require_once('setupPage.php');
    require_once("stelle.php");


	$pagHTML = setup("../HTML/ricerca.html");

	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	if ($connectionSuccess == false) {
		$pagHTML=str_replace("<ERRORE/>", "<div class=\"errorMessage\">Errore d'accesso al <span xml:lang=\"en\">database</span></div>", $pagHTML);
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

		$querySearch = "SELECT L.ID AS id, L.titolo AS titolo, A.nome AS nome, A.cognome AS cognome, G.nome AS genere, COALESCE( AVG(R.valutazione),0) AS media, C.path_img AS path_img, C.alt_text AS alt_text 
		FROM libri L 
		LEFT JOIN recensioni R ON  R.id_libro=L.ID 
		INNER JOIN autori A ON L.id_autore=A.ID 
		INNER JOIN generi G ON G.ID=L.id_genere 
		INNER JOIN copertine C ON C.id_libro=L.ID AND ";
                
                $titleOrAuthor='';
                $genre='';
                $search ='';       
                if(isset($_GET['filter'])) {
                    $titleOrAuthor=$dbAccess->escape_string(trim(htmlentities($_GET['filter'])));
                }
                if(isset($_GET['genre'])) {
                    $genre=$dbAccess->escape_string(trim(htmlentities($_GET['genre'])));
                }
                if(isset($_GET['search_bar'])) {
                    $search=$dbAccess->escape_string(trim(htmlentities($_GET['search_bar'])));
                }

                
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
			
			$bookList = "<div class=\"confirmationMessage\"> La ricerca ha prodotto " . $resultCount . " risultat";
			if ($resultCount == 1)
				$bookList .= "o</div>";
			else{
				$bookList .= "i</div>";
			}

			$bookList .= '<div class="bookList"><ul>';

			foreach ($resultSearch as $book) {
				$bookList .= '<li class="book"><h3>'. $book['titolo'] .'</h3>';
				$bookList .= '<img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /><dl>' ;
				$bookList .= '<dt>Autore:</dt><dd>' . $book['nome'] . ' ' . $book['cognome'] . '</dd>' ;
				$bookList .= '<dt>Genere:</dt><dd>' . $book['genere']. '</dd>';

                $stelle = 'Ancora nessuna stella per questo libro!';

                if($book['media'] != null) {
                    $stelle = round($book['media'],1) . '/5 ' . printStars($book['media']);
                }

				$bookList .= '<dt>Stelle:</dt><dd>' . $stelle . '</dd></dl>
                    <form class="form" method="get" action="dettagliLibro.php">
                        <div class="row">
                        <input type="hidden" value="' . $book['id'] . '" name="id_libro"/>
                        <input class="button buttonDettagli" type="submit" value="Dettagli del libro"/>
                        </div>
                    </form>
            </li>';
                                
			}
			$bookList .= '</ul></div>';
		} else {
		$bookList = "<div class=confirmationMessage>Nessun risultato corrispondente ai criteri di ricerca</div>";
		}

			$i=1;
            $pagesList="<div class=\"pageNumbers\">";
            $address=$_SERVER['REQUEST_URI'];
            $address=htmlspecialchars($address);

            if($currentPage>1){
                $prec=$currentPage-1;
                $pagesList= $pagesList."\n<a href=\"$address&amp;currentPage=$prec\" class=\"notCurrentPage\">Precedente</a>";
            }
            while($i<=$totalPages){                
                if($i==$currentPage){
                    $pagesList= $pagesList."<span class=\"currentPage\">$i</span>";
                }
                $i++;
            }
            if($currentPage<$totalPages){
                $succ=$currentPage+1;
                $pagesList= $pagesList."\n<a href=\"$address&amp;currentPage=$succ\" class=\"notCurrentPage\">Successivo</a>";
            }

             $pagesList= $pagesList."</div>";
             $pagHTML= str_replace("<RISULTATI/>", $bookList, $pagHTML);
             $pagHTML= str_replace("<NUMERO_PAGINA/>", $pagesList, $pagHTML);
             $pagHTML=str_replace("<ERRORE/>", "", $pagHTML);
	}

	echo $pagHTML;

?>
