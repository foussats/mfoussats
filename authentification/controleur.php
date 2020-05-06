<?php

session_start();

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;ehi");
}

require "modele.php";

// récupération des données passées en GET
$action = $_GET['action'];

// traitement selon l'action
switch ($action) {
    case "login":
        login();
        break;
	case "logout":
	    logout();
	    break;
}

/*function login(){

	$mode = "";
	// affichage du formulaire
	if ( !isset ($_POST['mail']) ) {
		// pas de données => affichage
		$donnees = null;
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données => test d'existence de cet utilisateur
		$user = recupereEnregistrementParMailEtPassword($_POST);
		if ($user != null){
			authentificationReussie($user);
			// redirection (sinon l'url demeurera action=creer)
			header ('Location:../score/controleur.php?action=lister');
			// authentification réussie
			
		} else {
			// authentification non réussie
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}*/

function login(){
	$mode = "";
	$erreurs['auth'] = null;
	// affichage du formulaire
	if ( !isset ($_POST['mail']) ) {
		// pas de données => affichage
		$donnees = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		$user = recupereEnregistrementParMailEtPassword($_POST);
		if ($user !== null){
			// authentification réussie
			authentificationReussie($user);
			// redirection (sinon l'url demeurera action=creer)
			header ('Location:../score/controleur.php?action=lister');
		} else {
			// authentification non réussie
			$erreurs['auth'] = "Login ou mot de passe invalide";
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}

function afficherFormulaire($mode, $donnees, $erreurs){
	$titre = "Authentification";
	// création code HTML
	$mail = $donnees['mail'];
	$password = $donnees['password'];
	$erreurAuth = $erreurs['auth'];
	$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="controleur.php?action=login">
<label for="login">Login</label>
<input id="mail" type="email" name="mail" value="$mail" required aria-required="true" />
<p class="erreur"></p>
<label for="password">Password</label>
<input id="password" type="password" name="password" value="$password" required aria-required="true" />
<p class="erreur">$erreurAuth</p>
<br><br>
<button name='submit' type='submit' id='submit'>Valider</button>
</form>
EOT;
	// affichage de la vue
	require "vue.php"; 	
}

function testDonnees($donnees){
	$erreurs = array();
	$utilisateur = recupereEnregistrementParMailEtPassword($donnees);
	if($utilisateur == null) {
		$erreurs['auth'] = "Pas d'utilisateur avec ces identifiants";
	} 
	return $erreurs;
}
function authentificationReussie($utilisateur){
	//session_start();
	$_SESSION['id'] = $utilisateur['id'];
	$_SESSION['mail'] = $utilisateur['mail'];
}

// ensuite...

function logout(){
	session_destroy();
	// redirection (sinon l'url demeurera action=creer)
	header ('Location:../score/controleur.php?action=lister');
}


?>