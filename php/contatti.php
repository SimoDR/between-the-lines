<?php

	require_once('regex_checker.php');

	$pagHTML = file_get_contents('../HTML/contatti.html');
	
	$correctMessage=true;

	$firstName="";
	$lastName="";
	$email="";
	$message="";

	

	// arrivo la prima volta sulla pagina
	if (empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['e_mail']) && empty($_POST['messagge']) ) {
		$correctMessage=false;
	}
	else{
		// se l'input non è nel formato corretto il messaggio non può essere inviato

		if (!check_nome($_POST['first_name']) ) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_NOME/>", "<div class=\"errorMessagge\">Il nome deve essere lungo tra i 2 e i 30 caratteri. Deve contenere solo lettere ed eventualmente spazi</div>", $pagHTML);
		}
		else {
			$firstName=$_POST['first_name'];
		}

		if (!check_nome($_POST['last_name'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_COGNOME/>", "<div class=\"errorMessagge\">Il cognome deve essere lungo tra i 2 e i 30 caratteri. Deve contenere solo lettere ed eventualmente spazi</div>", $pagHTML);
		}
		else{
			$lastName=$_POST['last_name'];
		}

		if (!check_email($_POST['e_mail'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_E_MAIL/>", "<div class=\"errorMessagge\">e-mail non valida</div>", $pagHTML);
		}
		else {
			$email=$_POST['e_mail'];		
		}

		if (strlen($_POST['message']) < 15) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_MESSAGGIO/>", "<div class=\"errorMessagge\">Il messaggio deve essere lungo almeno 15 caratteri</div>", $pagHTML);
		}
		else{
			$message=$_POST['message'];
		}

		// se tutto giusto "invio" il messaggio
		if ($correctMessage) {
			$pagHTML = str_replace("<CONFERMA_INVIO/>", "<div class=confirmationMessage><p>Grazie, il tuo messaggio è stato inviato correttamente! Ti contatteremo al più presto</p><div>", $pagHTML);
			$firstName="";
			$lastName="";
			$email="";
			$message="";
		}
	}

	// se il messaggio contiene errori salvo solo i campi corretti; altrimenti pulisco tutti i campi

	$pagHTML = str_replace("<NOME/>", $firstName, $pagHTML);
	$pagHTML = str_replace("<COGNOME/>", $lastName, $pagHTML);
	$pagHTML = str_replace("<E_MAIL/>", $email, $pagHTML);
	$pagHTML = str_replace("<MESSAGGIO/>", $message, $pagHTML);	

	echo $pagHTML;

?>