<!doctype html>
<html>
  <head>
    <title>Types de Véhicules de pompier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Liaison au fichier css de Bootstrap -->
    <link href="Bootstrap/css/bootstrap.css" rel="stylesheet">

  </head>

  <body>
    <main role="main">
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Les véhicules de pompier</h1>
          <p class="lead text-muted">Voici la liste des types de véhicules que l'on peut trouver dans une caserne de pompiers.</p>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
          <?php
                require 'connection.php';
                $listeVeh = "SELECT idTypeEngin, LblEngin, image FROM DSC.TypeEngin;";
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
    </main>
    <!-- Bouton Ajout -->
    <div class="container"> 
      <div class="row"> 
        <div class="col text-center"> 
           <a href="FormAjoutTypeEngin.php" type="button" class="btn btn-primary btn-lg">Ajouter</a>
        </div> 
      </div> 
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="../../assets/js/vendor/holder.min.js"></script>
  </body>
</html>