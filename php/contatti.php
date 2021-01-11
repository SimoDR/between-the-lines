<?php

	require_once('regex_checker.php');

	$pagHTML = file_get_contents('../HTML/contatti.html');
	
	$correctMessage=true;

	$firstName="";
	$lastName="";
	$email="";
	$message="";

	

	// tutti i campi non sono stati settati oppure sono ""
	if (empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['e_mail']) && empty($_POST['messagge']) ) {
		$correctMessage=false;
	}
	else{

		//TO DO: specificare qual è il formato corretto per non commettere errori (nome, cognome, e-mail)
		// TO DO: fare in modo che sia check_nome a valutare l'empty... 
		// il campo è ""  o non è nel formato corretto

		if (empty(($_POST['first_name'])) || !check_nome($_POST['first_name']) ) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_NOME/>", "Il nome deve essere ...", $pagHTML);
		}
		else {
			$firstName=$_POST['first_name'];
		}

		if (empty(($_POST['last_name'])) || !check_nome($_POST['last_name'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_COGNOME/>", "Il nome deve essere ...", $pagHTML);
		}
		else{
			$lastName=$_POST['last_name'];
		}

		if (empty(($_POST['e_mail'])) || !check_email($_POST['e_mail'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_E_MAIL/>", "e-mail deve essere...", $pagHTML);
		}
		else {
			$email=$_POST['e_mail'];		
		}

		if (empty(($_POST['messagge'])) || strlen($_POST['message']) < 15) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_MESSAGGIO/>", "Il messaggio deve essere lungo almeno 15 caratteri", $pagHTML);
		}
		else{
			$message=$_POST['message'];
		}

		// se tutto giusto "invio" il messaggio
		if ($correctMessage) {
			$pagHTML = str_replace("<CONFERMA_INVIO/>", "<div><p>Grazie, il tuo messaggio è stato inviato correttamente! Ti contatteremo al più presto</p><div>", $pagHTML);
			$firstName="";
			$lastName="";
			$email="";
			$message="";
		}
	}

	// se ci sono errori salvo solo i campi corretti; altrimenti (prima volta che finisco nella pagina oppure ho inviato il messaggio correttamente) pulisco tutti i campi

	$pagHTML = str_replace("<NOME/>", $firstName, $pagHTML);
	$pagHTML = str_replace("<COGNOME/>", $lastName, $pagHTML);
	$pagHTML = str_replace("<E_MAIL/>", $email, $pagHTML);
	$pagHTML = str_replace("<MESSAGGIO/>", $message, $pagHTML);	

	echo $pagHTML;

?>