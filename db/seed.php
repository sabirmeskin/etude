<?php
require_once __DIR__ . '/../config/database.php';

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "Erreur de connexion MySQL: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE presences');
$pdo->exec('TRUNCATE TABLE emploi_du_temps');
$pdo->exec('TRUNCATE TABLE notes');
$pdo->exec('TRUNCATE TABLE etudiants');
$pdo->exec('TRUNCATE TABLE matieres');
$pdo->exec('TRUNCATE TABLE classes');
$pdo->exec('TRUNCATE TABLE utilisateurs');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$classes = ['1ère Année Collège', '2ème Année Collège', '3ème Année Collège'];
$classIds = [];
$stmt = $pdo->prepare('INSERT INTO classes (nom) VALUES (?)');
foreach ($classes as $className) {
    $stmt->execute([$className]);
    $classIds[$className] = (int) $pdo->lastInsertId();
}

$subjects = [
    ['Mathématiques', 'Algèbre, géométrie et calcul'],
    ['Français', 'Lecture, écriture et grammaire'],
    ['Histoire-Géographie', 'Repères historiques et géographiques'],
    ['Sciences', 'Expériences et observation du vivant'],
];
$subjectIds = [];
$stmt = $pdo->prepare('INSERT INTO matieres (nom, description) VALUES (?, ?)');
foreach ($subjects as [$name, $description]) {
    $stmt->execute([$name, $description]);
    $subjectIds[$name] = (int) $pdo->lastInsertId();
}

$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->prepare('INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)')
    ->execute(['Admin', 'admin@local', $adminPassword, 'admin']);

$samplePhotos = [];
$photoFiles = glob(__DIR__ . '/../storage/uploads/images/*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}', GLOB_BRACE) ?: [];
sort($photoFiles);
foreach (array_slice($photoFiles, 0, 3) as $photoFile) {
    $samplePhotos[] = basename($photoFile);
}

$students = [
    ['nom' => 'El Amrani', 'prenom' => 'Youssef', 'email' => 'youssef@example.com', 'classe_id' => $classIds['1ère Année Collège']],
    ['nom' => 'Bennani', 'prenom' => 'Amina', 'email' => 'amina@example.com', 'classe_id' => $classIds['2ème Année Collège']],
    ['nom' => 'Alaoui', 'prenom' => 'Mehdi', 'email' => 'mehdi@example.com', 'classe_id' => $classIds['3ème Année Collège']],
    ['nom' => 'Zerouali', 'prenom' => 'Salma', 'email' => 'salma@example.com', 'classe_id' => $classIds['1ère Année Collège']],
    ['nom' => 'Idrissi', 'prenom' => 'Rayan', 'email' => 'rayan@example.com', 'classe_id' => $classIds['2ème Année Collège']],
    ['nom' => 'Fassi', 'prenom' => 'Imane', 'email' => 'imane@example.com', 'classe_id' => $classIds['3ème Année Collège']],
    ['nom' => 'Mansouri', 'prenom' => 'Hamza', 'email' => 'hamza@example.com', 'classe_id' => $classIds['1ère Année Collège']],
];
$studentIds = [];
$stmt = $pdo->prepare('INSERT INTO etudiants (nom, prenom, email, classe_id, photo) VALUES (?, ?, ?, ?, ?)');
foreach ($students as $index => $student) {
    $photoPath = $samplePhotos[$index] ?? null;
    $stmt->execute([$student['nom'], $student['prenom'], $student['email'], $student['classe_id'], $photoPath]);
    $studentIds[] = (int) $pdo->lastInsertId();
}

$notes = [
    [$studentIds[0], $subjectIds['Mathématiques'], 17],
    [$studentIds[0], $subjectIds['Français'], 15],
    [$studentIds[0], $subjectIds['Sciences'], 16],
    [$studentIds[1], $subjectIds['Mathématiques'], 14],
    [$studentIds[1], $subjectIds['Histoire-Géographie'], 15],
    [$studentIds[2], $subjectIds['Français'], 18],
    [$studentIds[2], $subjectIds['Sciences'], 17],
    [$studentIds[3], $subjectIds['Mathématiques'], 13],
    [$studentIds[3], $subjectIds['Sciences'], 15],
    [$studentIds[4], $subjectIds['Français'], 16],
    [$studentIds[4], $subjectIds['Histoire-Géographie'], 14],
    [$studentIds[5], $subjectIds['Mathématiques'], 11],
    [$studentIds[5], $subjectIds['Sciences'], 15],
    [$studentIds[6], $subjectIds['Français'], 14],
    [$studentIds[6], $subjectIds['Mathématiques'], 18],
];
$stmt = $pdo->prepare('INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?)');
foreach ($notes as [$studentId, $subjectId, $note]) {
    $stmt->execute([$studentId, $subjectId, $note]);
}

$schedules = [
    [$classIds['1ère Année Collège'], $subjectIds['Mathématiques'], 'Lundi', '08:00:00', '09:00:00'],
    [$classIds['1ère Année Collège'], $subjectIds['Français'], 'Lundi', '09:00:00', '10:00:00'],
    [$classIds['2ème Année Collège'], $subjectIds['Histoire-Géographie'], 'Mardi', '10:00:00', '11:00:00'],
    [$classIds['3ème Année Collège'], $subjectIds['Sciences'], 'Mercredi', '13:00:00', '14:00:00'],
];
$stmt = $pdo->prepare('INSERT INTO emploi_du_temps (classe_id, matiere_id, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?)');
foreach ($schedules as $schedule) {
    $stmt->execute($schedule);
}

$attendance = [
    [$studentIds[0], '2026-05-09', 1],
    [$studentIds[1], '2026-05-09', 1],
    [$studentIds[2], '2026-05-09', 0],
    [$studentIds[3], '2026-05-09', 1],
    [$studentIds[4], '2026-05-09', 0],
    [$studentIds[5], '2026-05-09', 1],
    [$studentIds[6], '2026-05-09', 1],
];
$stmt = $pdo->prepare('INSERT INTO presences (etudiant_id, date, present) VALUES (?, ?, ?)');
foreach ($attendance as $row) {
    $stmt->execute($row);
}

echo "Données de démonstration insérées avec succès.\n";
