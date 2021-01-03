<?php

	require_once('regex_checker.php');

	$pagHTML = file_get_contents('../HTML/contatti.html');
	
	$correctMessage=true;

	$firstName="";
	$lastName="";
	$email="";
	$message="";

	//TO DO: specificare qual è il formato corretto per non commettere errori

		if (empty(($_POST['first_name'])) OR !check_nome($_POST['first_name']) ) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_NOME/>", "Nome errato", $pagHTML);
			
		}
		else {
			$firstName=$_POST['first_name'];
		}

		if (empty(($_POST['last_name'])) OR !check_nome($_POST['last_name'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_COGNOME/>", "Cognome errato", $pagHTML);
		}
		else{
			$lastName=$_POST['last_name'];
		}

		if (empty(($_POST['e_mail'])) OR !check_email($_POST['e_mail'])) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_E_MAIL/>", "e-mail errata", $pagHTML);
		}
		else {
			$email=$_POST['e_mail'];		
		}

		if (empty(($_POST['messagge'])) OR strlen($_POST['message']) < 15) {
			$correctMessage=false;
			$pagHTML = str_replace("<ERRORE_MESSAGGIO/>", "Il messaggio non può essere meno lungo di 15 caratteri", $pagHTML);
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

	// se ci sono errori, salvo solo i campi corretti; se il messaggio è stato corretto pulisco tutti i campi

	$pagHTML = str_replace("<NOME/>", $firstName, $pagHTML);
	$pagHTML = str_replace("<COGNOME/>", $lastName, $pagHTML);
	$pagHTML = str_replace("<E_MAIL/>", $email, $pagHTML);
	$pagHTML = str_replace("<MESSAGGIO/>", $message, $pagHTML);	

	echo $pagHTML;

?>