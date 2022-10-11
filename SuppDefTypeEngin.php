<?php
	require 'connection.php';
	$nomFichier = $_GET["image"];
	$req = $db->prepare('DELETE FROM DSC.TypeEngin WHERE idTypeEngin = :mid ');
	$req->bindParam(':mid', $_GET["id"]);
	try
	{
		$req->execute();
		// On efface la photo du répertoire
		unlink("images/".$nomFichier);
  		header('Location: GestionTypeEngin.php');
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}	
?>