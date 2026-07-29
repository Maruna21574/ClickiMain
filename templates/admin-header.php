<?php
/** Očakáva: $pageTitle, $adminActive */
$adminActive = $adminActive ?? '';
?>
<!DOCTYPE html>
<html lang="sk" class="no-js">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle ?? 'Admin — Clicki') ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="/assets/img/clicki_favicon_small.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="js">
<div class="admin-body">
  <aside class="admin-sidebar">
    <div class="brand"><img src="/assets/img/clicki_logo_white.png" alt="Clicki"></div>
    <nav class="admin-nav">
      <a href="/admin/index.php" class="<?= $adminActive === 'dashboard' ? 'is-active' : '' ?>"><?= icon('layers') ?> Projekty</a>
      <a href="/admin/categories.php" class="<?= $adminActive === 'categories' ? 'is-active' : '' ?>"><?= icon('sliders') ?> Kategórie</a>
      <a href="/admin/messages.php" class="<?= $adminActive === 'messages' ? 'is-active' : '' ?>"><?= icon('mail') ?> Správy</a>
      <a href="/admin/settings.php" class="<?= $adminActive === 'settings' ? 'is-active' : '' ?>"><?= icon('check') ?> Nastavenia</a>
      <a href="/" target="_blank"><?= icon('arrow-up-right') ?> Zobraziť web</a>
    </nav>
    <div class="admin-sidebar__foot">
      <a href="/admin/logout.php"><?= icon('x') ?> Odhlásiť sa</a>
    </div>
  </aside>
  <main class="admin-main">
