
<?php
/**
 * Application SDIS – Gestion opérationnelle des secours
 *
 * Projet pédagogique réalisé dans le cadre du BTS SIO (option SLAM).
 * Cette application web a pour objectif de modéliser et gérer les activités du
 * Service Départemental d’Incendie et de Secours (SDIS) du Finistère.
 *
 * Fonctionnalités principales :
 * - Gestion du personnel : pompiers, habilitations et plannings de garde ;
 * - Suivi des véhicules : entretiens, inventaires et disponibilités ;
 * - (À venir) Gestion des interventions : mobilisation automatique des
 *   ressources humaines et matérielles selon les compétences et disponibilités.
 *
 * @package SDIS
 * @author  Sébastien Inion
 * @version 2.0
 * @since 2025-10-31
 * 
 */
require_once __DIR__ . '/include/entete.php';
?>
<main>
<div class="container w-50 h-50 mt-4">
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/imagesCarte/slide2.jpeg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/imagesCarte/slide3.jpeg" class="d-block w-100" alt="...">
    </div>
  </div>
</div>
</main>
<?php 
require_once __DIR__ . '/include/pied.php';
?>