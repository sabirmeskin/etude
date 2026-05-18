<?php

require_once __DIR__ . '/../config/database.php';

class Devoir
{
    public static function forClass(int $classId): array
    {
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT d.*, m.nom AS matiere_nom, u.nom AS auteur_nom
             FROM devoirs d
             JOIN matieres m ON d.matiere_id = m.id
             JOIN utilisateurs u ON d.created_by = u.id
             WHERE d.classe_id = ?
             ORDER BY d.date_limite IS NULL, d.date_limite ASC, d.created_at DESC'
        );
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public static function forTeacherClasses(array $classIds): array
    {
        if (empty($classIds)) {
            return [];
        }
        $pdo = db();
        $placeholders = implode(',', array_fill(0, count($classIds), '?'));
        $stmt = $pdo->prepare(
            "SELECT d.*, m.nom AS matiere_nom, c.nom AS classe_nom
             FROM devoirs d
             JOIN matieres m ON d.matiere_id = m.id
             JOIN classes c ON d.classe_id = c.id
             WHERE d.classe_id IN ($placeholders)
             ORDER BY d.created_at DESC"
        );
        $stmt->execute($classIds);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = db();
        $stmt = $pdo->prepare(
            'INSERT INTO devoirs (classe_id, matiere_id, titre, consigne, date_limite, created_by)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            (int) $data['classe_id'],
            (int) $data['matiere_id'],
            trim($data['titre'] ?? ''),
            trim($data['consigne'] ?? '') ?: null,
            !empty($data['date_limite']) ? $data['date_limite'] : null,
            (int) $data['created_by'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM devoirs WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function allOrdered(): array
    {
        $pdo = db();
        $sql = 'SELECT d.*, m.nom AS matiere_nom, c.nom AS classe_nom
                FROM devoirs d
                JOIN matieres m ON d.matiere_id = m.id
                JOIN classes c ON d.classe_id = c.id
                ORDER BY d.created_at DESC';
        return $pdo->query($sql)->fetchAll();
    }

    public static function forProfessorAssignments(int $userId): array
    {
        require_once __DIR__ . '/ProfessorAssignment.php';
        if (!ProfessorAssignment::tableExists()) {
            return [];
        }
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT d.*, m.nom AS matiere_nom, c.nom AS classe_nom
             FROM devoirs d
             INNER JOIN professeur_matiere_classe p
               ON p.utilisateur_id = ? AND p.classe_id = d.classe_id AND p.matiere_id = d.matiere_id
             JOIN matieres m ON d.matiere_id = m.id
             JOIN classes c ON d.classe_id = c.id
             ORDER BY d.created_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM devoirs WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
