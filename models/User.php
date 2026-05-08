<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    public static function findByEmail(string $email)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
