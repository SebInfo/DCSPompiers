<?php
require 'connection.php';
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// On récupère les valeurs qu'il y avait dans le formulaire
	$matricule =  $_POST['matricule'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$dateNaissance = $_POST['dateNaissance'];
	$tel = $_POST['tel'];
	$sexe = $_POST['sexe'];
	$grade = $_POST['grade'];


	// On va maintenant effectuer les tests
	$erreur = False; // on est optimiste

	// tests sur le matricule
	if (!isset($matricule) || strlen($matricule)!=6) 
	{
		$erreur = True;
	}
	// tests sur le nom
	if (!isset($nom) || strlen($nom)<3 || strlen($nom)>45 || !is_string($nom))
	{
		$erreur = True;
	}
	// tests sur le prénom
	if (!isset($prenom) || strlen($prenom)<3 || strlen($prenom)>45 || !is_string($prenom))
	{
		$erreur = True;
	}

	// Ajout si pas d'erreur
	if( ! $erreur )
	{
		// Il manque l'ajout d'une occurence dans la table affectation
		// Afin de mémoriser l'affectation du pompier à sa caserne
		
		// Préparation de la requête
		$req = $db->prepare('INSERT INTO DSC.Pompier (Matricule, NomPompier, PrenomPompier, DateNaissPompier, TelPompier, SexePompier, idGrade) VALUES (:mMatricule, :mNomPompier, :mPrenomPompier, :mDateNaissPompier, :mTelPompier, :mSexePompier, :midGrade )');

		// On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
		$req->bindParam(':mMatricule', $matricule);
		$req->bindParam(':mNomPompier', $nom);
		$req->bindParam(':mPrenomPompier', $prenom);
		$req->bindParam(':mDateNaissPompier', $dateNaissance);
		$req->bindParam(':mTelPompier', $tel);
		$req->bindParam(':mSexePompier', $sexe);
		$req->bindParam(':midGrade', $grade);

		try
		{
			$req->execute();
		}
		catch(PDOException $e)
		{
			if ($e->getCode()==23000)
			{
				echo "Ce matricule existe déjà";
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
