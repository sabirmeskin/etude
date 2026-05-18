<?php

require_once __DIR__ . '/../config/database.php';

class PasswordReset
{
    /** Cree un jeton valable 1h ; retourne le jeton en clair (a mettre dans l URL ou l email). */
    public static function createForUser(int $userId): string
    {
        $pdo = db();
        $pdo->prepare('DELETE FROM password_resets WHERE utilisateur_id = ?')->execute([$userId]);
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + 3600);
        $stmt = $pdo->prepare(
            'INSERT INTO password_resets (utilisateur_id, token_hash, expires_at) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $hash, $expires]);

        return $token;
    }

    public static function findUserIdByPlainToken(string $plainToken): ?int
    {
        $plainToken = trim($plainToken);
        if (strlen($plainToken) < 32) {
            return null;
        }
        $hash = hash('sha256', $plainToken);
        $pdo = db();
        $stmt = $pdo->prepare(
            'SELECT utilisateur_id FROM password_resets WHERE token_hash = ? AND expires_at > NOW() LIMIT 1'
        );
        $stmt->execute([$hash]);
        $row = $stmt->fetch();

        return $row ? (int) $row['utilisateur_id'] : null;
    }

    public static function deleteForUser(int $userId): void
    {
        $pdo = db();
        $pdo->prepare('DELETE FROM password_resets WHERE utilisateur_id = ?')->execute([$userId]);
    }
}
