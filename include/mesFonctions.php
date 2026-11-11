<?php

/**
 * Génère le bloc d'en-tête placé sous le menu.
 *
 * @param string $titre Titre principal de la page.
 * @param string $sous_titre Sous-titre affiché sous le titre.
 * @return string Code HTML de l'en-tête.
 */
function generationEntete(string $titre, string $sous_titre): string
{
  // Voir pour le traitement si besoins des chaines
  return '
    <div class="p-5 mb-4 bg-light rounded-2">
      <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">'.  $titre .'</h1>
        <p class="col-md-8 fs-4">'. $sous_titre. '</p>
      </div>
    </div>';
}

/**
 * Génère une carte Bootstrap représentant une option de navigation.
 *
 * @param string $titre Titre de l'option.
 * @param string $url_image Nom de l'image dans images/imagesCarte.
 * @param string $lien Page cible du lien.
 * @param string $titre_boutons Texte affiché sur le bouton.
 * @return string Code HTML de la carte.
 */
function generationOptions(
    string $titre,
    string $url_image = "SDIS.jpeg",
    string $lien = '#',
    string $titre_boutons = "Valider"
): string {
    return '
    <div class="col">
        <div class="card shadow-sm h-100 uniform-card">
            <a href="' . htmlspecialchars($lien, ENT_QUOTES) . '" class="card-img-container d-block">
                <img src="images/imagesCarte/' . htmlspecialchars($url_image, ENT_QUOTES) . '" 
                     class="card-img-top" 
                     alt="' . htmlspecialchars($titre, ENT_QUOTES) . '">
            </a>
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title text-center">' . htmlspecialchars($titre) . '</h5>
                <a href="' . htmlspecialchars($lien, ENT_QUOTES) . '" 
                   class="w-100 btn btn-lg btn-outline-primary mt-auto">'
                   . htmlspecialchars($titre_boutons) . '</a>
            </div>
        </div>
    </div>';
}
