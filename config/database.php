<?php
// configuration de la base de données (PDO)
// Modifier ces constantes selon votre environnement
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'mini_erp');
define('DB_USER', 'root');
define('DB_PASS', '');

// Sans envoi d email (SMTP) : afficher le lien de reinitialisation sur la page apres la demande. Mettre false en production.
if (!defined('PASSWORD_RESET_SHOW_LINK')) {
    define('PASSWORD_RESET_SHOW_LINK', true);
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}
