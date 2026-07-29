<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    redirect('/admin/index.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($project) {
        $paths = array_merge([$project['cover_image']], array_column(get_project_images($id), 'image_path'));
        foreach ($paths as $path) {
            if (str_starts_with($path, UPLOADS_URL . '/')) {
                $file = SITE_ROOT . $path;
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        db()->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
    }
}

redirect('/admin/index.php');
