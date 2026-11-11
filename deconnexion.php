<?php

require_once __DIR__ . '/include/controleAcces.php';
demarrerSession();

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
