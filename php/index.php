<?php

require_once('DBConnection.php');

	$pagHTML = file_get_contents("../HTML/index.html");

	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	if ($connectionSuccess == false) {
		$pagHTML=str_replace("<RISULTATI/>", "<div class=\"errorMessage\">Errore d'accesso al database</div>", $pagHTML);
	}
	else{

		// menù a tendina con generi
		$genreList="";
		$topReview="";
		$queryGenre=$dbAccess->queryDB("SELECT nome FROM generi");
		$queryTopReview=$dbAccess->queryDB(
			"SELECT L.titolo AS titolo, COALESCE(AVG(R.valutazione),'Nessun voto') AS media, C.path_img AS path_img, C.alt_text AS alt_text, COUNT(*) AS nrecensioni
			FROM libri L, recensioni R, copertine C
			WHERE L.ID=R.id_libro AND C.id_libro=L.ID
			GROUP BY L.ID
			ORDER BY nrecensioni DESC
			LIMIT 3");
		$queryTopRank=$dbAccess->queryDB(
			"SELECT L.titolo AS titolo, COALESCE(AVG(R.valutazione),'Nessun voto') AS media, C.path_img AS path_img, C.alt_text AS alt_text
			FROM libri L, recensioni R, copertine C
			WHERE L.ID=R.id_libro AND C.id_libro=L.ID
			GROUP BY L.ID
			ORDER BY media DESC
			LIMIT 3");

		$dbAccess->closeDBConnection();
        if ($queryGenre != null) {

			foreach ($queryGenre as $genre) {
				$genreList.= '<option value='. '"' . $genre['nome'] . '">' . $genre['nome'] . '</option>';
			}
			$pagHTML=str_replace("<GENERI/>", $genreList, $pagHTML);
		}

		if ($queryTopReview != null) {
			$topReview='<dl id="ranking">';
			foreach ($queryTopReview as $book) {
				$topReview .= '<dt>' . $book['titolo'] . '</dt>';
				$topReview .= '<dd><img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /> </dd>' ;
				$topReview .= '<dd>' . $book['nrecensioni'] . '</dt>';
			}
			$topReview .= '</dl>';
			$pagHTML=str_replace("<TOP3_RECENSITI/>", $topReview, $pagHTML);
		}

		if ($queryTopRank != null) {
			$topRank='<dl id="ranking">';
			foreach ($queryTopRank as $book) {
				$topRank .= '<dt>' . $book['titolo'] . '</dt>';
				$topRank .= '<dd><img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /> </dd>' ;
				$topRank .= '<dd>' . $book['media'] . '</dt>';
			}
			$topRank .= '</dl>';
			$pagHTML=str_replace("<TOP3_VOTATI/>", $topRank, $pagHTML);
		}
	}

	echo $pagHTML;

?>