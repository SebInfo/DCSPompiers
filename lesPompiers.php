<?php require('include/entete.php');?>
<main>
  <?php echo generationEntete("Gestions des Pompiers","Gestion des pompiers volontaires et professionnels"); ?>
  <div class="container py-3">
    <?php if (!empty($_SESSION['message_succes'])) : ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message_succes'], ENT_QUOTES, 'UTF-8'); ?></div>
      <?php unset($_SESSION['message_succes']); ?>
    <?php endif; ?>
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
        echo generationOptions('Ajouter un pompier','SDIS.jpeg','ajoutPompier.php');
        echo generationOptions('Modification Pompier','affectation.jpg');
        echo generationOptions('Gestion des affectations','affectation.jpg');
        echo generationOptions('Gestion des habilitations');
      ?>
    </div>
  </div>
</main>
<?php require('include/pied.php');?>
