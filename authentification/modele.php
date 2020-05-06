<?php

function recupereEnregistrementParMailEtPassword($donnees){
	// récupération d'un enregistrement
	$mail = $donnees['mail'];
	$password = $donnees['password'];
	$con = connexion();
	$query = "SELECT * FROM utilisateur WHERE mail = '$mail' AND password = '$password'";
	$result = $con->query($query);
	return $result->fetch_assoc();
	fermeture($con);
}	

/*function connexion() {
	// connexion avec la BD
	$con = new mysqli("localhost", "root", "", "mooc_ad");
	return $con;
}

function fermeture($con) {
	// fermeture de la connexion avec la BD
	mysqli_close($con);
}
	*/
require "../_config/BD.php";
?>

