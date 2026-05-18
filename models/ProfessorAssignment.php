<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/User.php';

class ProfessorAssignment
{
    public static function tableExists(): bool
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS c FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
        );
        $stmt->execute(['professeur_matiere_classe']);
        $cache = (int) ($stmt->fetch()['c'] ?? 0) > 0;

        return $cache;
    }

    /** @return array<int, array<string, mixed>> */
    public static function allForTeacher(int $userId): array
    {
        if (!self::tableExists()) {
            return [];
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT p.id, p.classe_id, p.matiere_id, c.nom AS classe_nom, m.nom AS matiere_nom
             FROM professeur_matiere_classe p
             JOIN classes c ON p.classe_id = c.id
             JOIN matieres m ON p.matiere_id = m.id
             WHERE p.utilisateur_id = ?
             ORDER BY c.nom, m.nom'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public static function add(int $userId, int $classeId, int $matiereId): bool
    {
        if (!self::tableExists() || $userId < 1 || $classeId < 1 || $matiereId < 1) {
            return false;
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO professeur_matiere_classe (utilisateur_id, classe_id, matiere_id) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $classeId, $matiereId]);
        self::syncDerivedProfessorClasses($userId);

        return true;
    }

    public static function remove(int $assignmentId, int $expectedUserId): bool
    {
        if (!self::tableExists() || $assignmentId < 1) {
            return false;
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'DELETE FROM professeur_matiere_classe WHERE id = ? AND utilisateur_id = ?'
        );
        $stmt->execute([$assignmentId, $expectedUserId]);
        $ok = $stmt->rowCount() > 0;
        if ($ok) {
            self::syncDerivedProfessorClasses($expectedUserId);
        }

        return $ok;
    }

    /** @return int[] */
    public static function distinctClassIds(int $userId): array
    {
        if (!self::tableExists()) {
            return [];
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT DISTINCT classe_id FROM professeur_matiere_classe WHERE utilisateur_id = ?'
        );
        $stmt->execute([$userId]);

        return array_map('intval', array_column($stmt->fetchAll(), 'classe_id'));
    }

    /** @return int[] */
    public static function matiereIdsForTeacherClass(int $userId, int $classId): array
    {
        if (!self::tableExists()) {
            return [];
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT matiere_id FROM professeur_matiere_classe WHERE utilisateur_id = ? AND classe_id = ?'
        );
        $stmt->execute([$userId, $classId]);

        return array_map('intval', array_column($stmt->fetchAll(), 'matiere_id'));
    }

    public static function exists(int $userId, int $classeId, int $matiereId): bool
    {
        if (!self::tableExists()) {
            return false;
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT 1 FROM professeur_matiere_classe WHERE utilisateur_id = ? AND classe_id = ? AND matiere_id = ? LIMIT 1'
        );
        $stmt->execute([$userId, $classeId, $matiereId]);

        return (bool) $stmt->fetch();
    }

    public static function countForTeacher(int $userId): int
    {
        if (!self::tableExists()) {
            return 0;
        }
        $pdo = db();
        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM professeur_matiere_classe WHERE utilisateur_id = ?');
        $stmt->execute([$userId]);

        return (int) ($stmt->fetch()['c'] ?? 0);
    }

    /** Met a jour professeur_classe (classes distinctes) pour compatibilite avec le reste du code. */
    public static function syncDerivedProfessorClasses(int $userId): void
    {
        if (!self::tableExists()) {
            return;
        }
        $ids = self::distinctClassIds($userId);
        User::setTeacherClasses($userId, $ids);
    }
}
