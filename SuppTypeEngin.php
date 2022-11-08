<?php
  session_start();
  if($_SESSION['login']!=true)
  {
    header("Location:connexion.php");
  }
  if(isset($_GET['id']) && isset($_GET['image']))
  {
    require('include/connection.inc.php');
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
  }
?>
<?php require('include/entete.inc.php');?>
  <main role="main">
    <?php echo generationEntete("Suppression d'un type Engin","Attention la suppression est définitive !!!"); ?>
    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row justify-content-center">
          <?php
            // On récupère l'id passé en paramètre pour avoir l'id du camion
            $id = $_GET["id"];
            $leTypeEngin = "SELECT idTypeEngin, LblEngin, image FROM DSC.TypeEngin WHERE idTypeEngin='$id';";
            $row = $db->query($leTypeEngin)->fetch();
          ?>            
          <div class="col-md-4">
            <div class="card mb-4 box-shadow">
              <img class="card-img-top" src="images/<?php echo $row["image"]?>" alt="Card image cap">
              <div class="card-body">
                <p class="card-text"> <?php echo ucwords($row["LblEngin"])?> </p>
              </div>
            </div>
          </div>        
        </div>
      </div>
    </div>
    <div class="row"> 
      <div class="col text-center"> 
          <a href="suppTypeEngin.php?id=<?php echo($row['idTypeEngin'])?>&image=<?php echo($row['image'])?>" role="button" class="btn btn-danger btn-lg">Supprimer</a>
      </div> 
    </div> 
  </main>
<?php require('include/pied.inc.php');?>