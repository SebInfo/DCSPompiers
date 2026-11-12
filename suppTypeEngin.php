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
    if (!jetonCsrfValide($_POST['jeton_csrf'] ?? null)) {
        $erreur = 'Le formulaire a expiré. Veuillez recommencer.';
    } else {
        try {
            $requete = $db->prepare(
                'DELETE FROM TypeEngin
                 WHERE idTypeEngin = :idTypeEngin'
            );
            $requete->execute([':idTypeEngin' => $engin['idTypeEngin']]);
            supprimerImageTeleversee($engin['image']);

            header('Location: GestionTypeEngin.php');
            exit;
        } catch (PDOException $exception) {
            $erreur = "Cet engin ne peut pas être supprimé car il est encore utilisé.";
        }
    }
}

require_once __DIR__ . '/include/entete.php';
?>
<main role="main">
  <?php echo generationEntete("Suppression d'un type d'engin", 'Cette suppression est définitive.'); ?>
  <div class="container">
    <?php if ($erreur !== '') : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <div class="album py-5 bg-light">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" src="images/<?php echo htmlspecialchars($engin['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($engin['LblEngin'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="card-body">
              <p class="card-text"><?php echo htmlspecialchars(ucwords($engin['LblEngin']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form method="post" action="suppTypeEngin.php?id=<?php echo urlencode($engin['idTypeEngin']); ?>" class="text-center">
      <input type="hidden" name="jeton_csrf" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
      <a href="GestionTypeEngin.php" class="btn btn-secondary btn-lg">Annuler</a>
      <button type="submit" class="btn btn-danger btn-lg">Supprimer</button>
    </form>
  </div>
</main>
<?php require __DIR__ . '/include/pied.php'; ?>
