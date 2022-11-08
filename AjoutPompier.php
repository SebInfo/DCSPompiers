
<?php
  require('include/connection.inc.php');
  session_start();
  if($_SESSION['login']!=true)
  {
    header("Location:connexion.php");
  }
  require('include/entete.inc.php');
  if(isset($_POST['validerP']))
  {
    // On récupère les valeurs qu'il y avait dans le formulaire
    $matricule =  $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['dateNaissance'];
    $tel = $_POST['tel'];
    $sexe = $_POST['sexe'];
    $grade = $_POST['grade'];
    $type = $_POST['type'];
    $bip = substr($_POST['bip'], 0, 3); 
    $idEmployeur = $_POST['employeur'];
    $dateEmbauche = $_POST['dateEmbauche'];
    $indice = $_POST['indice'];
    $idCaserne = $_POST['caserne'];


    // On va maintenant effectuer les tests
    $erreur = False; // on est optimiste

    // tests sur le matricule
    if (!isset($matricule) || strlen($matricule)!=6) 
    {
      $erreur = True;
    }
    // tests sur le nom
    if (!isset($nom) || strlen($nom)<3 || strlen($nom)>45 || !is_string($nom))
    {
      $erreur = True;
    }
    // tests sur le prénom
    if (!isset($prenom) || strlen($prenom)<3 || strlen($prenom)>45 || !is_string($prenom))
    {
      $erreur = True;
    }

    // Ajout si pas d'erreur
    if( ! $erreur )
    {
      // Il manque l'ajout d'une occurence dans la table affectation
      // Afin de mémoriser l'affectation du pompier à sa caserne
      
      // Préparation de la requête principale
      $req = $db->prepare('INSERT INTO DSC.Pompier (Matricule, NomPompier, PrenomPompier, DateNaissPompier, TelPompier, SexePompier, idGrade) VALUES (:mMatricule, :mNomPompier, :mPrenomPompier, :mDateNaissPompier, :mTelPompier, :mSexePompier, :midGrade )');

      // On va utiliser bindParam pour lier les variables PHP au paramètre :m de prépare
      $req->bindParam(':mMatricule', $matricule);
      $req->bindParam(':mNomPompier', $nom);
      $req->bindParam(':mPrenomPompier', $prenom);
      $req->bindParam(':mDateNaissPompier', $dateNaissance);
      $req->bindParam(':mTelPompier', $tel);
      $req->bindParam(':mSexePompier', $sexe);
      $req->bindParam(':midGrade', $grade);

      // On tente avec un try d'executer la requête
      try
      {
        $req->execute();
      }
      catch(PDOException $e)
      {
        if ($e->getCode()==23000)
        {
          echo "Ce matricule existe déjà";
        }
        else
        {
          echo $e->getMessage();
        }
      }

      // Requête pour insérer dans la table Professionel ou Volontaire
      // Préparation de la requête pour le Professionel
      $pro = $db->prepare('INSERT INTO DSC.Professionnel (MatPro, DateEmbauche, Indice) VALUES (:mMatricule, :mDateEmbauche, :mIndice)');

      // Préparation de la requête pour le pompier Volontaire
      $vol = $db->prepare('INSERT INTO DSC.Volontaire (MatVolontaire, Bip, IdEmployeur) VALUES (:mMatricule, :mBip, :mIdEmployeur)');

      if ($type == "volontaire") 
      {
        // Alors la requêtes qu'on va executer est $vol
        $req = $vol;
        // On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
        $req->bindParam(':mMatricule', $matricule);
        $req->bindParam(':mBip', $bip);
        $req->bindParam(':mIdEmployeur', $idEmployeur);

      }
      elseif ($type == "professionnel") {
        // Alors la requêtes qu'on va executer est $pro
        $req = $pro;
        // On va utiliser bind pour lier les variables PHP au paramètre :m de prépare
        $req->bindParam(':mMatricule', $matricule);
        $req->bindParam(':mDateEmbauche', $dateEmbauche);
        $req->bindParam(':mIndice', $indice);
      }
      else
      {
        # Ce cas ne doit pas arriver normalement car on a un bouton radio
        echo "Houston on a un problème !";
      }

      // On tente avec un try d'executer la requête
      try
      {
        $req->execute();
      }
      catch(PDOException $e)
      {
        echo "prb dans l'ajout des tables volontaire ou professionnel !";
        echo $e->getMessage();
      }

      // Ajout dans affectation
      $req = $db->prepare('INSERT INTO DSC.Affectation (Matricule, DateAff, IdCaserne) VALUES (:mMatricule, :mDateAffectation, :mIdCaserne)');
      $req->bindParam(':mMatricule', $matricule);
      $dateJour = date("Y-m-d");
      $req->bindParam(':mDateAffectation', $dateJour );
      $req->bindParam(':mIdCaserne', $idCaserne);

      // On tente avec un try d'executer la requête
      try
      {
        $req->execute();
        header('Location: lesPompiers.php');
      }
      catch(PDOException $e)
      {
        echo "prb dans l'ajout d'une valeur dans la table Affectation !";
        echo $e->getMessage();
      }

    }
    else
    {
      echo "On a trouvé des erreurs dans le filtrage des informations";
    }
}
?>
<main>
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
  <div class="container">
    <?php echo generationEntete("Ajout d'un pompier","Ajour d'une pompier et affectation à une caserne"); ?>
      <form method="post" action="ajoutPompier.php" id="form" novalidate>
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
          <input type="submit" value="Valider" class="btn btn-primary" name="validerP" />
        </div>

        <script>
                aff_cach_input('volontaire');
        </script>

      </form>
  </div>
</main>
<?php require('include/pied.inc.php');?>