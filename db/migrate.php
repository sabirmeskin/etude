<?php
/**
 * Migrations idempotentes (colonnes / tables manquantes).
 * Exécuter : php db/migrate.php
 * Ou appele depuis db/reset.php avec la connexion PDO existante.
 */
require_once __DIR__ . '/../config/database.php';

function columnExists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
    );
    $stmt->execute([$table, $column]);
    return (int) ($stmt->fetch()['c'] ?? 0) > 0;
}

function tableExists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) AS c FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $stmt->execute([$table]);
    return (int) ($stmt->fetch()['c'] ?? 0) > 0;
}

function run_schema_migrations(PDO $pdo): void
{
    if (!columnExists($pdo, 'utilisateurs', 'etudiant_id')) {
        $pdo->exec('ALTER TABLE utilisateurs ADD COLUMN etudiant_id INT NULL DEFAULT NULL AFTER role');
        echo "Colonne utilisateurs.etudiant_id ajoutee.\n";
    }

    if (columnExists($pdo, 'utilisateurs', 'etudiant_id')) {
        try {
            $pdo->exec(
                'ALTER TABLE utilisateurs ADD CONSTRAINT fk_utilisateur_etudiant
                 FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE SET NULL'
            );
            echo "Contrainte fk_utilisateur_etudiant ajoutee.\n";
        } catch (Throwable $e) {
            // deja presente ou impossible
        }
    }

    if (!tableExists($pdo, 'professeur_classe')) {
        $pdo->exec(
            'CREATE TABLE professeur_classe (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT NOT NULL,
                classe_id INT NOT NULL,
                UNIQUE KEY uq_prof_classe (utilisateur_id, classe_id),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
                FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        echo "Table professeur_classe creee.\n";
    }

    if (!tableExists($pdo, 'devoirs')) {
        $pdo->exec(
            'CREATE TABLE devoirs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                classe_id INT NOT NULL,
                matiere_id INT NOT NULL,
                titre VARCHAR(255) NOT NULL,
                consigne TEXT,
                date_limite DATE NULL,
                created_by INT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE,
                FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES utilisateurs(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        echo "Table devoirs creee.\n";
    }

    if (!tableExists($pdo, 'password_resets')) {
        $pdo->exec(
            'CREATE TABLE password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT NOT NULL,
                token_hash CHAR(64) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_password_reset_token (token_hash),
                KEY idx_password_reset_user (utilisateur_id),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        echo "Table password_resets creee.\n";
    }

    if (!tableExists($pdo, 'professeur_matiere_classe')) {
        $pdo->exec(
            'CREATE TABLE professeur_matiere_classe (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT NOT NULL,
                classe_id INT NOT NULL,
                matiere_id INT NOT NULL,
                UNIQUE KEY uq_prof_classe_matiere (utilisateur_id, classe_id, matiere_id),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
                FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE,
                FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
        echo "Table professeur_matiere_classe creee.\n";
    }

    $pdo->exec("UPDATE utilisateurs SET role = 'professeur' WHERE role = 'user'");
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string) $_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    try {
        run_schema_migrations(db());
        echo "Migration terminee.\n";
        exit(0);
    } catch (Throwable $e) {
        echo 'Erreur migration: ' . $e->getMessage() . "\n";
        exit(1);
    }
}
