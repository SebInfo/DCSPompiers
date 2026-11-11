<?php

declare(strict_types=1);

/**
 * Démarre la session si nécessaire.
 *
 * @return void
 */
function demarrerSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Indique si un utilisateur est identifié.
 *
 * @return bool True si la session correspond à un utilisateur identifié.
 */
function estConnecte(): bool
{
    demarrerSession();

    return ($_SESSION['login'] ?? false) === true;
}

/**
 * Protège une page réservée aux utilisateurs identifiés.
 * Redirige vers la page de connexion si nécessaire.
 *
 * @return void
 */
function exigerConnexion(): void
{
    if (!estConnecte()) {
        header('Location: connexion.php');
        exit;
    }
}

/**
 * Retourne le jeton CSRF de la session, ou en crée un.
 *
 * @return string Jeton CSRF associé à la session en cours.
 * @throws Exception Si PHP ne peut pas générer de données aléatoires sûres.
 */
function jetonCsrf(): string
{
    demarrerSession();

    if (empty($_SESSION['jeton_csrf'])) {
        $_SESSION['jeton_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['jeton_csrf'];
}

/**
 * Vérifie le jeton envoyé par un formulaire qui modifie les données.
 *
 * @param string|null $jeton Jeton reçu dans le formulaire.
 * @return bool True si le jeton correspond à celui de la session.
 */
function jetonCsrfValide(?string $jeton): bool
{
    demarrerSession();

    return is_string($jeton)
        && isset($_SESSION['jeton_csrf'])
        && hash_equals($_SESSION['jeton_csrf'], $jeton);
}
