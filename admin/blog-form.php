<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$post = null;
if ($isEdit) {
    $stmt = db()->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$post) {
        redirect('/admin/blog.php');
    }
}

$topics = ['web' => 'Web', 'seo' => 'SEO a rýchlosť', 'sprava-webu' => 'Správa webu', 'socialne-siete' => 'Sociálne siete', 'grafika' => 'Grafika', 'foto' => 'Fotografia'];
$errors = [];

$data = $post ?: [
    'slug' => '', 'topic' => 'web', 'title_sk' => '', 'title_en' => '', 'summary_sk' => '', 'summary_en' => '',
    'body_sk' => '', 'body_en' => '', 'cover_image' => '', 'is_published' => 1, 'published_at' => date('Y-m-d'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Neplatná relácia, skúste znova.';
    }
    $data['slug'] = slugify((string)($_POST['slug'] ?? ''));
    $data['topic'] = array_key_exists((string)($_POST['topic'] ?? ''), $topics) ? (string)$_POST['topic'] : 'web';
    foreach (['title_sk', 'title_en', 'summary_sk', 'summary_en', 'body_sk', 'body_en'] as $f) {
        $data[$f] = trim((string)($_POST[$f] ?? ''));
    }
    $data['is_published'] = isset($_POST['is_published']) ? 1 : 0;
    $date = (string)($_POST['published_at'] ?? '');
    $data['published_at'] = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : date('Y-m-d');

    if ($data['title_sk'] === '') {
        $errors[] = 'Vyplňte názov článku (SK).';
    }
    if ($data['slug'] === '') {
        $errors[] = 'Slug nemôže byť prázdny.';
    }
    if ($data['body_sk'] === '') {
        $errors[] = 'Vyplňte text článku (SK).';
    }
    if (empty($errors)) {
        $dup = db()->prepare('SELECT id FROM posts WHERE slug = ? AND id != ?');
        $dup->execute([$data['slug'], $id]);
        if ($dup->fetch()) {
            $errors[] = 'Tento slug už existuje, zvoľte iný.';
        }
    }

    if (empty($errors)) {
        $cover = handle_image_upload('cover_image');
        if ($cover) {
            $data['cover_image'] = $cover;
        }
        $vals = [$data['slug'], $data['topic'], $data['title_sk'], $data['title_en'], $data['summary_sk'], $data['summary_en'],
                 $data['body_sk'], $data['body_en'], $data['cover_image'], $data['is_published'], $data['published_at']];
        if ($isEdit) {
            db()->prepare('UPDATE posts SET slug=?, topic=?, title_sk=?, title_en=?, summary_sk=?, summary_en=?, body_sk=?, body_en=?, cover_image=?, is_published=?, published_at=? WHERE id=?')
                ->execute(array_merge($vals, [$id]));
        } else {
            db()->prepare('INSERT INTO posts (slug, topic, title_sk, title_en, summary_sk, summary_en, body_sk, body_en, cover_image, is_published, published_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)')
                ->execute($vals);
        }
        redirect('/admin/blog.php');
    }
}

$pageTitle = ($isEdit ? 'Upraviť článok' : 'Nový článok') . ' — Admin Clicki';
$adminActive = 'blog';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar">
  <h1><?= $isEdit ? 'Upraviť článok' : 'Nový článok' ?></h1>
  <a href="/admin/blog.php" class="btn btn--ghost btn--sm">Späť na zoznam</a>
</div>

<?php foreach ($errors as $e): ?>
<div class="form-alert form-alert--error"><?= h($e) ?></div>
<?php endforeach; ?>

<form method="post" action="/admin/blog-form.php<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="field-row">
      <div class="field">
        <label>Téma</label>
        <select name="topic">
          <?php foreach ($topics as $k => $label): ?>
          <option value="<?= h($k) ?>" <?= $data['topic'] === $k ? 'selected' : '' ?>><?= h($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label>Slug (URL)</label>
        <input type="text" name="slug" value="<?= h($data['slug']) ?>" data-slug-target required>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Názov (SK)</label>
        <input type="text" name="title_sk" value="<?= h($data['title_sk']) ?>" data-slug-source required>
      </div>
      <div class="field">
        <label>Názov (EN, nepovinné)</label>
        <input type="text" name="title_en" value="<?= h($data['title_en']) ?>">
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Krátky popis (SK) — zobrazí sa v zozname a v Google</label>
        <textarea name="summary_sk" style="min-height:90px;"><?= h($data['summary_sk']) ?></textarea>
      </div>
      <div class="field">
        <label>Krátky popis (EN, nepovinné)</label>
        <textarea name="summary_en" style="min-height:90px;"><?= h($data['summary_en']) ?></textarea>
      </div>
    </div>

    <div class="field">
      <label>Text článku (SK)</label>
      <textarea name="body_sk" style="min-height:420px;" required><?= h($data['body_sk']) ?></textarea>
      <p class="field-hint">Odseky oddeľte prázdnym riadkom. <code>## Nadpis</code> = nadpis sekcie, <code>- položka</code> = zoznam, <code>1. položka</code> = číslovaný zoznam, <code>**tučné**</code>.</p>
    </div>

    <div class="field">
      <label>Text článku (EN, nepovinné — ak je prázdny, použije sa slovenský)</label>
      <textarea name="body_en" style="min-height:260px;"><?= h($data['body_en']) ?></textarea>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Dátum zverejnenia</label>
        <input type="date" name="published_at" value="<?= h($data['published_at']) ?>">
      </div>
      <div class="field">
        <label class="checkbox-field" style="margin-top:2.2rem;">
          <input type="checkbox" name="is_published" value="1" <?= $data['is_published'] ? 'checked' : '' ?>>
          Zverejnený (inak ostane ako koncept)
        </label>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="field">
      <label>Titulný obrázok (nepovinné — bez neho sa vygeneruje podľa témy)</label>
      <?php if (!empty($data['cover_image'])): ?>
      <img id="cover-preview" src="<?= h($data['cover_image']) ?>" alt="" style="width:220px;height:auto;border-radius:8px;margin-bottom:.8rem;display:block;">
      <?php else: ?>
      <img id="cover-preview" src="" alt="" style="width:220px;height:auto;border-radius:8px;margin-bottom:.8rem;display:none;">
      <?php endif; ?>
      <input type="file" name="cover_image" accept="image/png,image/jpeg,image/webp" data-image-input="cover-preview">
    </div>
  </div>

  <button type="submit" class="btn btn--primary"><?= icon('check') ?> Uložiť článok</button>
</form>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
