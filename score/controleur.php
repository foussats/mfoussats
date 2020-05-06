<?php

session_start();

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}

require "modele.php";

// récupération des données passées en GET
$action = $_GET['action'];

// traitement selon l'action
switch ($action) {
    case "lister":
        lister();
        break;
    case "creer":
        creer();
        break;
    case "modifier":
        modifier();
        break;
	case "supprimer":
	    supprimer();
	    break;
}

// fonctions
function lister(){
	$titre = "Liste de scores";
	// récupération des enregistrements 
	$result = recupereTous();
	// création code HTML
	$corps = "<ul>"; 
	while($r = $result->fetch_assoc()) {
	   	$corps .= "<li>";
		$corps .= $r['id'].", ".$r['valeur'];
		//if(isset($_SESSION['id'])){
		if(isset($_SESSION['id']) && $_SESSION['id'] == $r['idUtilisateur']){ 
			// liens 
			$corps .= " - <a href=\"controleur.php?action=modifier&id=".$r['id']."\">Modifier</a>";
			$corps .= " | <a href=\"controleur.php?action=supprimer&id=".$r['id']."\">Supprimer</a>";
		}
		$corps .= "</li>";
	}
	$corps .= "</ul>";

	// //lien pour création
	if( isset($_SESSION['id']) ){
		$corps .= "<a href=\"controleur.php?action=creer\">Cr&eacute;er</a>";
	}
	
	// lien pour authentification
	if ( !isset( $_SESSION['mail'] ) ) {		
		$loginLogout = "<a href=\"../authentification/controleur.php?action=login\">Login</a>";
	} 
	else {
		$loginLogout = $_SESSION['mail']." - <a href=\"../authentification/controleur.php?action=lister\">Logout</a>";
	}
	
	// affichage de la vue
	require "vue.php"; 
}

function creer(){
	$mode = "creation";
	// affichage du formulaire
	if ( !isset ($_POST['valeur']) ) {
		// pas de données => affichage
		$donnees = null;
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données => test
		$erreurs = testDonnees($_POST);
		if ($erreurs == null){
			// ajout
			ajouteEnregistrement($_POST);
			// redirection (sinon l'url demeurera action=creer)
			header ('Location:controleur.php?action=lister');
		} else {
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}
function supprimer(){
	if ( !isset ($_GET["id"]) ) {
		// pas de données 
		die("requ&ecirc;te non autoris&eacute;e");
	}
	supprimeEnregistrement($_GET["id"]);
	lister();
}
function modifier(){
	$uid = -1;
	if(isset($_GET['id']))
		$uid = getUserIdFromScoreId($_GET['id']);
	else if(isset($_POST['id']))
		$uid = getUserIdFromScoreId($_POST["id"]);
	if ($uid != $_SESSION['id']) {
		// pas de données ou alors on tente de modifier un résultat qui n'est pas à nous
		// du coup on revoie sur l'accueil
		//die("requ&ecirc;te non autoris&eacute;e");
		header ('Location:controleur.php?action=lister');
	}
	if (!isset($_GET["id"]) || $uid != $_SESSION['id']) {
		// pas de données ou alors on tente de modifier un résultat qui n'est pas à nous
		// du coup on revoie sur l'accueil
		//die("requ&ecirc;te non autoris&eacute;e");
		header ('Location:controleur.php?action=lister');
	}
	$mode = "modification";
	// affichage du formulaire
	if ( !isset ($_POST["valeur"]) ) {
		// pas de données en POST (mais en GET) => affichage avec les données de l'enregistrement
		$donnees = recupereEnregistrementParId($_GET["id"]);
		$donnees['id'] = $_GET["id"];
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données en POST => test
		$erreurs = testDonnees($_POST);
		if ($erreurs == null){
			// ajout
			modifieEnregistrement($_POST);
			lister();
		} else {
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}
function afficherFormulaire($mode, $donnees, $erreurs){
	$loginLogout = "";
	if($mode == "creation"){
		$titre = "Création";
		$action = "creer";
	} else	if($mode == "modification"){
		$titre = "Modification";
		$action = "modifier";
	}
	// création code HTML
	$valeur = $donnees['valeur'];
	$id = $donnees['id'];
	$erreurValeur = $erreurs['valeur'];
	$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="controleur.php?action=$action">
<label for="valeur">Score</label>
<input id="valeur" type="text" name="valeur" value="$valeur" required aria-required="true" />
<p class="erreur">$erreurValeur</p>
<br><br>
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;
	// affichage de la vue
	require "vue.php"; 	
}
 
function testDonnees($donnees){
	$erreurs = [];
	// test si le score est une valeur numérique
	if (!is_numeric($donnees['valeur'])) {
		$erreurs['valeur'] = "la valeur entrée doit être un nombre";
	}
	return $erreurs;
}

?>