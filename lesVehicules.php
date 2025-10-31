<?php require('include/entete.php');?>
<main>
  <?php echo generationEntete("Les véhicules de pompier","Gestion des véhicules"); ?>
  <div class="container py-3">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
        echo generationOptions('Gestion des engins','gestionEngin.jpeg','gestionTypeEngin.php');
        echo generationOptions('Gestion des Véhicules','gestionVehicule.jpeg');
        echo generationOptions('Gestion des reparations','entretienVehicule.jpeg');
      ?>
    </div>
  </div>
</main>
<?php require('include/pied.php');?>