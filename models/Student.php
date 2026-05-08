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

    public static function find($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
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
}
