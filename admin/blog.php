<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$posts = db()->query('SELECT * FROM posts ORDER BY published_at DESC, id DESC')->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Blog — Admin Clicki';
$adminActive = 'blog';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Blog</h1>
  <a href="/admin/blog-form.php" class="btn btn--primary btn--sm">Nový článok <?= icon('arrow-right') ?></a>
</div>

<div class="admin-card" style="padding:0;overflow-x:auto;">
  <table class="admin-table">
    <thead>
      <tr><th>Názov</th><th>Téma</th><th>Dátum</th><th>Stav</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
      <tr>
        <td><strong><?= h($p['title_sk']) ?></strong></td>
        <td><span class="tag-pill"><?= h($p['topic']) ?></span></td>
        <td><?= h(format_date($p['published_at'])) ?></td>
        <td><?= $p['is_published'] ? '<span class="tag-pill tag-pill--pink">Zverejnený</span>' : '<span class="tag-pill tag-pill--muted">Koncept</span>' ?></td>
        <td class="row-actions">
          <a href="/clanok.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" class="btn btn--ghost btn--sm">Zobraziť</a>
          <a href="/admin/blog-form.php?id=<?= (int)$p['id'] ?>" class="btn btn--ghost btn--sm">Upraviť</a>
          <form method="post" action="/admin/blog-delete.php" data-confirm="Naozaj zmazať článok „<?= h($p['title_sk']) ?>“?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <button type="submit" class="btn btn--ghost btn--sm">Zmazať</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$posts): ?>
      <tr><td colspan="5" style="color:var(--text-faint);text-align:center;padding:2rem;">Zatiaľ žiadne články.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
