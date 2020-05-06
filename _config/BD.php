<?php

function connexion() {
	//connexion avec la BD
	$con = new mysqli("localhost", "root", "", "mooc_ad");
	return $con;
}

function fermeture($con) {
	//fermeture de la connexion avec la BD
	mysqli_close($con);
}

?>