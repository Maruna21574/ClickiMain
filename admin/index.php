<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$projects = db()->query('SELECT p.*, c.name_sk AS cat_name FROM projects p JOIN categories c ON c.id = p.category_id ORDER BY p.sort_order ASC, p.id DESC')->fetchAll(PDO::FETCH_ASSOC);
$catCount = (int)db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$unreadCount = (int)db()->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();

$pageTitle = 'Projekty — Admin Clicki';
$adminActive = 'dashboard';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Projekty</h1>
  <a href="/admin/project-form.php" class="btn btn--primary btn--sm"><?= icon('arrow-right') ?> Nový projekt</a>
</div>

<div class="grid admin-stats">
  <div class="admin-stat"><div class="admin-stat__value"><?= count($projects) ?></div><div class="admin-stat__label">Projektov v portfóliu</div></div>
  <div class="admin-stat"><div class="admin-stat__value"><?= $catCount ?></div><div class="admin-stat__label">Kategórií</div></div>
  <div class="admin-stat"><div class="admin-stat__value"><?= $unreadCount ?></div><div class="admin-stat__label">Neprečítaných správ</div></div>
  <div class="admin-stat"><div class="admin-stat__value"><?= count(array_filter($projects, fn($p) => $p['featured'])) ?></div><div class="admin-stat__label">Zvýraznených (featured)</div></div>
</div>

<div class="admin-card" style="padding:0;overflow-x:auto;">
  <table class="admin-table">
    <thead>
      <tr><th></th><th>Názov</th><th>Kategória</th><th>Klient</th><th>Rok</th><th>Featured</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($projects as $p): ?>
      <tr>
        <td><img class="thumb" src="<?= h($p['cover_image'] ?: '/assets/img/placeholder.php?title=' . urlencode($p['title_sk']) . '&cat=' . urlencode($p['cat_slug'] ?? 'web')) ?>" alt=""></td>
        <td><strong><?= h($p['title_sk']) ?></strong></td>
        <td><span class="tag-pill"><?= h($p['cat_name']) ?></span></td>
        <td><?= h($p['client']) ?></td>
        <td><?= h($p['year']) ?></td>
        <td><?= $p['featured'] ? '<span class="tag-pill tag-pill--pink">Áno</span>' : '<span class="tag-pill tag-pill--muted">Nie</span>' ?></td>
        <td class="row-actions">
          <a href="/admin/project-form.php?id=<?= (int)$p['id'] ?>" class="btn btn--ghost btn--sm">Upraviť</a>
          <form method="post" action="/admin/project-delete.php" data-confirm="Naozaj zmazať projekt „<?= h($p['title_sk']) ?>“?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <button type="submit" class="btn btn--ghost btn--sm">Zmazať</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$projects): ?>
      <tr><td colspan="7" style="color:var(--text-faint);text-align:center;padding:2rem;">Zatiaľ žiadne projekty.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
