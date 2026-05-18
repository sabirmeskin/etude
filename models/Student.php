<?php
require_once __DIR__ . '/../config/database.php';

class Student
{
    public static function all()
    {
        $pdo = db();
        $stmt = $pdo->query('SELECT * FROM etudiants ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function search($keyword)
    {
        $pdo = db();
        $keyword = '%' . $keyword . '%';
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? ORDER BY id DESC');
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    public static function paginate($page = 1, $perPage = 10, $keyword = '')
    {
        $pdo = db();
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];
        if ($keyword !== '') {
            $where = 'WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ?';
            $like = '%' . $keyword . '%';
            $params = [$like, $like, $like];
        }

        $countStmt = $pdo->prepare('SELECT COUNT(*) as total FROM etudiants ' . $where);
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $sql = 'SELECT * FROM etudiants ' . $where . ' ORDER BY id DESC LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;
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

    public static function byClass($classId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE classe_id = ? ORDER BY nom, prenom');
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /** Recherche insensible a la casse pour lier un compte eleve. */
    public static function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));
        if ($email === '') {
            return null;
        }
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE LOWER(TRIM(email)) = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function create(array $data)
    {
        $pdo = db();
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $email = trim($data['email'] ?? null);
        $photo = $data['photo'] ?? null;

        $stmt = $pdo->prepare('INSERT INTO etudiants (nom, prenom, email, photo) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nom, $prenom, $email, $photo]);
        return $pdo->lastInsertId();
    }

    public static function update($id, array $data)
    {
        $pdo = db();
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $email = trim($data['email'] ?? null);
        $photo = $data['photo'] ?? null;

        $stmt = $pdo->prepare('UPDATE etudiants SET nom = ?, prenom = ?, email = ?, photo = ? WHERE id = ?');
        return $stmt->execute([$nom, $prenom, $email, $photo, $id]);
    }

    public static function delete($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM etudiants WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function getGradeStats($studentId, ?array $matiereIdsOnly = null)
    {
        $pdo = db();
        $extra = '';
        $params = [$studentId];
        if ($matiereIdsOnly !== null) {
            if ($matiereIdsOnly === []) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($matiereIdsOnly), '?'));
            $extra = ' AND n.matiere_id IN (' . $placeholders . ')';
            foreach ($matiereIdsOnly as $mid) {
                $params[] = (int) $mid;
            }
        }
        $stmt = $pdo->prepare('
            SELECT 
                m.id AS matiere_id,
                m.nom as matiere,
                COUNT(n.id) as count,
                AVG(n.note) as moyenne,
                MAX(n.note) as max_note,
                MIN(n.note) as min_note
            FROM notes n
            LEFT JOIN matieres m ON n.matiere_id = m.id
            WHERE n.etudiant_id = ?' . $extra . '
            GROUP BY n.matiere_id, m.id, m.nom
            ORDER BY m.nom
        ');
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getOverallAverage($studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT AVG(note) as moyenne FROM notes WHERE etudiant_id = ?');
        $stmt->execute([$studentId]);
        $result = $stmt->fetch();
        return round($result['moyenne'] ?? 0, 2);
    }

    public static function getClassStats($classId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('
            SELECT 
                e.id,
                e.nom,
                e.prenom,
                AVG(n.note) as moyenne
            FROM etudiants e
            LEFT JOIN notes n ON e.id = n.etudiant_id
            WHERE e.classe_id = ?
            GROUP BY e.id
            ORDER BY (moyenne IS NULL), moyenne DESC, e.nom
        ');
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public static function countByClass($classId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM etudiants WHERE classe_id = ?');
        $stmt->execute([$classId]);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public static function importFromCsv(string $tmpFilePath, array $options = [])
    {
        $pdo = db();
        $handle = fopen($tmpFilePath, 'r');
        if (!$handle) return ['success' => 0, 'errors' => ['unable_to_open_file']];

        $header = null;
        $insertStmt = $pdo->prepare('INSERT INTO etudiants (nom, prenom, email, classe_id, photo) VALUES (?, ?, ?, ?, ?)');
        $success = 0;
        $errors = [];

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            if ($header === null) {
                $header = array_map('trim', $row);
                continue;
            }

            $data = array_combine($header, $row);
            if ($data === false) {
                $errors[] = 'invalid_row';
                continue;
            }

            $nom = trim($data['nom'] ?? $data['name'] ?? '');
            $prenom = trim($data['prenom'] ?? $data['firstname'] ?? '');
            $email = trim($data['email'] ?? '');
            $classe = !empty($data['classe_id']) ? (int)$data['classe_id'] : (isset($data['classe']) ? (int)$data['classe'] : null);

            if ($nom === '' || $prenom === '') {
                $errors[] = 'missing_name';
                continue;
            }

            try {
                $insertStmt->execute([$nom, $prenom, $email ?: null, $classe ?: null, null]);
                $success++;
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        fclose($handle);

        return ['success' => $success, 'errors' => $errors];
    }

    public static function exportCsv()
    {
        $pdo = db();
        $stmt = $pdo->query('SELECT id, nom, prenom, email, classe_id FROM etudiants ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        $output = fopen('php://memory', 'r+');
        fputcsv($output, ['id','nom','prenom','email','classe_id']);
        foreach ($rows as $r) {
            fputcsv($output, [$r['id'], $r['nom'], $r['prenom'], $r['email'], $r['classe_id']]);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
