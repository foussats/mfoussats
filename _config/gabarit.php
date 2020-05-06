<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="../css/script.css"/>
</head>
<body>
<!-- BANDEAU -->
<div id="bandeau">bandeau...</div>
<!-- Login/Logout -->
<div id="auth">
<?php
	//lien pour authentification
	if(!isset($_SESSION['mail'])){
		echo "<p><a href=\"../authentification/controleur.php?action=login\">Login</a> - <a href=\"../utilisateur/controleur.php?action=creer\">S'enregistrer</a>";
	} else{
		echo $_SESSION['mail']. " - <a href=\"../authentification/controleur.php?action=logout\">Logout</a></p>";
	}
?>
</div>
<!-- Menu -->
<div id="menu">menu</div>
<!--Corps -->
<div id="corps"><?php echo $corps; ?></div>
<!-- Footer -->
<footer>MOOC AppDyn</footer>
</body>
</html>