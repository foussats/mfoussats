<?php


function recupereTous(){
	// récupération de tous les enregistrements
	$con = connexion();
	$query = "SELECT * FROM score";
	$result = $con->query($query);
	fermeture($con);
	return $result;
}

/*function connexion() {
	// connexion avec la BD
	$con = new mysqli("localhost", "root", "", "mooc_ad");
	return $con;
}

function fermeture($con) {
	// fermeture de la connexion avec la BD
	mysqli_close($con);
}*/

require "../_config/BD.php";

function ajouteEnregistrement($donnees){
	$valeur = $donnees['valeur'];
	$idUtilisateur = $_SESSION['id'];
	// ajout d'un enregistrement
	$con = connexion();
	$query = "INSERT INTO score (`id`, `valeur`, `date`, `idUtilisateur`)
		VALUES (NULL, '".$valeur."', NOW(), '".$idUtilisateur."');";
	$result = $con->query($query);
	fermeture($con);	
}
function recupereEnregistrementParId($id){
	// récupération d'un enregistrement
	$con = connexion();
	$query = "SELECT * FROM score WHERE id = $id";
	$result = $con->query($query);
	return $result->fetch_assoc();
	fermeture($con);
}	
/*function modifieEnregistrement($id, $donnees){
	$valeur = $donnees['valeur'];
	// récupération d'un enregistrement
	$con = connexion();
	$query = "UPDATE score SET `valeur` = $valeur WHERE id = $id;";
	$result = $con->query($query);
	fermeture($con);
}
*/

function modifieEnregistrement($donnees){
	$id = $donnees['id'];
	$valeur = $donnees['valeur'];
	// récupération d'un enregistrement
	$con = connexion();
	$query = "UPDATE score SET `valeur` = $valeur WHERE id = $id;";
	$result = $con->query($query);
	fermeture($con);
}

function supprimeEnregistrement($id){
	// suppression d'un enregistrement
	$con = connexion();
	$query = "DELETE FROM score WHERE id = $id";
	$result = $con->query($query);
	fermeture($con);
}	

function getUserIdFromScoreId($id)
{
	$co = connexion();
	$query = "SELECT idUtilisateur from score WHERE id = " . $id;
	$res = $co->query($query);
	return $res->fetch_assoc()['idUtilisateur'];
}
	
?>

