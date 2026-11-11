<?php

require_once __DIR__ . '/include/controleAcces.php';
demarrerSession();

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

    if (!jetonCsrfValide($_POST['jeton_csrf'] ?? null)) {
        $erreur = 'Le formulaire a expiré. Veuillez recommencer.';
    } elseif ($login === 'Mylène' && $motDePasse === 'Micoton') {
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        header('Location: index.php');
        exit;
    } else {
        $erreur = 'Identifiant ou mot de passe incorrect.';
    }
}

require_once __DIR__ . '/include/entete.php';
?>
<main>
  <?php echo generationEntete('Identification', 'Veuillez vous identifier.'); ?>
  <form class="container col-6 col-md-4" method="post" action="connexion.php">
    <?php if ($erreur !== '') : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <input type="hidden" name="jeton_csrf" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
    <div class="form-group">
      <label for="login">Login</label>
      <input type="text" name="login" class="form-control" id="login" required autocomplete="username">
    </div>
    <div class="form-group">
      <label for="motDePasse">Mot de passe</label>
      <input type="password" name="motDePasse" class="form-control" id="motDePasse" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn btn-primary">Valider</button>
  </form>
</main>
<?php require __DIR__ . '/include/pied.php'; ?>
