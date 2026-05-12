<?php
require_once __DIR__ . '/BaseController.php';

class MediaController extends BaseController
{
    public function image()
    {
        if (empty($_SESSION['user'])) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }

        $file = basename($_GET['file'] ?? '');
        if ($file === '') {
            http_response_code(404);
            echo 'Image introuvable';
            exit;
        }

        $baseDir = realpath(__DIR__ . '/../storage/uploads/images');
        $path = $baseDir ? $baseDir . DIRECTORY_SEPARATOR . $file : null;
        $realPath = $path ? realpath($path) : false;

        if (!$baseDir || !$realPath || strpos($realPath, $baseDir) !== 0 || !is_file($realPath)) {
            http_response_code(404);
            echo 'Image introuvable';
            exit;
        }

        $mime = mime_content_type($realPath) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($realPath));
        readfile($realPath);
        exit;
    }
}