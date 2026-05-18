<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    public static function findByEmail(string $email)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findById(int $id): ?array
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $name, string $email, string $password, string $role = 'user', ?int $etudiantId = null): int
    {
        $pdo = db();
        if (self::columnEtudiantIdExists($pdo)) {
            $stmt = $pdo->prepare(
                'INSERT INTO utilisateurs (nom, email, password, role, etudiant_id) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role,
                $etudiantId,
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role,
            ]);
        }

        return (int) $pdo->lastInsertId();
    }

    private static function columnEtudiantIdExists(PDO $pdo): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS c FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
        );
        $stmt->execute(['utilisateurs', 'etudiant_id']);
        $cache = (int) ($stmt->fetch()['c'] ?? 0) > 0;

        return $cache;
    }

    /** @return int[] */
    public static function teacherClassIds(int $userId): array
    {
        $pdo = db();
        if (!self::tableProfesseurClasseExists($pdo)) {
            return [];
        }
        $stmt = $pdo->prepare('SELECT classe_id FROM professeur_classe WHERE utilisateur_id = ?');
        $stmt->execute([$userId]);
        return array_map('intval', array_column($stmt->fetchAll(), 'classe_id'));
    }

    private static function tableProfesseurClasseExists(PDO $pdo): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS c FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
        );
        $stmt->execute(['professeur_classe']);
        $cache = (int) ($stmt->fetch()['c'] ?? 0) > 0;

        return $cache;
    }

    public static function setTeacherClasses(int $userId, array $classIds): void
    {
        $pdo = db();
        $pdo->prepare('DELETE FROM professeur_classe WHERE utilisateur_id = ?')->execute([$userId]);
        $stmt = $pdo->prepare('INSERT INTO professeur_classe (utilisateur_id, classe_id) VALUES (?, ?)');
        foreach ($classIds as $cid) {
            $cid = (int) $cid;
            if ($cid > 0) {
                $stmt->execute([$userId, $cid]);
            }
        }
    }

    /** @return array<int, array<string, mixed>> */
    public static function allProfesseurs(): array
    {
        $pdo = db();
        $stmt = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'professeur' ORDER BY nom, email");

        return $stmt->fetchAll();
    }

    public static function countUsersLinkedToStudent(int $etudiantId): int
    {
        if (!self::columnEtudiantIdExists(db())) {
            return 0;
        }
        $pdo = db();
        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM utilisateurs WHERE etudiant_id = ?');
        $stmt->execute([$etudiantId]);
        return (int) ($stmt->fetch()['c'] ?? 0);
    }

    public static function updatePassword(int $userId, string $plainPassword): void
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE utilisateurs SET password = ? WHERE id = ?');
        $stmt->execute([password_hash($plainPassword, PASSWORD_DEFAULT), $userId]);
    }
}

