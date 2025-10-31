<?php
$dotenv = parse_ini_file(__DIR__ . '/.env');

$hote       = $dotenv['DB_HOST'];
$port       = $dotenv['DB_PORT'];
$bd         = $dotenv['DB_NAME'];
$login      = $dotenv['DB_USER'];
$motDePasse = $dotenv['DB_PASS'];

$dsn = "mysql:host=$hote;port=$port;dbname=$bd;charset=utf8";

try {
    $db = new PDO($dsn, $login, $motDePasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage()); // consigne l’erreur dans les logs du serveur
    echo "<div class='alert alert-danger'>Erreur de connexion à la base de données.</div>";
}
