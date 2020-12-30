<?php
	
	require_once("DBconnection.php");

	$pagHTML = file_get_contents("../HTML/ricerca.html");
	$dbAccess = new DbAccess();
	$connectionSuccess = $dbAccess->openDBConnection();

	if ($connectionSuccess == false) 
	{
	// TO DO: NON VA BENE: bisogna sollevare eccezioni con try e catch
		die ("Errore nell'apertura del DB");
	}

	else 
	{
		//TO DO: dividere i risultati per pagina

		// TO DO: inserire ricerche forse ti piacerebbe di altri generi, dello stesso autore...

		//TO DO: cambiare query base
		//TO DO: fissare un max di ricerche per pagina
		$query = "SELECT * FROM libri L, autori A, classificazioni C, generi G WHERE L.id_autore=A.ID AND C.id_libro=L.ID AND G.ID=C.id_genere AND";

		if( !isset($_GET['RICERCA_TAG']) )
		{
			// TO DO: NON VA BENE: bisogna sollevare eccezioni con try e catch
			die ("Errore inserire un valore");
		}

		$titleOrAuthor=$_GET['FILTRO_TAG'];
		$genre=$_GET['GENERE_TAG'];

		if($titleOrAuthor == 0){ // ricerca per titolo
			$query .= "L.titolo LIKE '% "$_GET['RICERCA_TAG']" %' ";
        }
        else{ //ricerca per autore 
        	$query .= "(A.nome LIKE '%"$_GET['RICERCA_TAG']"%' OR A.cognome LIKE '%"$_GET['RICERCA_TAG']"%')";
        }
        if($genre!=="QUALSIASI_TAG"){ // ricerca per genere
                $query .= " AND G.nome='GENERE_TAG' ";
        }

        $result=queryDB($query);
        $dbAccess->closeDBConnection();
        $bookList = "";

        if ($result != null) 
        {
			//stampo la ricerca
			$bookList = '<dl id="book-list">';
			foreach ($resultQuery as $ book) 
			{
				$bookList .= '<dt>' . $book['titolo'] '</dt>';
				$bookList .= '<dd>' . $book['nome'] '</dd>';
				$bookList .= '<dd>' . $book['cognome'] '</dd>';
			}
		$bookList .= '</dl>';
		}
		else 
		{
		// messaggio che dice che non ci sono risultati del DB
		$bookList = "<p>Nessun risultato corrispondente ai criteri di ricerca</p>";
		}

		// sostituisco il segnaposto
		echo str_replace("<Risultati />", $bookList, $pagHTML);
	}

?>