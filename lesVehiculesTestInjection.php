<?php require('include/entete.inc.php');?>
<main>
  <?php echo generationEntete("Test hacking","Injection Code SQL"); ?>
  <div class="container py-3">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
    <?php
                $id = $_GET['id'];
                $listeVeh = "SELECT * FROM TypeEngin WHERE idTypeEngin = $id;";
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
</main>
<?php require('include/pied.inc.php');?>