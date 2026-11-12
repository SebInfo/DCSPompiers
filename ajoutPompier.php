<?php

require_once __DIR__ . '/include/controleAcces.php';
exigerConnexion();
require_once __DIR__ . '/include/baseDonnees.php';

$erreur = '';
$aujourdhui = new DateTimeImmutable('today');
$dateNaissanceMinimum = $aujourdhui->modify('-80 years');
$dateNaissanceMaximum = $aujourdhui->modify('-16 years');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données communes aux deux types de pompier.
    $matricule = trim($_POST['matricule'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $dateNaissance = $_POST['dateNaissance'] ?? '';
    $tel = trim($_POST['tel'] ?? '');
    $sexe = $_POST['sexe'] ?? '';
    $grade = filter_input(INPUT_POST, 'grade', FILTER_VALIDATE_INT);
    $type = $_POST['type'] ?? '';
    $bip = trim($_POST['bip'] ?? '');
    $idEmployeur = filter_input(INPUT_POST, 'employeur', FILTER_VALIDATE_INT);
    $dateEmbauche = $_POST['dateEmbauche'] ?? '';
    $indice = filter_input(INPUT_POST, 'indice', FILTER_VALIDATE_INT);
    $idCaserne = filter_input(INPUT_POST, 'caserne', FILTER_VALIDATE_INT);

    // Préparation des contrôles de validité pour rendre la condition suivante lisible.
    $dateNaissanceValide = DateTimeImmutable::createFromFormat('!Y-m-d', $dateNaissance);
    $dateEmbaucheValide = DateTime::createFromFormat('Y-m-d', $dateEmbauche);
    $dateNaissanceFormatValide = $dateNaissanceValide !== false
        && $dateNaissanceValide->format('Y-m-d') === $dateNaissance;
    $agePompierValide = $dateNaissanceFormatValide
        && $dateNaissanceValide >= $dateNaissanceMinimum
        && $dateNaissanceValide <= $dateNaissanceMaximum;
    $informationsCommunesInvalides = !preg_match('/^\d{6}$/', $matricule)
        || mb_strlen($nom) < 3 || mb_strlen($nom) > 45
        || mb_strlen($prenom) < 3 || mb_strlen($prenom) > 45
        || !$dateNaissanceFormatValide
        || !preg_match('/^\d{10}$/', $tel)
        || !in_array($sexe, ['féminin', 'masculin'], true)
        || !is_int($grade) || $grade < 1
        || !is_int($idCaserne) || $idCaserne < 1
        || !in_array($type, ['volontaire', 'professionnel'], true);
    $informationsVolontaireInvalides = $type === 'volontaire'
        && (!preg_match('/^\d{1,3}$/', $bip)
            || ($idEmployeur !== null && (!is_int($idEmployeur) || $idEmployeur < 1)));
    $informationsProfessionnelInvalides = $type === 'professionnel'
        && (!is_int($indice) || $indice < 0
            || !$dateEmbaucheValide || $dateEmbaucheValide->format('Y-m-d') !== $dateEmbauche);

    if (!jetonCsrfValide($_POST['jeton_csrf'] ?? null)) {
        $erreur = 'Le formulaire a expiré. Veuillez recommencer.';
    } elseif (!$dateNaissanceFormatValide) {
        $erreur = 'La date de naissance est invalide.';
    } elseif (!$agePompierValide) {
        $erreur = 'Un pompier doit être âgé de 16 à 80 ans.';
    } elseif ($informationsCommunesInvalides) {
        $erreur = 'Certaines informations du formulaire sont incorrectes.';
    } elseif ($informationsVolontaireInvalides) {
        $erreur = 'Les informations du pompier volontaire sont incorrectes.';
    } elseif ($informationsProfessionnelInvalides) {
        $erreur = 'Les informations du pompier professionnel sont incorrectes.';
    } else {
        try {
            $db->beginTransaction();

            // Insertion des informations communes.
            $requete = $db->prepare(
                'INSERT INTO Pompier
                (Matricule, NomPompier, PrenomPompier, DateNaissPompier, TelPompier, SexePompier, idGrade)
                VALUES (:matricule, :nom, :prenom, :dateNaissance, :tel, :sexe, :grade)'
            );
            $requete->execute([
                ':matricule' => $matricule,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':dateNaissance' => $dateNaissance,
                ':tel' => $tel,
                ':sexe' => $sexe,
                ':grade' => $grade,
            ]);

            if ($type === 'volontaire') {
                // Insertion des informations spécifiques à un volontaire.
                $requete = $db->prepare(
                    'INSERT INTO Volontaire (MatVolontaire, Bip, IdEmployeur)
                    VALUES (:matricule, :bip, :idEmployeur)'
                );
                $requete->execute([
                    ':matricule' => $matricule,
                    ':bip' => $bip,
                    ':idEmployeur' => is_int($idEmployeur) ? $idEmployeur : null,
                ]);
            } else {
                // Insertion des informations spécifiques à un professionnel.
                $requete = $db->prepare(
                    'INSERT INTO Professionnel (MatPro, DateEmbauche, Indice)
                    VALUES (:matricule, :dateEmbauche, :indice)'
                );
                $requete->execute([
                    ':matricule' => $matricule,
                    ':dateEmbauche' => $dateEmbauche,
                    ':indice' => $indice,
                ]);
            }

            // Une nouvelle affectation est créée à la date du jour.
            $requete = $db->prepare(
                'INSERT INTO Affectation (Matricule, DateAff, IdCaserne)
                VALUES (:matricule, :dateAffectation, :idCaserne)'
            );
            $requete->execute([
                ':matricule' => $matricule,
                ':dateAffectation' => date('Y-m-d'),
                ':idCaserne' => $idCaserne,
            ]);

            $db->commit();
            $_SESSION['message_succes'] = 'Le pompier a été ajouté.';
            header('Location: lesPompiers.php');
            exit;
        } catch (PDOException $exception) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            error_log($exception->getMessage());
            $erreur = 'Erreur lors de l’insertion du pompier.';
        }
    }
}

