<?php
// ==========================
// 1. Contrôle d’accès
// ==========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: connexion.php');
    exit;
}

// ==========================
// 2. Traitement du formulaire
// ==========================
$insertionOK = false;
$erreur = false;
require_once __DIR__ . '/include/baseDonnees.php';
if (isset($_POST['validerP'])) 
{
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_UNSAFE_RAW);
    if (!preg_match('/^\d{6}$/', $matricule)) {
      $erreur = true;
    }
    $erreur = false;

    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateNaissance = $_POST['dateNaissance'] ?? null;
    $tel = $_POST['tel'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $type = $_POST['type'] ?? '';
    $bip = substr($_POST['bip'] ?? '', 0, 3);
    $idEmployeur = $_POST['employeur'] ?? null;
    $dateEmbauche = $_POST['dateEmbauche'] ?? null;
    $indice = $_POST['indice'] ?? null;
    $idCaserne = $_POST['caserne'] ?? null;

    if (strlen($matricule) !== 6 || !ctype_digit($matricule)) $erreur = true;
    if (strlen($nom) < 3 || strlen($nom) > 45) $erreur = true;
    if (strlen($prenom) < 3 || strlen($prenom) > 45) $erreur = true;

    if (!$erreur) 
    {
        try {
            $db->beginTransaction();

            // Insertion du pompier
            $req = $db->prepare('
                INSERT INTO DSC.Pompier 
                (Matricule, NomPompier, PrenomPompier, DateNaissPompier, TelPompier, SexePompier, idGrade)
                VALUES (:mat, :nom, :prenom, :naiss, :tel, :sexe, :grade)
            ');
            $req->execute([
                ':mat' => $matricule,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':naiss' => $dateNaissance,
                ':tel' => $tel,
                ':sexe' => $sexe,
                ':grade' => $grade
            ]);

            if ($type === 'volontaire') {
                $req = $db->prepare('
                    INSERT INTO DSC.Volontaire (MatVolontaire, Bip, IdEmployeur)
                    VALUES (:mat, :bip, :emp)
                ');
                $req->execute([':mat' => $matricule, ':bip' => $bip, ':emp' => $idEmployeur]);
            } elseif ($type === 'professionnel') {
                $req = $db->prepare('
                    INSERT INTO DSC.Professionnel (MatPro, DateEmbauche, Indice)
                    VALUES (:mat, :emb, :ind)
                ');
                $req->execute([':mat' => $matricule, ':emb' => $dateEmbauche, ':ind' => $indice]);
            }

            // Affectation
            $req = $db->prepare('
                INSERT INTO DSC.Affectation (Matricule, DateAff, IdCaserne)
                VALUES (:mat, :date, :cas)
            ');
            $req->execute([
                ':mat' => $matricule,
                ':date' => date('Y-m-d'),
                ':cas' => $idCaserne
            ]);
            // On valide l'ajout
            $db->commit();
            require_once 'include/entete.php';
            ?>
            <!-- Ecran pour indiquer que l'ajout c'est bien déroulé -->
            <main class="d-flex flex-column justify-content-center align-items-center" style="height: 70vh;">
                <div class="alert alert-success text-center p-5 shadow-lg">
                    <h2 class="mb-3">✅ Ajout du pompier effectué avec succès !</h2>
                    <p>Vous allez être redirigé vers la liste des pompiers dans <strong>5 secondes</strong>...</p>
                    <div class="spinner-border text-success mt-3" role="status">
                        <span class="visually-hidden">Redirection...</span>
                    </div>
                    <p class="mt-4">
                        <a href="lesPompiers.php" class="btn btn-primary">Redirection immédiate</a>
                    </p>
                </div>
            </main>

            <script>
                // Redirection automatique après 5 secondes vers la page gérant les pompiers
                setTimeout(() => {
                    window.location.href = 'lesPompiers.php';
                }, 5000);
            </script>

            <?php
            require_once 'include/pied.php';
            exit;
        } 
        catch (PDOException $e) 
        {
            // Création du chemin du fichier log
            $logFile = __DIR__ . '/logs/app_errors.log';

            // Formatage du message
            $message = sprintf(
              "[%s] Erreur PDO : %s dans %s ligne %d%s",
              date('Y-m-d H:i:s'),
              $e->getMessage(),
              $e->getFile(),
              $e->getLine(),
              PHP_EOL
            );
            // Écriture dans le fichier
            error_log($message, 3, $logFile);

            echo "<div class='alert alert-danger'>Erreur lors de l’insertion (voir logs/app_errors.log)</div>";
        } 
    }
    else 
    {
        echo "<div class='alert alert-warning'>Erreur dans le formulaire.</div>";
    }
}

require_once 'include/entete.php';
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
<?php require('include/pied.php');?>