<?php
require_once("regex_checker.php");
require_once('DBConnection.php');
require_once('uploadImg.php');
require_once('setupPage.php');

$page = setup("../HTML/inserisciLibro.html");
$error = "";
$uploadResult=array();

$dbAccess = new DbAccess();
$connectionSuccess = $dbAccess->openDBConnection();
if ($connectionSuccess == false) {
    $pagHTML=str_replace("<SUCCESSO/>", "<div class=\"errorMessage\">Errore d'accesso al database</div>", $pagHTML);
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
            $authorList.= '<option value='. '"' . $author['ID'] . '">' . $author['cognome'] . ' ' . $author['nome'] . '</option>';
        }
        $page=str_replace("<AUTORI/>", $authorList, $page);
    }

    if ($queryGenre != null) {
        $genreList= '<option value='. '"" selected="selected" hidden="true">' . 'Seleziona un genere' . '</option>';
        foreach ($queryGenre as $genre) {
            $genreList.= '<option value="' . $genre['ID'] . '">' . $genre['nome'] . '</option>';
        }
        $page=str_replace("<GENERI/>", $genreList, $page);
    }

    if (isset($_POST["addBook"])) {
        if (isset($_POST["titolo"])) {
            $titolo = $_POST["titolo"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato inserito il titolo del libro</div>";
        }
        if (isset($_POST["trama"])) {
            $trama = $_POST["trama"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stata inserita la trama del libro</div>";
        }
        if (isset($_POST["genre"]) && $_POST["genre"] != "") {
            $genere = (int) $_POST["genre"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato specificato il genere del libro</div>";
        }
        if (isset($_POST["author"]) && $_POST["author"] != "") {
            $autore = (int) $_POST["author"];
        } else {
            $error = $error . "<div class=\"errorMessage\">Non è stato specificato l'autore del libro</div>";
        }

        if(isset($_POST['alt_text'])){
            $desc_foto=$_POST['alt_text'];
            if($desc_foto==''){
                $err_desc='<p class="errorMessage">Inserire una descrizione per la foto</p>';
            }
            if($_FILES["fileToUpload"]['size'] != 0){
                $factoryImg = new factoryImg();
                $filePath="../img/Copertine/";
                if(isset($_FILES['fileToUpload'])){
                    $uploadResult = $factoryImg->uploadImage("Copertine/","fileToUpload");
                    if($uploadResult['error']==""){
                        $filePath=$uploadResult['path'];
                    } else{
                        $error.=$uploadResult['error'];
                    }
                }
            }
        }
        if(empty($error)) {
            $obj_connection = new DBAccess();
            if ($filePath!=="../img/Copertine/" && $obj_connection->openDBConnection()) {
                $queryInsert = $obj_connection->insert_and_get_id("INSERT INTO `libri` (`titolo`, `id_autore`, `id_genere`, `trama`) VALUES (\"$titolo\", $autore, $genere, \"$trama\")");
                if ($queryInsert == -1) {
                    $error = $error . "<div class=\"errorMessage\">l'inserimento non è andato a buon fine</div>";
                } else {
                    $obj_connection->insertDB("INSERT INTO `copertine` (`id_libro`,`path_img`,`alt_text`) VALUES ($queryInsert, \"$filePath\", \"$desc_foto\")");
                }
                $obj_connection->closeDBConnection();
            } else {
                $error = $error . "<div class=\"errorMessage\">Impossibile stabilire una connessione con il database</div>";
            }
        }
    } 
}

echo $page;
?>
