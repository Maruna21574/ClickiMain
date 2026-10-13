<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    redirect('/admin/blog.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = db()->prepare('SELECT cover_image FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $cover = (string)$stmt->fetchColumn();
    if ($cover !== '' && str_starts_with($cover, UPLOADS_URL . '/')) {
        $file = SITE_ROOT . $cover;
        if (is_file($file)) {
            @unlink($file);
        }
    }
    db()->prepare('DELETE FROM posts WHERE id = ?')->execute([$id]);
}

redirect('/admin/blog.php');
