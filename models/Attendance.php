<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Student.php';

class Attendance
{
    public static function summaryForDate($date)
    {
        $pdo = db();
        $stmt = $pdo->prepare('
            SELECT
                SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN present = 0 THEN 1 ELSE 0 END) as absent_count,
                COUNT(*) as total_count
            FROM presences
            WHERE date = ?
        ');
        $stmt->execute([$date]);
        return $stmt->fetch() ?: ['present_count' => 0, 'absent_count' => 0, 'total_count' => 0];
    }

    public static function summaryForClassAndDate($classId, $date)
    {
        $pdo = db();
        $stmt = $pdo->prepare('
            SELECT
                SUM(CASE WHEN COALESCE(p.present, 0) = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN COALESCE(p.present, 0) = 0 THEN 1 ELSE 0 END) as absent_count,
                COUNT(e.id) as total_count
            FROM etudiants e
            LEFT JOIN presences p ON p.etudiant_id = e.id AND p.date = ?
            WHERE e.classe_id = ?
        ');
        $stmt->execute([$date, $classId]);
        return $stmt->fetch() ?: ['present_count' => 0, 'absent_count' => 0, 'total_count' => 0];
    }

    public static function rosterByClassAndDate($classId, $date)
    {
        $pdo = db();
        $stmt = $pdo->prepare('
            SELECT
                e.id,
                e.nom,
                e.prenom,
                e.email,
                COALESCE(p.present, 0) as present,
                p.date
            FROM etudiants e
            LEFT JOIN presences p ON p.etudiant_id = e.id AND p.date = ?
            WHERE e.classe_id = ?
            ORDER BY e.nom, e.prenom
        ');
        $stmt->execute([$date, $classId]);
        return $stmt->fetchAll();
    }

    public static function saveForClassAndDate($classId, $date, array $attendance)
    {
        $pdo = db();
        $students = Student::byClass($classId);
        $stmt = $pdo->prepare('INSERT INTO presences (etudiant_id, date, present) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE present = VALUES(present)');

        foreach ($students as $student) {
            $present = isset($attendance[$student['id']]) ? 1 : 0;
            $stmt->execute([$student['id'], $date, $present]);
        }
    }

    public static function byStudent($studentId, $limit = 10)
    {
        $pdo = db();
        $limit = (int) $limit;
        $stmt = $pdo->prepare('SELECT * FROM presences WHERE etudiant_id = ? ORDER BY date DESC LIMIT ' . $limit);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function statsForStudent($studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('
            SELECT
                SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN present = 0 THEN 1 ELSE 0 END) as absent_count,
                COUNT(*) as total_count
            FROM presences
            WHERE etudiant_id = ?
        ');
        $stmt->execute([$studentId]);
        return $stmt->fetch() ?: ['present_count' => 0, 'absent_count' => 0, 'total_count' => 0];
    }
}