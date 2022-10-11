<?php
require 'connection.php';

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
	$type = $_POST['type'];
	$bip = substr($_POST['bip'], 0, 3); 
	$idEmployeur = $_POST['employeur'];
	$dateEmbauche = $_POST['dateEmbauche'];
	$indice = $_POST['indice'];
	$idCaserne = $_POST['caserne'];


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
		
		// Préparation de la requête principale
		$req = $db->prepare('INSERT INTO DSC.Pompier (Matricule, NomPompier, PrenomPompier, DateNaissPompier, TelPompier, SexePompier, idGrade) VALUES (:mMatricule, :mNomPompier, :mPrenomPompier, :mDateNaissPompier, :mTelPompier, :mSexePompier, :midGrade )');

		// On va utiliser bindParam pour lier les variables PHP au paramètre :m de prépare
		$req->bindParam(':mMatricule', $matricule);
		$req->bindParam(':mNomPompier', $nom);
		$req->bindParam(':mPrenomPompier', $prenom);
		$req->bindParam(':mDateNaissPompier', $dateNaissance);
		$req->bindParam(':mTelPompier', $tel);
		$req->bindParam(':mSexePompier', $sexe);
		$req->bindParam(':midGrade', $grade);

		// On tente avec un try d'executer la requête
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

		// Requête pour insérer dans la table Professionel ou Volontaire
		// Préparation de la requête pour le Professionel
		$pro = $db->prepare('INSERT INTO DSC.Professionnel (MatPro, DateEmbauche, Indice) VALUES (:mMatricule, :mDateEmbauche, :mIndice)');

		// Préparation de la requête pour le pompier Volontaire
		$vol = $db->prepare('INSERT INTO DSC.Volontaire (MatVolontaire, Bip, IdEmployeur) VALUES (:mMatricule, :mBip, :mIdEmployeur)');

		if ($type == "volontaire") 
		{
			// Alors la requêtes qu'on va executer est $vol
			$req = $vol;
			// On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
			$req->bindParam(':mMatricule', $matricule);
			$req->bindParam(':mBip', $bip);
			$req->bindParam(':mIdEmployeur', $idEmployeur);

		}
		elseif ($type == "professionnel") {
			// Alors la requêtes qu'on va executer est $pro
			$req = $pro;
			// On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
			$req->bindParam(':mMatricule', $matricule);
			$req->bindParam(':mDateEmbauche', $dateEmbauche);
			$req->bindParam(':mIndice', $indice);
		}
		else
		{
			# Ce cas ne doit pas arriver normalement car on a un bouton radio
			echo "Houston on a un problème !";
		}

		// On tente avec un try d'executer la requête
		try
		{
			$req->execute();
		}
		catch(PDOException $e)
		{
			echo "prb dans l'ajout des tables volontaire ou professionnel !";
			echo $e->getMessage();
		}

		// Ajout dans affectation
		$req = $db->prepare('INSERT INTO DSC.Affectation (Matricule, DateAff, IdCaserne) VALUES (:mMatricule, :mDateAffectation, :mIdCaserne)');
		$req->bindParam(':mMatricule', $matricule);
		$dateJour = date("Y-m-d");
		$req->bindParam(':mDateAffectation', $dateJour );
		$req->bindParam(':mIdCaserne', $idCaserne);

		// On tente avec un try d'executer la requête
		try
		{
			$req->execute();
		}
		catch(PDOException $e)
		{
			echo "prb dans l'ajout d'une valeur dans la table Affectation !";
			echo $e->getMessage();
		}

	}
	else
	{
		echo "On a trouvé des erreurs dans le filtrage des informations";
	}
	
}

?>
