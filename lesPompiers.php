<?php require('include/entete.inc.php');?>
<main>
  <?php echo generationEntete("Gestions des Pompiers","Gestion des pompiers volontaires et professionnels"); ?>
  <div class="container py-3">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
        echo generationOptions('Ajouter une pompier','Ajouter un pompier','SDIS.jpeg','ajoutPompier.php');
        echo generationOptions('Gestion des affectations','Affectation aux casernes');
        echo generationOptions('Gestion des habilitations','Gestions des habilitations');
      ?>
    </div>
  </div>
</main>
<?php require('include/pied.inc.php');?>