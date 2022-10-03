<!DOCTYPE html>
<html>
  <head>
    <title>Ajout d'un pompier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Liaison au fichier css de Bootstrap -->
    <link href="Bootstrap/css/bootstrap.css" rel="stylesheet">
    <script>
      function aff_cach_input(action)
      { 
        // Cas volontaire (bouton radio)
        if (action == "volontaire") 
        {
            document.getElementById('blockVolontaire').style.display="inline"; 
            document.getElementById('blockPro').style.display="none"; 
        }
        else if (action == "pro")
        // Cas professionnel (bouton radio)
        {
            document.getElementById('blockVolontaire').style.display="none"; 
            document.getElementById('blockPro').style.display="inline"; 
        }
        return true;
      }
    </script>
  </head>
  <body>  
  <?php
    require 'connection.php';
  ?>
  <div class="container">
    <h1>Ajout d'un Pompier</h1>
    <form method="post" action="ajoutPompier.php" id="form"  novalidate>
      <div class="form-row">
        <div class="form-control-group col-md-3">
          <label for="matricule">Matricule</label>
          <input pattern="[0-9]{6}" class="form-control" name="matricule" id="matricule" placeholder="Ex : 876524" required>
          <div class="invalid-feedback">
            Le matricule est obligatoire ( Il est constitué de 6 chiffres )
          </div>
        </div>
        <div class="form-group col-md-3">
          <label for="dateNaissance">Date de Naissance</label>
          <input type="date" class="form-control" name="dateNaissance" id="dateNaissance" required>
          <div class="invalid-feedback">
            La date de naissance est obligatoire
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-control-group col-md-6">
          <label for="nom">Nom</label>
          <input pattern="[A-Za-zéèà-]{3,45}" class="form-control" name="nom" id="Nom" required>
          <div class="invalid-feedback">
            Le nom du pompier est obligatoire (minimum 3 caractères maximum 45)
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prenom">Prénom</label>
          <input pattern="[A-Za-zéèà-]{3,45}" class="form-control" name="prenom" id="prenom" required>
          <div class="invalid-feedback">
            Le prénom du pompier est obligatoire (minimum 3 caractères maximum 45)
          </div>
        </div>
      </div>
      <div class="form-row">
        <!-- Boutons radio -->
        <div class="form-control-group col-md-6">
          <label class="md-3" for="sexe">Sexe  :</label>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="sexef" name="sexe" value="féminin">
              <label class="custom-control-label" for="sexef">féminin</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="sexem" name="sexe" value="masculin" checked>
              <label class="custom-control-label" for="sexem">masculin</label>
            </div>
          <div class="invalid-feedback">
            Le sexe est obligatoire
          </div>
        </div>
      </div>
      <div class="form-row">

        <!-- Liste déroulante -->
        <div class="form-group col-md-3">
          <label for="grade">Grade</label>
          <div class="form-group">
            <select class="form-control" id="grade" name="grade">
              <?php
                $listeGrade = "SELECT idGrade, LblGrade FROM DSC.Grade;";
                foreach  ($db->query($listeGrade) as $row) {
                  echo '<option value="'.$row["idGrade"].'">'.ucwords($row["LblGrade"]).'</option>';
                }
              ?>
            </select>
          </div>
          <div class="invalid-feedback">
            Le grade est obligatoire
          </div>
        </div>

        <div class="form-group col-md-3">
          <label for="caserne">Caserne</label>
          <div class="form-group">
            <select class="form-control" name="caserne" id="caserne">
              <?php
                $listeCaserne = "SELECT idCaserne, NomCaserne FROM DSC.Caserne;";
                foreach  ($db->query($listeCaserne) as $row) {
                  echo '<option value="'.$row["idCaserne"].'">'.ucwords($row["NomCaserne"]).'</option>';
                }
              ?>
            </select>
          </div>
          <div class="invalid-feedback">
            La caserne est obligatoire
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-control-group col-md-6">
          <label for="tel">Téléphone</label>
          <input type="tel" pattern="^[0-9]{10}$" class="form-control" name="tel" id="tel" required>
          <div class="invalid-feedback">
            Le numéro de téléphone est obligatoire
          </div>
        </div>
      </div>
      <div class="form-row">  

        <div class="form-control-group col-md-12">
          <label class="md-3" for="type">Type pompier  :</label>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="pro" name="type" value="professionnel" onchange="aff_cach_input('pro')">
            <label class="custom-control-label" for="pro">Professionnel</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input"  id="volontaire" name="type" value="volontaire" onchange="aff_cach_input('volontaire')" checked>
            <label class="custom-control-label" for="volontaire">Volontaire</label>
          </div>   
          <div class="invalid-feedback">
            Le type est obligatoire
          </div>
          
        </div>
  
        <!-- Partie volontaire -->
        <!-- Liste déroulante -->
        <div id="blockVolontaire">
          <div class="form">
            <label for="employeur" >Employeur</label>
            <div class="form-group">
              <select class="form-control col-md-6" name="employeur" id="employeur">
                <?php
                  $listeGrade = "SELECT idEmployeur, NomEmployeur FROM DSC.Employeur;";
                  foreach  ($db->query($listeGrade) as $row) {
                    echo '<option value="'.$row["idEmployeur"].'">'.ucwords($row["NomEmployeur"]).'</option>';
                  }
                ?>
              </select>
            </div>
            <div class="invalid-feedback">
                L'employeur est obligatoire
            </div>
          </div>
          <div class="form">
              <label for="bip" id="titreBip">Bip</label>
              <input type="number" class="form-control" name="bip" id="bip" placeholder="Ex : 123" >
              <div class="invalid-feedback">
                Le Bip obligatoire
              </div>
          </div>
        </div> 

        <!-- Partie pro -->
        <div id="blockPro">
          <div class="form">
              <label for="indice">Indice</label>
              <input type="number" class="form-control" name="indice" id="indice" placeholder="Ex : 840" >
              <div class="invalid-feedback">
                L'indice est obligatoire
              </div>
          </div>
          <div class="form">
              <label for="dateEmbauche">Date d'embauche'</label>
              <input type="date" class="form-control" name="dateEmbauche" id="dateEmbauche" >
              <div class="invalid-feedback">
                La date d'embauche est obligatoire'
              </div>
          </div>
        </div> 
      </div>

      <div class="form-row">
        <input type="submit" value="Valider" class="btn btn-primary" name="valider" />
      </div>

      <script>
              aff_cach_input('volontaire');
      </script>

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
  </script>
  </body>
</html>

<!-- Les liaisons aux scripts bootstrap -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>