<?php
require_once __DIR__ . '/../config/database.php';

class Subject
{
    public static function all()
    {
        $pdo = db();
        $stmt = $pdo->query('SELECT * FROM matieres ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM matieres WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create(array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO matieres (nom, description) VALUES (?, ?)');
        $stmt->execute([$data['nom'], $data['description'] ?? null]);
        return $pdo->lastInsertId();
    }

    public static function update($id, array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE matieres SET nom = ?, description = ? WHERE id = ?');
        return $stmt->execute([$data['nom'], $data['description'] ?? null, $id]);
    }

    public static function delete($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM matieres WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
