<?php
require_once __DIR__ . '/../inc/functions.php';

if (is_logged_in()) {
    redirect('/admin/index.php');
}

$isSetup = !admin_exists();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Neplatná relácia, skúste znova.';
    } elseif ($isSetup) {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['password_confirm'] ?? '');

        if (mb_strlen($username) < 3) {
            $error = 'Meno musí mať aspoň 3 znaky.';
        } elseif (mb_strlen($password) < 8) {
            $error = 'Heslo musí mať aspoň 8 znakov.';
        } elseif ($password !== $confirm) {
            $error = 'Heslá sa nezhodujú.';
        } else {
            $stmt = db()->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            attempt_login($username, $password);
            redirect('/admin/index.php');
        }
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (attempt_login($username, $password)) {
            redirect('/admin/index.php');
        }
        $error = 'Nesprávne prihlasovacie meno alebo heslo.';
    }
}

$pageTitle = ($isSetup ? 'Nastavenie administrátora' : 'Prihlásenie') . ' — Clicki';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="/assets/img/clicki_favicon_small.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<div class="admin-auth">
  <div class="admin-auth-card">
    <img src="/assets/img/clicki_logo_white.png" alt="Clicki">
    <?php if ($isSetup): ?>
      <h1>Vitajte v Clicki</h1>
      <p class="lead">Toto je prvé spustenie — vytvorte si administrátorský účet pre správu portfólia.</p>
    <?php else: ?>
      <h1>Prihlásenie</h1>
      <p class="lead">Prihláste sa do administrácie portfólia Clicki.</p>
    <?php endif; ?>

    <?php if ($error): ?><div class="form-alert form-alert--error"><?= h($error) ?></div><?php endif; ?>

    <form method="post" action="/admin/login.php">
      <?= csrf_field() ?>
      <div class="field">
        <label for="username">Prihlasovacie meno</label>
        <input type="text" id="username" name="username" value="<?= h($_POST['username'] ?? '') ?>" required autofocus>
      </div>
      <div class="field">
        <label for="password">Heslo</label>
        <input type="password" id="password" name="password" required>
      </div>
      <?php if ($isSetup): ?>
      <div class="field">
        <label for="password_confirm">Zopakujte heslo</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
      </div>
      <?php endif; ?>
      <button type="submit" class="btn btn--primary btn--block"><?= $isSetup ? 'Vytvoriť účet' : 'Prihlásiť sa' ?></button>
    </form>
  </div>
</div>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
