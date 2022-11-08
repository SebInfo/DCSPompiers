<?php
  require('include/connection.inc.php');
  if($_SESSION['login']!=true)
  {
    header("Location:connexion.php");
  }
  if(isset($_POST['valider']))
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
<?php require('include/entete.inc.php');?>
<main>
  <?php echo generationEntete("Ajout d'un type d'Engin","Gestion des type d'engin dans les casernes"); ?>
    <div class="container">
      <div class="album py-5 bg-light">
        <div class="row justify-content-center">
          <div class="col-md-4">
            <div class="card mb-4 box-shadow">
              <img class="card-img-top" src="" id="photo"  width=20% class="img-responsive float-right" >
            </div>
          </div>
        </div>
      </div>
      <form method="post" action="ajoutTypeEngin.php" id="form" enctype="multipart/form-data" novalidate>
        <div class="form-row">
          <div class="form-control-group col-md-3">
            <label for="idTypeEngin">idTypeEngin</label>
            <input pattern="[A-Z]{2,4}" class="form-control" name="idTypeEngin" id="idTypeEngin" placeholder="Ex : EPA" required>
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
              La date de naissance est obligatoire
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-control-group col-md-6">
            <label for="LblEngin">Libellé de l'engin</label>
            <input class="form-control" name="LblEngin" id="LblEngin" required>
            <div class="invalid-feedback">
              Le libellé de l'engin est obligatoire
            </div>
          </div>
        </div>
        <div class="form-row">
          <input type="submit" value="Ajouter" class="btn btn-primary" name="valider" />
        </div>
      </form>
    </div>
</main>
<?php require('include/pied.inc.php');?>
 