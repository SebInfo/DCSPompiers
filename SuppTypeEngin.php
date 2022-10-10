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
          <h1 class="jumbotron-heading">Suppression d'un type Engin</h1>
          <p class="lead text-muted">Attention la suppression est définitive !!!</p>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row justify-content-center">
          <?php
                require 'connection.php';
                $id=$_GET["id"];
                $leTypeEngin = "SELECT idTypeEngin, LblEngin, image FROM DSC.TypeEngin WHERE idTypeEngin='$id';";
                foreach ($db->query($leTypeEngin) as $row) 
                {?>            
                  <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                      <img class="card-img-top" src="images/<?php echo $row["image"]?>" alt="Card image cap">
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
    <!-- Bouton Suppression -->
    <div class="container"> 
      <div class="row"> 
        <div class="col text-center"> 
           <a href="SuppDefTypeEngin.php?id=<?php echo($row['idTypeEngin'])?>&image=<?php echo($row['image'])?>" type="button" class="btn btn-danger btn-lg">Supprimer</a>
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