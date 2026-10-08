<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db()->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
    } elseif ($action === 'toggle_read') {
        db()->prepare('UPDATE messages SET is_read = 1 - is_read WHERE id = ?')->execute([$id]);
    }
    redirect('/admin/messages.php');
}

$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Správy — Admin Clicki';
$adminActive = 'messages';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar"><h1>Správy z kontaktného formulára</h1></div>

<div class="admin-card" style="padding:0;">
  <?php if (!$messages): ?>
    <p style="padding:2rem;text-align:center;color:var(--text-faint);">Zatiaľ žiadne správy.</p>
  <?php endif; ?>
  <?php foreach ($messages as $m): ?>
  <details style="border-bottom:1px solid var(--border);padding:1.1rem 1.5rem;">
    <summary style="cursor:pointer;display:flex;justify-content:space-between;gap:1rem;align-items:center;list-style:none;">
      <span style="display:flex;align-items:center;gap:.8rem;">
        <?= $m['is_read'] ? '<span class="tag-pill tag-pill--muted">prečítané</span>' : '<span class="tag-pill tag-pill--pink">nové</span>' ?>
        <strong><?= h($m['name']) ?></strong>
        <span style="color:var(--text-faint);font-size:var(--fs-xs);"><?= h($m['email']) ?></span>
      </span>
      <span style="color:var(--text-faint);font-size:var(--fs-xs);"><?= h(format_date($m['created_at'])) ?></span>
    </summary>
    <div style="padding-top:1rem;">
      <p style="font-size:var(--fs-sm);color:var(--text-faint);margin-bottom:.6rem;">
        Telefón: <?= h($m['phone'] ?: '—') ?> · Rozpočet: <?= h($m['budget'] ?: '—') ?>
        <?php if (preg_match('#^https?://#i', $m['website'] ?? '')): ?>
        · Aktuálny web: <a href="<?= h($m['website']) ?>" target="_blank" rel="noopener noreferrer" style="color:var(--pink);"><?= h(preg_replace('#^https?://(www\.)?#i', '', rtrim($m['website'], '/'))) ?></a>
        <?php endif; ?>
      </p>
      <p style="white-space:pre-wrap;"><?= h($m['message']) ?></p>
      <div class="row-actions" style="margin-top:1rem;">
        <form method="post" action="/admin/messages.php">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <input type="hidden" name="action" value="toggle_read">
          <button type="submit" class="btn btn--ghost btn--sm"><?= $m['is_read'] ? 'Označiť ako neprečítané' : 'Označiť ako prečítané' ?></button>
        </form>
        <form method="post" action="/admin/messages.php" data-confirm="Zmazať túto správu?">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <input type="hidden" name="action" value="delete">
          <button type="submit" class="btn btn--ghost btn--sm">Zmazať</button>
        </form>
        <a href="mailto:<?= h($m['email']) ?>" class="btn btn--primary btn--sm">Odpovedať e-mailom</a>
      </div>
    </div>
  </details>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
