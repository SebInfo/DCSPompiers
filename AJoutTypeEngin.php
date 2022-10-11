<?php
require 'connection.php';
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// On récupère les valeurs qu'il y avait dans le formulaire
	$idTypeEngin =  $_POST['idTypeEngin'];
	$LblEngin = $_POST['LblEngin'];

	// On va maintenant effectuer les tests
	$erreur = False; // on est optimiste
	
	// Traitement de la photo
  	if (isset($_FILES) && count($_FILES)>0)
  	{
	    $urlPhoto = $_FILES['image'];
	    $nom_fichier = $urlPhoto['name'];
	    if (strlen($nom_fichier)==0) 
	    {
	      $nom_fichier="user.png";
	    }
  	}
  	else
  	{
  		$erreur = True;
  	}

	// tests sur l'id'
	if (!isset($idTypeEngin) || strlen($idTypeEngin)>4) 
	{
		$erreur = True;
	}

	// tests sur le libelle
	if (!isset($LblEngin) || strlen($LblEngin)<3 || strlen($LblEngin)>45 || !is_string($LblEngin))
	{
		$erreur = True;
	}
	
	// Ajout si pas d'erreur
	if( ! $erreur )
	{

		// Préparation de la requête principale
		$req = $db->prepare('INSERT INTO DSC.TypeEngin (idTypeEngin, LblEngin, image) VALUES (:midTypeEngin, :mLblEngin, :mimage )');

		// On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
		$req->bindParam(':midTypeEngin', $idTypeEngin, PDO::PARAM_STR);
		$req->bindParam(':mLblEngin', $LblEngin, PDO::PARAM_STR);
		$req->bindParam(':mimage',  $nom_fichier, PDO::PARAM_STR);

		// On tente avec un try d'executer la requête
		try
		{
			$req->execute();
			// On déplace l'image dans le répertoire
			move_uploaded_file($urlPhoto['tmp_name'],'images/'.$nom_fichier);
      		header('Location: GestionTypeEngin.php');
		}
		catch(PDOException $e)
		{
			if ($e->getCode()==23000)
			{
				echo "Cet id d'engin existe déjà";
			}
			else
			{
				echo $e->getMessage();
			}
		}	
	}
	else
	{
		echo "On a trouvé des erreurs dans le filtrage des informations";
	}
	
}

?>