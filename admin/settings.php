<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Neplatná relácia, skúste znova.';
    } else {
        $current = (string)($_POST['current_password'] ?? '');
        $new = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['new_password_confirm'] ?? '');

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $errors[] = 'Aktuálne heslo nie je správne.';
        } elseif (mb_strlen($new) < 8) {
            $errors[] = 'Nové heslo musí mať aspoň 8 znakov.';
        } elseif ($new !== $confirm) {
            $errors[] = 'Nové heslá sa nezhodujú.';
        } else {
            db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
            $success = true;
        }
    }
}

$pageTitle = 'Nastavenia — Admin Clicki';
$adminActive = 'settings';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar"><h1>Nastavenia</h1></div>

<div class="admin-card" style="max-width:480px;">
  <h3 style="font-size:var(--fs-lg);margin-bottom:1.2rem;">Zmena hesla</h3>

  <?php if ($success): ?><div class="form-alert form-alert--success">Heslo bolo úspešne zmenené.</div><?php endif; ?>
  <?php foreach ($errors as $e): ?><div class="form-alert form-alert--error"><?= h($e) ?></div><?php endforeach; ?>

  <form method="post" action="/admin/settings.php">
    <?= csrf_field() ?>
    <div class="field"><label>Aktuálne heslo</label><input type="password" name="current_password" required></div>
    <div class="field"><label>Nové heslo</label><input type="password" name="new_password" required></div>
    <div class="field"><label>Zopakujte nové heslo</label><input type="password" name="new_password_confirm" required></div>
    <button type="submit" class="btn btn--primary">Zmeniť heslo</button>
  </form>
</div>

<div class="admin-card" style="max-width:480px;">
  <h3 style="font-size:var(--fs-lg);margin-bottom:.8rem;">Kontaktný e-mail a texty webu</h3>
  <p style="font-size:var(--fs-sm);color:var(--text-faint);">
    Kontaktný e-mail, na ktorý chodia dopyty (<code><?= h(ADMIN_EMAIL) ?></code>), sa mení v súbore
    <code>inc/config.php</code>. Statické texty webu (nadpisy, popisy služieb a pod.) sa upravujú v
    <code>inc/i18n/sk.php</code> a <code>inc/i18n/en.php</code>.
  </p>
</div>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
