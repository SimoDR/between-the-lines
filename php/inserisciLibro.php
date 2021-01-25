<?php
require_once("sessione.php");
require_once("regex_checker.php");
require_once('DBConnection.php');
require_once('uploadImg.php');
require_once('setupPage.php');
require_once('sessione.php');

$page = setup("../HTML/inserisciLibro.html");
$error = "";
$uploadResult=array();

$dbAccess = new DbAccess();
$connectionSuccess = $dbAccess->openDBConnection();
if ($connectionSuccess == false) {
    $error = $error . "<div class=\"errorMessage\">Errore d'accesso al database</div>";
} else {
    $titolo = "";
    $trama = "";
    $genere = 0;
    $autore = 0;
    $queryInsert = -1;
    $filePath="";
    
    $queryAuthor=$dbAccess->queryDB("SELECT * FROM autori ORDER BY cognome");
    $queryGenre=$dbAccess->queryDB("SELECT * FROM generi ORDER BY nome");
    $dbAccess->closeDBConnection();

    if ($queryAuthor != null) {
        $authorList= '<option value='. '"" selected="selected" hidden="true">' . 'Seleziona un autore' . '</option>';
        foreach ($queryAuthor as $author) {
            $authorList.= '<option value='. '"' . $author['ID'] . '">' . strtoupper($author['cognome']) . ' ' . $author['nome'] . '</option>';
        }
        $page=str_replace("<AUTORI/>", $authorList, $page);
    }

    if ($queryGenre != null) {
        $genreList= '<option value='. '"" selected="selected" hidden="true">' . 'Seleziona un genere' . '</option>';
        foreach ($queryGenre as $genre) {
            $genreList.= '<option value="' . $genre['ID'] . '">' . strtolower($genre['nome']) . '</option>';
        }
        $page=str_replace("<GENERI/>", $genreList, $page);
    }

    if (isset($_POST["addBook"])) {
        if (isset($_POST["author"]) && $_POST["author"] != "") {
            $autore = (int) $_POST["author"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato specificato l'autore del libro</div>";
        }

        if (isset($_POST["genre"]) && $_POST["genre"] != "") {
            $genere = (int) $_POST["genre"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato specificato il genere del libro</div>";
        }
        
        if (isset($_POST["titolo"])) {
            if (strlen($_POST["titolo"]) < 5) {
                $error = $error . "<div class=\"errorMessage\">Il titolo del libro è troppo breve. Esso deve contenere <strong>almeno 5 caratteri</strong>.</div>";
            } else {
                $titolo = $_POST["titolo"];
            }
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato inserito il titolo del libro.</div>";
        }
        
        if (isset($_POST["trama"])) {
            $lngth = strlen($_POST["trama"]);
            if ($lngth < 50) {
                $error = $error . "<div class=\"errorMessage\">La trama del libro è troppo breve. Essa deve contenere <strong>almeno 50 caratteri</strong>, mentre adesso ne ha solo <strong>" .$lngth. "</strong>.</div>";
            } elseif ($lngth > 500) {
                $error = $error . "<div class=\"errorMessage\">La trama del libro è troppo lunga. Essa deve contenere <strong>al massimo 500 caratteri</strong>, mentre adesso ne ha <strong>" .$lngth. "</strong>.</div>";
            } else {
                $trama = $_POST["trama"];
            }
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stata inserita la trama del libro</div>";
        }

        if(isset($_POST['alt_text'])) {
            $desc_foto=$_POST['alt_text'];
            $lngth = strlen($desc_foto);
            if ($lngth < 5) {
                $error = $error . "<div class=\"errorMessage\">Il testo alternativo alla copertina è troppo breve. Esso deve contenere <strong>almeno 5 caratteri</strong>, mentre adesso ne ha solo <strong>" .$lngth. "</strong>.</div>";
            } elseif ($lngth > 50) {
                $error = $error . "<div class=\"errorMessage\">Il testo alternativo alla copertina è troppo lungo. Esso deve contenere <strong>al massimo 50 caratteri</strong>, mentre adesso ne ha <strong>" .$lngth. "</strong>.</div>";
            } else {
                if($_FILES["fileToUpload"]['size'] != 0){
                    $factoryImg = new factoryImg();
                    $filePath="../img/copertine/";
                    if(isset($_FILES['fileToUpload'])){
                        $uploadResult = $factoryImg->uploadImage("copertine/","fileToUpload");
                        if($uploadResult['error']==""){
                            $filePath=$uploadResult['path'];
                        } else{
                            $error.=$uploadResult['error'];
                        }
                    } else {
                        $filePath="../img/copertine/icona_libro_verde.png";
                    }
                } else {
                    $filePath="../img/copertine/icona_libro_verde.png";
                }
            }
        } else {
            $error= $error ."<div class=\"errorMessage\">Non è stata inserita una descrizione per la foto. Per favore è importante inserie una descrizione di lunghezza compresa tra 5 e 50 caratteri.</div>";
        }
        if(empty($error)) {
            $obj_connection = new DBAccess();
            if ($filePath!=="../img/copertine/" && $obj_connection->openDBConnection()) {
                $titolo = $obj_connection->escape_string(trim(htmlentities($titolo)));
                $autore = $obj_connection->escape_string(trim(htmlentities($autore)));
                $genere = $obj_connection->escape_string(trim(htmlentities($genere)));
                $trama = $obj_connection->escape_string(trim(htmlentities($trama)));
                $filePath = $obj_connection->escape_string(trim(htmlentities($filePath)));
                $desc_foto = $obj_connection->escape_string(trim(htmlentities($desc_foto)));

                $queryInsert = $obj_connection->insert_and_get_id("INSERT INTO `libri` (`titolo`, `id_autore`, `id_genere`, `trama`) VALUES (\"$titolo\", $autore, $genere, \"$trama\")");
                if ($queryInsert == -1) {
                    $error = $error . "<div class=\"errorMessage\">L'inserimento non è andato a buon fine</div>";
                } else {
                    $copertinaInsert=$obj_connection->insertDB("INSERT INTO `copertine` (`id_libro`,`path_img`,`alt_text`) VALUES ($queryInsert, \"$filePath\", \"$desc_foto\")");
                    if ($copertinaInsert){
                        $error = "<div class=\"successMessage\">Inserimento avvenuto con successo.</div>";
                    } else {
                        $error = "<div class=\"errorMessage\">Inserimento del libro avvenuto con successo, inserimento della copertina non completato.</div>";
                    }
                }
                $obj_connection->closeDBConnection();
            } else {
                $error = $error . "<div class=\"errorMessage\">Impossibile inserire i dati nel database il database</div>";
            }
        }
    } 
}
$page = str_replace("<SUCCESSO/>", "$error", $page);

echo $page;
?>
