<?php

require_once __DIR__ . '/include/controleAcces.php';
require_once __DIR__ . '/include/televersement.php';
exigerConnexion();
require_once __DIR__ . '/include/baseDonnees.php';

$idTypeEngin = $_GET['id'] ?? '';
if (!preg_match('/^[A-Z]{2,4}$/', $idTypeEngin)) {
    header('Location: GestionTypeEngin.php');
    exit;
}

$requete = $db->prepare(
    'SELECT idTypeEngin, LblEngin, image
     FROM TypeEngin
     WHERE idTypeEngin = :idTypeEngin'
);
$requete->execute([':idTypeEngin' => $idTypeEngin]);
$engin = $requete->fetch();

if ($engin === false) {
    header('Location: GestionTypeEngin.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelleEngin = trim($_POST['LblEngin'] ?? '');

    if (!jetonCsrfValide($_POST['jeton_csrf'] ?? null)) {
        $erreur = 'Le formulaire a expiré. Veuillez recommencer.';
    } elseif (mb_strlen($libelleEngin) < 3 || mb_strlen($libelleEngin) > 45) {
        $erreur = 'Le libellé doit contenir entre 3 et 45 caractères.';
    } else {
        $nouvelleImage = televerserImage($_FILES['image'] ?? [], $erreur);
        $nomFichier = $nouvelleImage ?? $engin['image'];

        if ($erreur === '') {
            try {
                $requete = $db->prepare(
                    'UPDATE TypeEngin
                     SET LblEngin = :libelleEngin, image = :image
                     WHERE idTypeEngin = :idTypeEngin'
                );
                $requete->execute([
                    ':libelleEngin' => $libelleEngin,
                    ':image' => $nomFichier,
                    ':idTypeEngin' => $idTypeEngin,
                ]);

                if ($nouvelleImage !== null) {
                    supprimerImageTeleversee($engin['image']);
                }

                header('Location: GestionTypeEngin.php');
                exit;
            } catch (PDOException $exception) {
                if ($nouvelleImage !== null) {
                    supprimerImageTeleversee($nouvelleImage);
                }
                $erreur = "L'engin n'a pas pu être modifié.";
            }
        }
    }
}

require_once __DIR__ . '/include/entete.php';
?>
<main role="main">
  <?php echo generationEntete("Modification d'un type d'engin", 'Modifiez le libellé et, si nécessaire, la photo.'); ?>
  <div class="container">
    <?php if ($erreur !== '') : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <div class="album py-5 bg-light">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" id="photo" src="images/<?php echo htmlspecialchars($engin['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Aperçu de l'engin">
            <div class="card-body">
              <p class="card-text"><?php echo htmlspecialchars(ucwords($engin['LblEngin']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form method="post" action="ModifTypeEngin.php?id=<?php echo urlencode($engin['idTypeEngin']); ?>" id="form" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="jeton_csrf" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-row">
        <div class="form-control-group col-md-3">
          <label for="idTypeEngin">Identifiant</label>
          <input class="form-control" value="<?php echo htmlspecialchars($engin['idTypeEngin'], ENT_QUOTES, 'UTF-8'); ?>" id="idTypeEngin" disabled>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="image">Nouvelle photo (facultative)</label>
          <input type="file" onchange="actuPhoto(this)" id="image" name="image" accept="image/jpeg, image/png, image/gif">
          <div class="form-text">JPEG, PNG ou GIF, 2 Mo maximum.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-control-group col-md-6">
          <label for="LblEngin">Libellé de l'engin</label>
          <input class="form-control" name="LblEngin" value="<?php echo htmlspecialchars($engin['LblEngin'], ENT_QUOTES, 'UTF-8'); ?>" id="LblEngin" minlength="3" maxlength="45" required>
          <div class="invalid-feedback">Le libellé doit contenir entre 3 et 45 caractères.</div>
        </div>
      </div>
      <div class="form-row">
        <input type="submit" value="Modifier" class="btn btn-primary" name="valider">
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/include/pied.php'; ?>
