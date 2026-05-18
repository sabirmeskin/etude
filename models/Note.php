<?php
require_once __DIR__ . '/../config/database.php';

class Note
{
    public static function create(array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?)');
        return $stmt->execute([$data['student_id'], $data['matiere_id'], $data['note']]);
    }

    public static function byStudent($studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT notes.*, matieres.nom as matiere_nom FROM notes JOIN matieres ON notes.matiere_id = matieres.id WHERE notes.etudiant_id = ? ORDER BY notes.id DESC');
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function byStudentSearch($studentId, $keyword = '')
    {
        $pdo = db();
        $keyword = trim($keyword);
        if ($keyword === '') {
            return self::byStudent($studentId);
        }

        $like = '%' . $keyword . '%';
        $stmt = $pdo->prepare('SELECT notes.*, matieres.nom as matiere_nom FROM notes JOIN matieres ON notes.matiere_id = matieres.id WHERE notes.etudiant_id = ? AND matieres.nom LIKE ? ORDER BY notes.id DESC');
        $stmt->execute([$studentId, $like]);
        return $stmt->fetchAll();
    }

    public static function paginateByStudent($studentId, $page = 1, $perPage = 5, $keyword = '', ?array $matiereIdsOnly = null)
    {
        $pdo = db();
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;
        $keyword = trim($keyword);

        $where = 'WHERE notes.etudiant_id = ?';
        $params = [$studentId];
        if ($keyword !== '') {
            $where .= ' AND matieres.nom LIKE ?';
            $params[] = '%' . $keyword . '%';
        }
        if ($matiereIdsOnly !== null) {
            if ($matiereIdsOnly === []) {
                return [
                    'data' => [],
                    'total' => 0,
                    'pages' => 1,
                    'page' => $page,
                    'perPage' => $perPage,
                ];
            }
            $placeholders = implode(',', array_fill(0, count($matiereIdsOnly), '?'));
            $where .= ' AND notes.matiere_id IN (' . $placeholders . ')';
            foreach ($matiereIdsOnly as $mid) {
                $params[] = (int) $mid;
            }
        }

        $countSql = 'SELECT COUNT(*) as total FROM notes JOIN matieres ON notes.matiere_id = matieres.id ' . $where;
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $sql = 'SELECT notes.*, matieres.nom as matiere_nom FROM notes JOIN matieres ON notes.matiere_id = matieres.id ' . $where . ' ORDER BY notes.id DESC LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => (int) ceil($total / $perPage),
            'page' => $page,
            'perPage' => $perPage,
        ];
    }

    public static function average($studentId, ?array $matiereIdsOnly = null)
    {
        $pdo = db();
        $where = 'WHERE etudiant_id = ?';
        $params = [$studentId];
        if ($matiereIdsOnly !== null) {
            if ($matiereIdsOnly === []) {
                return null;
            }
            $placeholders = implode(',', array_fill(0, count($matiereIdsOnly), '?'));
            $where .= ' AND matiere_id IN (' . $placeholders . ')';
            foreach ($matiereIdsOnly as $mid) {
                $params[] = (int) $mid;
            }
        }
        $stmt = $pdo->prepare('SELECT AVG(note) as avg_note FROM notes ' . $where);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row && $row['avg_note'] !== null ? round($row['avg_note'], 2) : null;
    }

    public static function countAll()
    {
        $pdo = db();
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM notes');
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public static function createBulk(array $grades)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE note = VALUES(note)');
        
        $count = 0;
        foreach ($grades as $grade) {
            if (!empty($grade['student_id']) && !empty($grade['matiere_id']) && isset($grade['note'])) {
                $stmt->execute([
                    $grade['student_id'],
                    $grade['matiere_id'],
                    (float) $grade['note']
                ]);
                $count++;
            }
        }
        return $count;
    }

    public static function getByStudentAndSubject($studentId, $subjectId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM notes WHERE etudiant_id = ? AND matiere_id = ?');
        $stmt->execute([$studentId, $subjectId]);
        return $stmt->fetch();
    }
}
