<?php
// ==========================
// 1. Contrôle d'accès
// ==========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: connexion.php');
    exit;
}

require_once __DIR__ . '/include/baseDonnees.php';

// ==========================
// 2. Récupération du pompier
// ==========================
$matricule = filter_input(INPUT_GET, 'matricule', FILTER_VALIDATE_INT);
if (!$matricule && isset($_POST['matricule'])) {
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_VALIDATE_INT);
}

if (!$matricule) {
    header('Location: ListePompiers.php');
    exit;
}

$pompier = null;
$erreur = '';
$success = false;

try {
    // Récupération des informations du pompier
    $query = "
        SELECT 
            p.*,
            CASE 
                WHEN pro.MatPro IS NOT NULL THEN 'professionnel'
                WHEN vol.MatVolontaire IS NOT NULL THEN 'volontaire'
                ELSE NULL
            END as TypePompier,
            pro.DateEmbauche,
            pro.Indice,
            vol.Bip,
            vol.IdEmployeur
        FROM Pompier p
        LEFT JOIN Professionnel pro ON p.Matricule = pro.MatPro
        LEFT JOIN Volontaire vol ON p.Matricule = vol.MatVolontaire
        WHERE p.Matricule = :matricule
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([':matricule' => $matricule]);
    $pompier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pompier) {
        header('Location: ListePompiers.php');
        exit;
    }
} catch (PDOException $e) {
    $erreur = "Erreur lors de la récupération des données";
    error_log($e->getMessage());
}