require_once __DIR__ . '/include/entete.php';
?>


<main>
  <script>
    function afficherChampsSelonType(typePompier) {
      const estVolontaire = typePompier === 'volontaire';
      const champBip = document.getElementById('bip');

      document.getElementById('blockVolontaire').style.display = estVolontaire ? 'block' : 'none';
      document.getElementById('blockPro').style.display = estVolontaire ? 'none' : 'block';
      champBip.required = estVolontaire;
      champBip.disabled = !estVolontaire;
    }
  </script>
  <div class="container">
    <?php echo generationEntete("Ajout d'un pompier", "Ajout d'un pompier et affectation à une caserne"); ?>
    <?php if ($erreur !== '') : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
      <form method="post" action="ajoutPompier.php" id="form" novalidate>
        <input type="hidden" name="jeton_csrf" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
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
            <input type="date" class="form-control" name="dateNaissance" id="dateNaissance" min="<?php echo $dateNaissanceMinimum->format('Y-m-d'); ?>" max="<?php echo $dateNaissanceMaximum->format('Y-m-d'); ?>" required>
            <div class="invalid-feedback">
              La date de naissance doit correspondre à un âge compris entre 16 et 80 ans.
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-control-group col-md-6">
            <label for="nom">Nom</label>
            <input pattern="[A-Za-zéèà-]{3,45}" class="form-control" name="nom" id="nom" required>
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
                  $listeGrade = "SELECT idGrade, LblGrade FROM Grade;";
                  foreach  ($db->query($listeGrade) as $row) {
                    echo '<option value="'.(int) $row["idGrade"].'">'.htmlspecialchars(ucwords($row["LblGrade"]), ENT_QUOTES, 'UTF-8').'</option>';
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
                  $listeCaserne = "SELECT idCaserne, NomCaserne FROM Caserne;";
                  foreach  ($db->query($listeCaserne) as $row) {
                    echo '<option value="'.(int) $row["idCaserne"].'">'.htmlspecialchars(ucwords($row["NomCaserne"]), ENT_QUOTES, 'UTF-8').'</option>';
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
              <input type="radio" class="custom-control-input" id="pro" name="type" value="professionnel" onchange="afficherChampsSelonType('professionnel')">
              <label class="custom-control-label" for="pro">Professionnel</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="volontaire" name="type" value="volontaire" onchange="afficherChampsSelonType('volontaire')" checked>
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
                    $listeEmployeurs = "SELECT idEmployeur, NomEmployeur FROM Employeur;";
                    foreach ($db->query($listeEmployeurs) as $row) {
                      echo '<option value="'.(int) $row["idEmployeur"].'">'.htmlspecialchars(ucwords($row["NomEmployeur"]), ENT_QUOTES, 'UTF-8').'</option>';
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
                <input type="number" class="form-control" name="bip" id="bip" placeholder="Ex : 123" min="0" max="999" step="1" required>
                <div class="invalid-feedback">
                  Le BIP est obligatoire et doit contenir de 1 à 3 chiffres.
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
                <label for="dateEmbauche">Date d'embauche</label>
                <input type="date" class="form-control" name="dateEmbauche" id="dateEmbauche" >
                <div class="invalid-feedback">
                  La date d'embauche est obligatoire
                </div>
            </div>
          </div> 
        </div>

        <div class="form-row">
          <input type="submit" value="Valider" class="btn btn-primary" name="validerP" />
        </div>

        <script>
                afficherChampsSelonType('volontaire');
        </script>

      </form>
  </div>
</main>
<?php require('include/pied.php');?>
