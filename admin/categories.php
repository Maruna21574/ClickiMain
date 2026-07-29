<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$iconOptions = ['code', 'sliders', 'share', 'palette', 'camera', 'drone', 'star', 'layers', 'globe', 'trending-up'];
$errors = [];
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Neplatná relácia, skúste znova.';
    }
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $projCount = (int)db()->query('SELECT COUNT(*) FROM projects WHERE category_id = ' . (int)$id)->fetchColumn();
        if ($projCount > 0) {
            $errors[] = 'Túto kategóriu nemožno zmazať, obsahuje ' . $projCount . ' projekt(ov). Najprv ich presuňte alebo zmažte.';
        } else {
            db()->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
            redirect('/admin/categories.php');
        }
    } elseif (empty($errors)) {
        $id = (int)($_POST['id'] ?? 0);
        $slug = slugify((string)($_POST['slug'] ?? ''));
        $nameSk = trim((string)($_POST['name_sk'] ?? ''));
        $nameEn = trim((string)($_POST['name_en'] ?? ''));
        $icon = in_array($_POST['icon'] ?? '', $iconOptions, true) ? $_POST['icon'] : 'star';
        $sort = (int)($_POST['sort_order'] ?? 0);

        if ($slug === '' || $nameSk === '' || $nameEn === '') {
            $errors[] = 'Vyplňte názov (SK aj EN) a slug.';
        } else {
            $dup = db()->prepare('SELECT id FROM categories WHERE slug = ? AND id != ?');
            $dup->execute([$slug, $id]);
            if ($dup->fetch()) {
                $errors[] = 'Tento slug už existuje.';
            } else {
                if ($id) {
                    db()->prepare('UPDATE categories SET slug=?, name_sk=?, name_en=?, icon=?, sort_order=? WHERE id=?')
                        ->execute([$slug, $nameSk, $nameEn, $icon, $sort, $id]);
                } else {
                    db()->prepare('INSERT INTO categories (slug, name_sk, name_en, icon, sort_order) VALUES (?,?,?,?,?)')
                        ->execute([$slug, $nameSk, $nameEn, $icon, $sort]);
                }
                redirect('/admin/categories.php');
            }
        }
    }
}

$categories = db()->query('SELECT c.*, (SELECT COUNT(*) FROM projects p WHERE p.category_id = c.id) AS project_count
    FROM categories c ORDER BY c.sort_order ASC')->fetchAll(PDO::FETCH_ASSOC);

$editing = null;
if ($editId) {
    foreach ($categories as $c) {
        if ((int)$c['id'] === $editId) { $editing = $c; break; }
    }
}
$form = $editing ?: ['id' => 0, 'slug' => '', 'name_sk' => '', 'name_en' => '', 'icon' => 'star', 'sort_order' => 0];

$pageTitle = 'Kategórie — Admin Clicki';
$adminActive = 'categories';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar"><h1>Kategórie</h1></div>

<?php foreach ($errors as $e): ?>
<div class="form-alert form-alert--error"><?= h($e) ?></div>
<?php endforeach; ?>

<div class="admin-card" style="padding:0;overflow-x:auto;">
  <table class="admin-table">
    <thead><tr><th>Názov (SK)</th><th>Názov (EN)</th><th>Slug</th><th>Ikona</th><th>Projektov</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($categories as $c): ?>
      <tr>
        <td><strong><?= h($c['name_sk']) ?></strong></td>
        <td><?= h($c['name_en']) ?></td>
        <td><?= h($c['slug']) ?></td>
        <td><?= icon($c['icon']) ?></td>
        <td><?= (int)$c['project_count'] ?></td>
        <td class="row-actions">
          <a href="/admin/categories.php?edit=<?= (int)$c['id'] ?>" class="btn btn--ghost btn--sm">Upraviť</a>
          <form method="post" action="/admin/categories.php" data-confirm="Zmazať kategóriu „<?= h($c['name_sk']) ?>“?">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
            <button type="submit" class="btn btn--ghost btn--sm">Zmazať</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="admin-card">
  <h3 style="font-size:var(--fs-lg);margin-bottom:1.2rem;"><?= $editing ? 'Upraviť kategóriu' : 'Pridať kategóriu' ?></h3>
  <form method="post" action="/admin/categories.php">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
    <div class="field-row">
      <div class="field"><label>Názov (SK)</label><input type="text" name="name_sk" value="<?= h($form['name_sk']) ?>" data-slug-source required></div>
      <div class="field"><label>Názov (EN)</label><input type="text" name="name_en" value="<?= h($form['name_en']) ?>" required></div>
    </div>
    <div class="field-row">
      <div class="field"><label>Slug</label><input type="text" name="slug" value="<?= h($form['slug']) ?>" data-slug-target required></div>
      <div class="field">
        <label>Ikona</label>
        <select name="icon">
          <?php foreach ($iconOptions as $opt): ?>
          <option value="<?= h($opt) ?>" <?= $form['icon'] === $opt ? 'selected' : '' ?>><?= h($opt) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="field"><label>Poradie</label><input type="number" name="sort_order" value="<?= (int)$form['sort_order'] ?>" style="max-width:140px;"></div>
    <button type="submit" class="btn btn--primary"><?= icon('check') ?> Uložiť kategóriu</button>
    <?php if ($editing): ?><a href="/admin/categories.php" class="btn btn--ghost">Zrušiť úpravu</a><?php endif; ?>
  </form>
</div>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
