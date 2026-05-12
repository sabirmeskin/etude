<?php
require_once __DIR__ . '/../config/database.php';

class Announcement
{
    public static function latest($limit = 5)
    {
        $pdo = db();
        $limit = (int) $limit;
        $stmt = $pdo->query('SELECT * FROM annonces ORDER BY date_publication DESC LIMIT ' . $limit);
        return $stmt->fetchAll();
    }

    public static function create(array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO annonces (titre, contenu, createur) VALUES (?, ?, ?)');
        return $stmt->execute([
            $data['titre'],
            $data['contenu'],
            $data['createur'] ?? 'Admin'
        ]);
    }

    public static function delete($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM annonces WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
