
<?php
$lastVisit = $_COOKIE['visite'] ?? 'Première visite enregistrée';
?>

<div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">

    <div class="col-md-8 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
        <svg class="bi" width="30" height="24" aria-hidden="true">
          <use xlink:href="#bootstrap"></use>
        </svg>
      </a>

      <div>
        <p class="mb-0"><small>&copy; 2025 SDIS – Avenue de Keradennec, 29337 QUIMPER CEDEX</small></p>
        <p class="mb-0"><small>Tél. : 02 98 10 31 50</small></p>
        <p class="mt-2 text-muted">
          <small> <?= htmlspecialchars($lastVisit, ENT_QUOTES, 'UTF-8') ?></small>
        </p>
      </div>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex mb-0">
      <li class="ms-3">
        <a class="text-muted" href="#" aria-label="Twitter">
          <svg class="bi" width="24" height="24"><use xlink:href="#twitter"></use></svg>
        </a>
      </li>
      <li class="ms-3">
        <a class="text-muted" href="#" aria-label="Instagram">
          <svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg>
        </a>
      </li>
      <li class="ms-3">
        <a class="text-muted" href="#" aria-label="Facebook">
          <svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg>
        </a>
      </li>
    </ul>

    <?php if (!empty($_SESSION['login']) && $_SESSION['login'] === true) : ?>
    <a href="deconnexion.php" class="btn btn-secondary ms-3">Fin de session</a>
    <?php endif; ?>

  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous">
</script>

 <!-- JS personnalisé -->
    <script src="js/app.js"></script>
  </body>
</html>