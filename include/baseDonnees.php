<?php

$dotenv = parse_ini_file(__DIR__ . '/.env');
$clesRequises = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS'];

if ($dotenv === false || array_diff($clesRequises, array_keys($dotenv))) {
    error_log('Configuration de base de données absente ou incomplète.');
    http_response_code(500);
    exit('Configuration de la base de données indisponible.');
}

$hote       = $dotenv['DB_HOST'];
$port       = $dotenv['DB_PORT'];
$bd         = $dotenv['DB_NAME'];
$login      = $dotenv['DB_USER'];
$motDePasse = $dotenv['DB_PASS'];

// La base active est celle indiquée par DB_NAME dans le fichier .env.
// Les requêtes peuvent donc utiliser directement les noms des tables.
$dsn = "mysql:host=$hote;port=$port;dbname=$bd;charset=utf8mb4";

try {
    $db = new PDO($dsn, $login, $motDePasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage()); // consigne l’erreur dans les logs du serveur
    http_response_code(500);
    exit('Erreur de connexion à la base de données.');
}