// ==========================
// 3. Traitement du formulaire
// ==========================
if (isset($_POST['validerModif'])) {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateNaissance = $_POST['dateNaissance'] ?? null;
    $tel = $_POST['tel'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $type = $_POST['type'] ?? '';
    
    // Champs volontaire
    $bip = substr($_POST['bip'] ?? '', 0, 3);
    $idEmployeur = $_POST['employeur'] ?? null;
    
    // Champs professionnel
    $dateEmbauche = $_POST['dateEmbauche'] ?? null;
    $indice = $_POST['indice'] ?? null;

    $erreurValidation = false;
    if (strlen($nom) < 3 || strlen($nom) > 45) $erreurValidation = true;
    if (strlen($prenom) < 3 || strlen($prenom) > 45) $erreurValidation = true;

    if (!$erreurValidation) {
        try {
            $db->beginTransaction();

            // Mise à jour du pompier
            $req = $db->prepare('
                UPDATE Pompier 
                SET NomPompier = :nom, 
                    PrenomPompier = :prenom, 
                    DateNaissPompier = :naiss, 
                    TelPompier = :tel, 
                    SexePompier = :sexe, 
                    idGrade = :grade
                WHERE Matricule = :mat
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

            // Gestion du changement de type
            $ancienType = $pompier['TypePompier'];
            
            if ($type !== $ancienType) {
                // Suppression de l'ancien type
                if ($ancienType === 'volontaire') {
                    $db->prepare('DELETE FROM Volontaire WHERE MatVolontaire = :mat')->execute([':mat' => $matricule]);
                } elseif ($ancienType === 'professionnel') {
                    $db->prepare('DELETE FROM Professionnel WHERE MatPro = :mat')->execute([':mat' => $matricule]);
                }
                
                // Ajout du nouveau type
                if ($type === 'volontaire') {
                    $req = $db->prepare('INSERT INTO Volontaire (MatVolontaire, Bip, IdEmployeur) VALUES (:mat, :bip, :emp)');
                    $req->execute([':mat' => $matricule, ':bip' => $bip, ':emp' => $idEmployeur]);
                } elseif ($type === 'professionnel') {
                    $req = $db->prepare('INSERT INTO Professionnel (MatPro, DateEmbauche, Indice) VALUES (:mat, :emb, :ind)');
                    $req->execute([':mat' => $matricule, ':emb' => $dateEmbauche, ':ind' => $indice]);
                }
            } else {
                // Mise à jour du type existant
                if ($type === 'volontaire') {
                    $req = $db->prepare('UPDATE Volontaire SET Bip = :bip, IdEmployeur = :emp WHERE MatVolontaire = :mat');
                    $req->execute([':mat' => $matricule, ':bip' => $bip, ':emp' => $idEmployeur]);
                } elseif ($type === 'professionnel') {
                    $req = $db->prepare('UPDATE Professionnel SET DateEmbauche = :emb, Indice = :ind WHERE MatPro = :mat');
                    $req->execute([':mat' => $matricule, ':emb' => $dateEmbauche, ':ind' => $indice]);
                }
            }

            $db->commit();
            $success = true;
            
            // Recharger les données
            $stmt = $db->prepare($query);
            $stmt->execute([':matricule' => $matricule]);
            $pompier = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $db->rollBack();
            $erreur = "Erreur lors de la modification";
            error_log($e->getMessage());
        }
    } else {
        $erreur = "Erreur dans le formulaire";
    }
}

require_once 'include/entete.php';
?>

<main>
  <script>
    function aff_cach_input(action) { 
      if (action == "volontaire") {
          document.getElementById('blockVolontaire').style.display="block"; 
          document.getElementById('blockPro').style.display="none"; 
      } else if (action == "professionnel") {
          document.getElementById('blockVolontaire').style.display="none"; 
          document.getElementById('blockPro').style.display="block"; 
      }
      return true;
    }
  </script>

  <div class="container">
    <?php echo generationEntete("Modification d'un pompier", "Modifier les informations du pompier " . htmlspecialchars($pompier['NomPompier'] . " " . $pompier['PrenomPompier'])); ?>
    
    <?php if ($success): ?>
      <div class="alert alert-success">
        ✅ Les modifications ont été enregistrées avec succès !
      </div>
    <?php endif; ?>
    
    <?php if ($erreur): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($erreur); ?>
      </div>
    <?php endif; ?>

    <form method="post" action="ModifPompier.php" id="form" novalidate>
      <input type="hidden" name="matricule" value="<?php echo $matricule; ?>">
      
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="matricule_display">Matricule</label>
          <input type="text" class="form-control" value="<?php echo htmlspecialchars($pompier['Matricule']); ?>" disabled>
        </div>
        <div class="form-group col-md-3">
          <label for="dateNaissance">Date de Naissance</label>
          <input type="date" class="form-control" name="dateNaissance" id="dateNaissance" 
                 value="<?php echo htmlspecialchars($pompier['DateNaissPompier']); ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nom">Nom</label>
          <input pattern="[A-Za-zéèà-]{3,45}" class="form-control" name="nom" id="nom" 
                 value="<?php echo htmlspecialchars($pompier['NomPompier']); ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prenom">Prénom</label>
          <input pattern="[A-Za-zéèà-]{3,45}" class="form-control" name="prenom" id="prenom" 
                 value="<?php echo htmlspecialchars($pompier['PrenomPompier']); ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Sexe :</label>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="sexef" name="sexe" value="féminin"
                   <?php echo ($pompier['SexePompier'] === 'féminin') ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="sexef">féminin</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="sexem" name="sexe" value="masculin"
                   <?php echo ($pompier['SexePompier'] === 'masculin') ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="sexem">masculin</label>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="grade">Grade</label>
          <select class="form-control" id="grade" name="grade">
            <?php
              $listeGrade = "SELECT idGrade, LblGrade FROM Grade;";
              foreach ($db->query($listeGrade) as $row) {
                $selected = ($row["idGrade"] == $pompier['IdGrade']) ? 'selected' : '';
                echo '<option value="'.$row["idGrade"].'" '.$selected.'>'.ucwords($row["LblGrade"]).'</option>';
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="tel">Téléphone</label>
          <input type="tel" pattern="^[0-9]{10}$" class="form-control" name="tel" id="tel" 
                 value="<?php echo htmlspecialchars($pompier['TelPompier']); ?>" required>
        </div>
      </div>

      <div class="form-row">  
        <div class="form-group col-md-12">
          <label>Type pompier :</label>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="pro" name="type" value="professionnel" 
                   onchange="aff_cach_input('professionnel')"
                   <?php echo ($pompier['TypePompier'] === 'professionnel') ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="pro">Professionnel</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" class="custom-control-input" id="volontaire" name="type" value="volontaire" 
                   onchange="aff_cach_input('volontaire')"
                   <?php echo ($pompier['TypePompier'] === 'volontaire') ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="volontaire">Volontaire</label>
          </div>   
        </div>
  
        <!-- Partie volontaire -->
        <div id="blockVolontaire">
          <div class="form-group">
            <label for="employeur">Employeur</label>
            <select class="form-control col-md-6" name="employeur" id="employeur">
              <?php
                $listeEmployeur = "SELECT idEmployeur, NomEmployeur FROM Employeur;";
                foreach ($db->query($listeEmployeur) as $row) {
                  $selected = ($row["idEmployeur"] == $pompier['IdEmployeur']) ? 'selected' : '';
                  echo '<option value="'.$row["idEmployeur"].'" '.$selected.'>'.ucwords($row["NomEmployeur"]).'</option>';
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="bip">Bip</label>
            <input type="text" class="form-control" name="bip" id="bip" 
                   value="<?php echo htmlspecialchars($pompier['Bip'] ?? ''); ?>" maxlength="3">
          </div>
        </div> 

        <!-- Partie pro -->
        <div id="blockPro">
          <div class="form-group">
            <label for="indice">Indice</label>
            <input type="number" class="form-control" name="indice" id="indice" 
                   value="<?php echo htmlspecialchars($pompier['Indice'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label for="dateEmbauche">Date d'embauche</label>
            <input type="date" class="form-control" name="dateEmbauche" id="dateEmbauche" 
                   value="<?php echo htmlspecialchars($pompier['DateEmbauche'] ?? ''); ?>">
          </div>
        </div> 
      </div>

      <div class="form-row mt-3">
        <a href="ListePompiers.php" class="btn btn-secondary me-2">Annuler</a>
        <input type="submit" value="Enregistrer les modifications" class="btn btn-primary" name="validerModif" />
      </div>
    </form>

    <script>
      // Initialisation de l'affichage selon le type
      <?php if ($pompier['TypePompier'] === 'professionnel'): ?>
        aff_cach_input('professionnel');
      <?php else: ?>
        aff_cach_input('volontaire');
      <?php endif; ?>
    </script>
  </div>
</main>

<?php require('include/pied.php'); ?>
