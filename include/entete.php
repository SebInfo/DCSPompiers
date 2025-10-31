<?php

declare(strict_types=1);

// --- Initialisation de la session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Gestion du login ---
$_SESSION['login'] = $_SESSION['login'] ?? false;

// --- Paramètres régionaux ---
date_default_timezone_set('Europe/Paris');

// Formatage de la date avec IntlDateFormatter (plus moderne que strftime)
$formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::SHORT
);
$formatter->setPattern("'Dernière connexion' EEEE d MMMM y 'à' HH'h'MM");

// Cookie valable 30 minutes
setcookie('visite', $formatter->format(time()), [
    'expires' => time() + 1800,
    'path' => '/',
    'secure' => false, // true en HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

// --- Inclusion des fichiers nécessaires ---
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/mesFonctions.php';
require_once __DIR__ . '/svg.php';

?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Sébastien Inion">
    <title>SDIS</title>

    <!-- CSS Bootstrap -->
    <link 
      rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" 
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" 
      crossorigin="anonymous"
    >

    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <header>
      <?php require __DIR__ . '/menu.php'; ?>
    </header>
