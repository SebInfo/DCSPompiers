<?php

/**
 * Genere le code HTML de l'entête en dessous du menu
 *
 * @param  string Titre de la page
 * @param  string Le sous titre
 * @return string
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
 * Génère le code HTML avec Bootstrap pour les options dans une page
 *
 * @param  string Titre de l'option
 * @param  string Url de l'image (ex : images/imagesCarte/gestionEngin.jpeg )
 * @param  string Url où va pointer le bouton
 * @param  string Titre du bouton (falcultatif) Go si pas renseigné 
 * @return string Retourne le code HTML 
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
