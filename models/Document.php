<?php
require_once __DIR__ . '/../config/database.php';

class Document
{
    public static function create($studentId, $filename, $path)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO documents (etudiant_id, filename, path) VALUES (?, ?, ?)');
        return $stmt->execute([$studentId, $filename, $path]);
    }

    public static function byStudent($studentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM documents WHERE etudiant_id = ? ORDER BY uploaded_at DESC');
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function get($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM documents WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function delete($id)
    {
        $doc = self::get($id);
        if (!$doc) return false;

        // attempt to remove the file from disk if it's inside uploads
        $publicDir = realpath(__DIR__ . '/../public');
        $filePath = $publicDir . $doc['path'];
        $uploadsBase = realpath(__DIR__ . '/../public/uploads');
        $realFile = realpath($filePath);
        if ($realFile && $uploadsBase && strpos($realFile, $uploadsBase) === 0) {
            @unlink($realFile);
        }

        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM documents WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
