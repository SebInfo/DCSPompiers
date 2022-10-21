<?php require('include/entete.inc.php');?>
<main>
  <?php echo generationEntete("Les véhicules de pompier","Gestion des véhicules"); ?>
  <div class="container py-3">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
        echo generationOptions('Gestion des engins','Gestion des types engin qui peuvent exister','gestionEngin.jpeg','gestionTypeEngin.php');
        echo generationOptions('Gestion des Véhicules','Pour ajouter des véhicules aux casernes.','gestionVehicule.jpeg');
        echo generationOptions('Gestion des reparations','Entretiens et suivis des véhicules de la flotte.','entretienVehicule.jpeg');
      ?>
    </div>
  </div>
</main>
<?php require('include/pied.inc.php');?>