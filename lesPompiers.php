<?php require('include/entete.php');?>
<main>
  <?php echo generationEntete("Gestions des Pompiers","Gestion des pompiers volontaires et professionnels"); ?>
  <div class="container py-3">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
        echo generationOptions('Liste des pompiers','SDIS.jpeg','ListePompiers.php', 'Voir la liste');
        echo generationOptions('Ajouter un pompier','SDIS.jpeg','ajoutPompier.php', 'Ajouter');
        echo generationOptions('Gestion des habilitations','affectation.jpg', '#', 'Gérer');
      ?>
    </div>
  </div>
</main>
<?php require('include/pied.php');?>