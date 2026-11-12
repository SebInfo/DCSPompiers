<?php

require_once __DIR__ . '/include/controleAcces.php';
exigerConnexion();
require_once __DIR__ . '/include/entete.php';

$idTypeEngin = $_GET['id'] ?? '';
$engin = false;

if (preg_match('/^[A-Z]{2,4}$/', $idTypeEngin)) {
    $requete = $db->prepare(
        'SELECT idTypeEngin, LblEngin, image
         FROM TypeEngin
         WHERE idTypeEngin = :idTypeEngin'
    );
    $requete->execute([':idTypeEngin' => $idTypeEngin]);
    $engin = $requete->fetch();
}
?>
<main>
  <?php echo generationEntete('Test de protection SQL', 'Recherche sécurisée d’un type d’engin.'); ?>
  <div class="container py-3">
    <?php if ($engin === false) : ?>
      <div class="alert alert-info">Indiquez un identifiant d’engin valide dans l’URL.</div>
    <?php else : ?>
      <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
        <div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" src="images/<?php echo htmlspecialchars($engin['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($engin['LblEngin'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="card-body">
              <p class="card-text"><?php echo htmlspecialchars(ucwords($engin['LblEngin']), ENT_QUOTES, 'UTF-8'); ?></p>
              <a href="ModifTypeEngin.php?id=<?php echo urlencode($engin['idTypeEngin']); ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>
              <a href="suppTypeEngin.php?id=<?php echo urlencode($engin['idTypeEngin']); ?>" class="btn btn-sm btn-outline-secondary">Supprimer</a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/include/pied.php'; ?>
