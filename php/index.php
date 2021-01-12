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
		$queryGenre=$dbAccess->queryDB("SELECT nome FROM generi");
        if ($queryGenre != null) {

			foreach ($queryGenre as $genre) {
				$genreList.= '<option value='. '"' . $genre['nome'] . '">' . $genre['nome'] . '</option>';
			}
			$pagHTML=str_replace("<GENERI/>", $genreList, $pagHTML);
		}
	}

	echo $pagHTML;



?>