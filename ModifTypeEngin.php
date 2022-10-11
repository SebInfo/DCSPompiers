<!doctype html>
<html>
  <head>
    <title>Modification d'un type d'engin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Liaison au fichier css de Bootstrap -->
    <link href="Bootstrap/css/bootstrap.css" rel="stylesheet">

  </head>

  <body>
    <main role="main">
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Modification d'un type Engin</h1>
          <p class="lead text-muted">Modifier les champs qui vous semble utile !</p>
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
    <form method="post" action="ModifEffTypeEngin.php?nom=<?php echo ($row["image"])?>&id=<?php echo($row["idTypeEngin"])?>" id="form" enctype="multipart/form-data" novalidate>
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


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="../../assets/js/vendor/holder.min.js"></script>
  </body>
</html>