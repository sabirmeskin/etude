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

    public static function average($studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT AVG(note) as avg_note FROM notes WHERE etudiant_id = ?');
        $stmt->execute([$studentId]);
        $row = $stmt->fetch();
        return $row && $row['avg_note'] !== null ? round($row['avg_note'], 2) : null;
    }
}
