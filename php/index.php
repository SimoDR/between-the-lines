<?php

require_once('DBConnection.php');
require_once('setupPage.php');
require_once ('sessione.php');
require_once('stelle.php');

$page = setup("../HTML/index.html");

$dbAccess = new DbAccess();
$connectionSuccess = $dbAccess->openDBConnection();

if ($connectionSuccess == false) {
    $page = str_replace("<RISULTATI/>", "<div class=\"errorMessage\">Errore d'accesso al database</div>", $page);
} else {

    // menù a tendina con generi
    $genreList = "";
    $topReview = "";
    $lastReview = "";

    $queryGenre = $dbAccess->queryDB("SELECT nome FROM generi");
    $queryTopReview = $dbAccess->queryDB(
        "SELECT L.ID AS id, L.titolo AS titolo, COALESCE(AVG(R.valutazione),'Nessun voto') AS media, C.path_img AS path_img, C.alt_text AS alt_text, COUNT(*) AS nrecensioni
			FROM libri L, recensioni R, copertine C
			WHERE L.ID=R.id_libro AND C.id_libro=L.ID
			GROUP BY L.ID
			ORDER BY nrecensioni DESC
			LIMIT 3");

    $queryLastReview = $dbAccess->queryDB(
        "SELECT L.ID AS id, L.titolo AS titolo, U.username AS nome, R.testo AS testo, R.valutazione AS valutazione, F.path_foto AS foto, F.alt_text AS alt, R.dataora AS dataora
			FROM libri L 
            INNER JOIN recensioni R ON R.id_libro=L.ID
            INNER JOIN utenti U ON R.id_utente=U.ID
            LEFT JOIN foto_profilo F ON U.id_propic=F.ID
			ORDER BY R.dataora DESC
			LIMIT 3");

    $dbAccess->closeDBConnection();
    if ($queryGenre != null) {

        foreach ($queryGenre as $genre) {
            $genreList .= '<option value=' . '"' . $genre['nome'] . '">' . $genre['nome'] . '</option>';
        }
        $page = str_replace("<GENERI/>", $genreList, $page);
    }

    if ($queryTopReview != null) {
        $topReview = '<div id="rankingTopReview"><h2>I libri più recensiti </h2><ol>';
        foreach ($queryTopReview as $book) {
            $topReview .= '<li><dl>';
            $topReview .= '<dt><a href="dettagliLibro.php?id_libro=' . $book['id'] . '">' . $book['titolo'] . '</a></dt>';
            $topReview .= '<dd><img src="' . $book['path_img'] . '" alt="' . $book['alt_text'] . '" /> </dd>';
            $topReview .= '<dd>' . $book['nrecensioni'] . ' recensioni</dd>';
            $topReview .= '</dl></li>';

        }
        
        $topReview .= '</ol></div>';
        $page = str_replace("<TOP3_RECENSITI/>", $topReview, $page);
    }

    if ($queryLastReview != null) {
        $lastReview = '<div id="rankingLastReview"><h2>Le nuove recensioni</h2><ol class="reviewList">';
        foreach ($queryLastReview as $book) {

            $lastReview .= '<li>
            <a href="dettagliLibro.php?id_libro=' . $book['id'] . '">' . $book['titolo'] . '</a>';


            $lastReview .= '
            <div class="review">
            <div class="reviewDetails">
            <img class="userPic" src="' . $book['foto'] . '" alt="' . $book['alt'] . '" />' .
             '<span class="username">' . $book['nome'] . '</span>' .
             '<span class="reviewDatetime">' . $book['dataora'] .'</span></div>'

             . '<div class="reviewContent"><span>' . $book['testo'] . '</span>' .
             '<span class="stelle">' . round($book['valutazione'],1) . " " .printStars($book['valutazione']) . '</span></div>';
            $lastReview .= '</div></li>';
            /*

            $lastReview .=  '<dt><a href="dettagliLibro.php?id_libro=' . $book['id'] . '">' . $book['titolo'] . '</a></dt>';

            $lastReview .= '<div class = "review"><div class="reviewDetails"><dd><img class="userPic" src="' . $book['foto'] . '" alt="' . $book['alt'] . '" /> </dd>';

            $lastReview .= '<dd class="username">' . $book['nome'] . '</dd>';
            $lastReview .= '<dd class="reviewDatetime">' . $book['dataora'] . '</dd></div>';
            $lastReview .= '<div class="reviewContent"><dd>' . $book['testo'] . '</dd>';
            $lastReview .= '<dd class="stelle">' . round($book['valutazione'],1) . " " .printStars($book['valutazione']) . '</dd></div';
            $lastReview .= '</dl></li>';
            */
        }

        $lastReview .= '</ol></div>';
        $page = str_replace("<ULTIMI3_RECENSITI/>", $lastReview, $page);
    }
}

echo $page;

?>