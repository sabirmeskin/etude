<?php
// Script d'initialisation : drop + import du schéma SQL
require_once __DIR__ . '/../config/database.php';

$schemaFile = __DIR__ . '/schema.sql';
if (!file_exists($schemaFile)) {
    echo "Fichier schema non trouvé: $schemaFile\n";
    exit(1);
}

$schema = file_get_contents($schemaFile);
if ($schema === false) {
    echo "Impossible de lire le fichier schema.sql\n";
    exit(1);
}

$dsn = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "Erreur de connexion MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

// Exécuter chaque instruction séparément
$stmts = array_filter(array_map('trim', explode(';', $schema)));
try {
    foreach ($stmts as $sql) {
        if ($sql === '') continue;
        $pdo->exec($sql);
    }
    echo "Schéma importé avec succès.\n";
    exit(0);
} catch (Exception $e) {
    echo "Erreur lors de l'import du schéma: " . $e->getMessage() . "\n";
    exit(1);
}
