<!doctype html>
<html>
  <head>
    <title>Ajout d'un type de véhicule</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Liaison au fichier css de Bootstrap -->
    <link href="Bootstrap/css/bootstrap.css" rel="stylesheet">

  </head>

  <body>
    <main role="main">
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Ajout d'un type Engin</h1>
          <p class="lead text-muted">Formulaire pour ajouter une type engin</p>
        </div>
      </section>
    </main>
    <div class="container">
      <h1>Ajout d'un type engin</h1>
      <img src="" id="photo"  width=20% class="img-responsive float-right" >
      <form method="post" action="AjoutTypeEngin.php" id="form" enctype="multipart/form-data" novalidate>
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