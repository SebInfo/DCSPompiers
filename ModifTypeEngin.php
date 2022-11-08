<?php 
session_start();
if($_SESSION['login']!=true)
{
  header("Location:connexion.php");
}
require('include/entete.inc.php');
if(isset($_POST['valider']))
{
  require('include/connection.inc.php');
  $ancienNom = $_GET['nom'];
  // On récupère les valeurs qu'il y avait dans le formulaire
	$LblEngin = $_POST['LblEngin'];
	$idTypeEngin =  $_GET['id'];
	

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
		$req = $db->prepare('UPDATE DSC.TypeEngin SET LblEngin=:mLblEngin, image=:mimage WHERE idTypeEngin=:midTypeEngin');

		// On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
		$req->bindParam(':midTypeEngin', $idTypeEngin, PDO::PARAM_STR);
		$req->bindParam(':mLblEngin', $LblEngin, PDO::PARAM_STR);
		$req->bindParam(':mimage',  $nom_fichier, PDO::PARAM_STR);

		// On tente avec un try d'executer la requête
		try
		{
			$req->execute();
			// On déplace l'image dans le répertoire
			unlink("images/".$ancienNom);
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
else
{
    echo generationEntete("Modification d'un type Engin","Modifier les champs qui vous semble utile !"); 
}
?>
    <main role="main">
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row justify-content-center">
          <?php
                $id=$_GET["id"];
                $leTypeEngin = "SELECT idTypeEngin, LblEngin, image FROM DSC.TypeEngin WHERE idTypeEngin='$id';";
                foreach ($db->query($leTypeEngin) as $row) 
                {?>            
                  <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                      <img class="card-img-top" id="photo" src="images/<?php echo $row["image"]?>" alt="Card image cap">
                      <div class="card-body">
                        <p class="card-text"> <?php echo ucwords($row["LblEngin"])?> </p>
                      </div>
                    </div>
                  </div>        
                <?php }
          ?>
          </div>
        </div>
      </div>
    </main>
    <div class="container">
    <form method="post" action="ModifTypeEngin.php?nom=<?php echo ($row["image"])?>&id=<?php echo($row["idTypeEngin"])?>" id="form" enctype="multipart/form-data" novalidate>
        <div class="form-row">
          <div class="form-control-group col-md-3">
            <label for="idTypeEngin">Identifiant  (champ non modifiable)</label>
            <input pattern="[A-Z]{2,4}" disabled="disabled" class="form-control" value="<?php echo ($row["idTypeEngin"])?>" name="idTypeEngin" id="idTypeEngin" placeholder="Ex : EPA" required>
            <div class="invalid-feedback">
              L'id du type Véhicule est obligatoire ( Il est constitué de 3 à 4 caractère en majuscule )
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="image">Photo</label>
            <input type="file" onchange="actuPhoto(this)" id="image" name="image" accept="image/jpeg, image/png, image/gif">
            <div class="invalid-feedback">
              La photo est obligatoire
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-control-group col-md-6">
            <label for="LblEngin">Libellé de l'engin</label>
            <input class="form-control" name="LblEngin" value="<?php echo ucwords($row["LblEngin"])?>" id="LblEngin" required>
            <div class="invalid-feedback">
              Le libellé de l'engin est obligatoire
            </div>
          </div>
        </div>
        <div class="form-row">
          <input type="submit" value="Modifier" class="btn btn-primary" name="valider" />
        </div>
    </form>
  	</div>
 
  	<script>
     (function() {
        "use strict"
        window.addEventListener("load", function() {
          var form = document.getElementById("form")
          form.addEventListener("submit", function(event) {
            if (form.checkValidity() == false) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add("was-validated")
          }, false)
        }, false)

      }())

      function actuPhoto(element)
      {
        var image=document.getElementById("image");
        var fReader = new FileReader();
        fReader.readAsDataURL(image.files[0]);
        fReader.onloadend = function(event)
        {
          var img = document.getElementById("photo");
          img.src = event.target.result;
        }
      }
    </script>
<?php require('include/pied.inc.php');?>