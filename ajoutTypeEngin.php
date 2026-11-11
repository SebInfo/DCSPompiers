<?php

require_once __DIR__ . '/include/controleAcces.php';
require_once __DIR__ . '/include/televersement.php';
exigerConnexion();
require_once __DIR__ . '/include/baseDonnees.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTypeEngin = strtoupper(trim($_POST['idTypeEngin'] ?? ''));
    $libelleEngin = trim($_POST['LblEngin'] ?? '');

    if (!jetonCsrfValide($_POST['jeton_csrf'] ?? null)) {
        $erreur = 'Le formulaire a expiré. Veuillez recommencer.';
    } elseif (!preg_match('/^[A-Z]{2,4}$/', $idTypeEngin)) {
        $erreur = "L'identifiant doit contenir de 2 à 4 lettres majuscules.";
    } elseif (mb_strlen($libelleEngin) < 3 || mb_strlen($libelleEngin) > 45) {
        $erreur = 'Le libellé doit contenir entre 3 et 45 caractères.';
    } else {
        $nomFichier = televerserImage($_FILES['image'] ?? [], $erreur) ?? 'vehiculePompier.jpeg';

        if ($erreur === '') {
            try {
                $requete = $db->prepare(
                    'INSERT INTO DSC.TypeEngin (idTypeEngin, LblEngin, image)
                     VALUES (:idTypeEngin, :libelleEngin, :image)'
                );
                $requete->execute([
                    ':idTypeEngin' => $idTypeEngin,
                    ':libelleEngin' => $libelleEngin,
                    ':image' => $nomFichier,
                ]);

                header('Location: GestionTypeEngin.php');
                exit;
            } catch (PDOException $exception) {
                supprimerImageTeleversee($nomFichier);
                $erreur = $exception->getCode() === '23000'
                    ? 'Cet identifiant d’engin existe déjà.'
                    : "L'engin n'a pas pu être ajouté.";
            }
        }
    }
}

require_once __DIR__ . '/include/entete.php';
?>
<main>
  <?php echo generationEntete("Ajout d'un type d'engin", "Gestion des types d'engin dans les casernes"); ?>
  <div class="container">
    <?php if ($erreur !== '') : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <div class="album py-5 bg-light">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" src="images/vehiculePompier.jpeg" id="photo" alt="Aperçu de l'engin">
          </div>
        </div>
      </div>
    </div>
    <form method="post" action="ajoutTypeEngin.php" id="form" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="jeton_csrf" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-row">
        <div class="form-control-group col-md-3">
          <label for="idTypeEngin">Identifiant</label>
          <input pattern="[A-Z]{2,4}" class="form-control" name="idTypeEngin" id="idTypeEngin" placeholder="Ex. : EPA" required>
          <div class="invalid-feedback">L'identifiant doit contenir de 2 à 4 lettres majuscules.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="image">Photo (facultative)</label>
          <input type="file" onchange="actuPhoto(this)" id="image" name="image" accept="image/jpeg, image/png, image/gif">
          <div class="form-text">JPEG, PNG ou GIF, 2 Mo maximum.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-control-group col-md-6">
          <label for="LblEngin">Libellé de l'engin</label>
          <input class="form-control" name="LblEngin" id="LblEngin" minlength="3" maxlength="45" required>
          <div class="invalid-feedback">Le libellé doit contenir entre 3 et 45 caractères.</div>
        </div>
      </div>
      <div class="form-row">
        <input type="submit" value="Ajouter" class="btn btn-primary" name="valider">
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/include/pied.php'; ?>
