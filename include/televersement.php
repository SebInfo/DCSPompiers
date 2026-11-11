<?php

declare(strict_types=1);

/**
 * Enregistre une image dans le dossier images et retourne son nom.
 * Retourne null lorsqu'aucun fichier n'est envoyé.
 */
function televerserImage(array $fichier, ?string &$erreur = null): ?string
{
    if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $erreur = "L'envoi de l'image a échoué.";
        return null;
    }

    if (($fichier['size'] ?? 0) > 2 * 1024 * 1024) {
        $erreur = "L'image ne doit pas dépasser 2 Mo.";
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $typeMime = $finfo->file($fichier['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    if (!isset($extensions[$typeMime])) {
        $erreur = 'Le fichier doit être une image JPEG, PNG ou GIF.';
        return null;
    }

    $nomFichier = 'engin_' . bin2hex(random_bytes(8)) . '.' . $extensions[$typeMime];
    $destination = __DIR__ . '/../images/' . $nomFichier;

    if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
        $erreur = "L'image n'a pas pu être enregistrée.";
        return null;
    }

    return $nomFichier;
}

/**
 * Supprime uniquement les images créées par l'application.
 */
function supprimerImageTeleversee(string $nomFichier): void
{
    if (!preg_match('/^engin_[a-f0-9]{16}\.(jpg|png|gif)$/', $nomFichier)) {
        return;
    }

    $chemin = __DIR__ . '/../images/' . $nomFichier;
    if (is_file($chemin)) {
        unlink($chemin);
    }
}
