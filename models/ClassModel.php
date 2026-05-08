<?php
require_once __DIR__ . '/../config/database.php';

class ClassModel
{
    public static function all()
    {
        $pdo = db();
        $stmt = $pdo->query('SELECT * FROM classes ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create(array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO classes (nom) VALUES (?)');
        $stmt->execute([$data['nom']]);
        return $pdo->lastInsertId();
    }

    public static function assignStudent($classId, $studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE etudiants SET classe_id = ? WHERE id = ?');
        return $stmt->execute([$classId, $studentId]);
    }
}
