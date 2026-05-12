<?php

function uploadFile(array $file, string $targetDir, array $allowedMime = [], int $maxSize = 5 * 1024 * 1024)
{
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) return null;

    if ($file['size'] > $maxSize) return null;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!empty($allowedMime) && !in_array($mime, $allowedMime)) return null;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = bin2hex(random_bytes(8)) . '.' . $ext;

    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $destination = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeName;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $safeName;
    }
    return null;
}

function resolvePhotoPath(?string $photo): ?string
{
    if (empty($photo)) {
        return null;
    }

    $filename = basename($photo);
    return '/index.php?r=media/image&file=' . rawurlencode($filename);
}
