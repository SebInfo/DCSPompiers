<?php require('include/entete.inc.php');?>
<?

  if($_SESSION['login']!=true)
  {
    header("Location:connexion.php");
  }
?>
    <main role="main">
      <?php echo generationEntete("Les types engins","Voici la liste des types de véhicules que l'on peut trouver dans une caserne de pompiers."); ?>
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
          <?php
                $listeVeh = "SELECT idTypeEngin, LblEngin, image FROM TypeEngin;";
                foreach ($db->query($listeVeh) as $row) 
                {?>            
                  <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                      <img class="card-img-top" src="images/<?php echo $row["image"]?>" alt="Card image cap">
                      <div class="card-body">
                        <p class="card-text"> <?php echo ucwords($row["LblEngin"])?> </p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="btn-group">
                            <a href="ModifTypeEngin.php?id=<?php echo ($row["idTypeEngin"])?>" type="button" class="btn btn-sm btn-outline-secondary">Modifier</a>
                            <a href="SuppTypeEngin.php?id=<?php echo ($row["idTypeEngin"])?>" type="button" class="btn btn-sm btn-outline-secondary">Supprimer</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>        
                <?php }
          ?>
          </div>
        </div>
      </div>
      <div class="row"> 
        <div class="col text-center"> 
          <a href="ajoutTypeEngin.php" role="button" class="btn btn-success btn-lg">Ajouter</a>
      </div> 
    </main>
<?php require('include/pied.inc.php');?>