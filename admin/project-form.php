<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$project = null;
if ($isEdit) {
    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$project) {
        redirect('/admin/index.php');
    }
}

$categories = get_categories();
$errors = [];

$data = $project ?: [
    'category_id' => $categories[0]['id'] ?? 0,
    'slug' => '', 'title_sk' => '', 'title_en' => '',
    'summary_sk' => '', 'summary_en' => '', 'description_sk' => '', 'description_en' => '',
    'client' => '', 'year' => date('Y'), 'featured' => 0, 'sort_order' => 0, 'cover_image' => '', 'live_url' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Neplatná relácia, skúste znova.';
    }
    $data['category_id'] = (int)($_POST['category_id'] ?? 0);
    $data['slug'] = slugify((string)($_POST['slug'] ?? ''));
    $data['title_sk'] = trim((string)($_POST['title_sk'] ?? ''));
    $data['title_en'] = trim((string)($_POST['title_en'] ?? ''));
    $data['summary_sk'] = trim((string)($_POST['summary_sk'] ?? ''));
    $data['summary_en'] = trim((string)($_POST['summary_en'] ?? ''));
    $data['description_sk'] = trim((string)($_POST['description_sk'] ?? ''));
    $data['description_en'] = trim((string)($_POST['description_en'] ?? ''));
    $data['client'] = trim((string)($_POST['client'] ?? ''));
    $data['year'] = trim((string)($_POST['year'] ?? ''));
    $data['live_url'] = trim((string)($_POST['live_url'] ?? ''));
    $data['featured'] = isset($_POST['featured']) ? 1 : 0;
    $data['sort_order'] = (int)($_POST['sort_order'] ?? 0);

    if ($data['title_sk'] === '' || $data['title_en'] === '') {
        $errors[] = 'Vyplňte názov projektu (SK aj EN).';
    }
    if ($data['slug'] === '') {
        $errors[] = 'Slug nemôže byť prázdny.';
    }
    if (!$data['category_id']) {
        $errors[] = 'Vyberte kategóriu.';
    }
    $liveUrl = normalize_url($data['live_url']);
    if ($liveUrl === null) {
        $errors[] = 'Odkaz na live ukážku nie je platná URL adresa.';
    } else {
        $data['live_url'] = $liveUrl;
    }

    if (empty($errors)) {
        $dupStmt = db()->prepare('SELECT id FROM projects WHERE slug = ? AND id != ?');
        $dupStmt->execute([$data['slug'], $id]);
        if ($dupStmt->fetch()) {
            $errors[] = 'Tento slug už existuje, zvoľte iný.';
        }
    }

    if (empty($errors)) {
        $coverPath = handle_image_upload('cover_image');
        if ($coverPath) {
            $data['cover_image'] = $coverPath;
        }

        if ($isEdit) {
            $sql = 'UPDATE projects SET category_id=?, slug=?, title_sk=?, title_en=?, summary_sk=?, summary_en=?,
                    description_sk=?, description_en=?, client=?, year=?, live_url=?, cover_image=?, featured=?, sort_order=? WHERE id=?';
            db()->prepare($sql)->execute([
                $data['category_id'], $data['slug'], $data['title_sk'], $data['title_en'],
                $data['summary_sk'], $data['summary_en'], $data['description_sk'], $data['description_en'],
                $data['client'], $data['year'], $data['live_url'], $data['cover_image'], $data['featured'], $data['sort_order'], $id,
            ]);
        } else {
            $sql = 'INSERT INTO projects (category_id, slug, title_sk, title_en, summary_sk, summary_en,
                    description_sk, description_en, client, year, live_url, cover_image, featured, sort_order)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            db()->prepare($sql)->execute([
                $data['category_id'], $data['slug'], $data['title_sk'], $data['title_en'],
                $data['summary_sk'], $data['summary_en'], $data['description_sk'], $data['description_en'],
                $data['client'], $data['year'], $data['live_url'], $data['cover_image'], $data['featured'], $data['sort_order'],
            ]);
            $id = (int)db()->lastInsertId();
        }

        if (!empty($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            $delStmt = db()->prepare('DELETE FROM project_images WHERE id = ? AND project_id = ?');
            foreach ($_POST['delete_images'] as $imgId) {
                $delStmt->execute([(int)$imgId, $id]);
            }
        }

        $newGallery = handle_multi_upload('gallery');
        if ($newGallery) {
            $maxOrder = (int)db()->query('SELECT COALESCE(MAX(sort_order),0) FROM project_images WHERE project_id = ' . (int)$id)->fetchColumn();
            $imgStmt = db()->prepare('INSERT INTO project_images (project_id, image_path, sort_order) VALUES (?, ?, ?)');
            foreach ($newGallery as $i => $path) {
                $imgStmt->execute([$id, $path, $maxOrder + $i + 1]);
            }
        }

        redirect('/admin/index.php');
    }
}

$galleryImages = $isEdit ? get_project_images($id) : [];

$pageTitle = ($isEdit ? 'Upraviť projekt' : 'Nový projekt') . ' — Admin Clicki';
$adminActive = 'dashboard';
require __DIR__ . '/../templates/admin-header.php';
?>

<div class="admin-topbar">
  <h1><?= $isEdit ? 'Upraviť projekt' : 'Nový projekt' ?></h1>
  <a href="/admin/index.php" class="btn btn--ghost btn--sm">Späť na zoznam</a>
</div>

<?php foreach ($errors as $e): ?>
<div class="form-alert form-alert--error"><?= h($e) ?></div>
<?php endforeach; ?>

<form method="post" action="/admin/project-form.php<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="field-row">
      <div class="field">
        <label>Kategória</label>
        <select name="category_id" required>
          <?php foreach ($categories as $cat): ?>
          <option value="<?= (int)$cat['id'] ?>" <?= (int)$data['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>><?= h($cat['name_sk']) ?></option>
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
        <label>Názov (EN)</label>
        <input type="text" name="title_en" value="<?= h($data['title_en']) ?>" required>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Krátky popis pre kartu (SK)</label>
        <input type="text" name="summary_sk" value="<?= h($data['summary_sk']) ?>">
      </div>
      <div class="field">
        <label>Krátky popis pre kartu (EN)</label>
        <input type="text" name="summary_en" value="<?= h($data['summary_en']) ?>">
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Popis projektu (SK)</label>
        <textarea name="description_sk"><?= h($data['description_sk']) ?></textarea>
      </div>
      <div class="field">
        <label>Popis projektu (EN)</label>
        <textarea name="description_en"><?= h($data['description_en']) ?></textarea>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Klient</label>
        <input type="text" name="client" value="<?= h($data['client']) ?>">
      </div>
      <div class="field">
        <label>Rok</label>
        <input type="text" name="year" value="<?= h($data['year']) ?>">
      </div>
    </div>

    <div class="field">
      <label>Live ukážka (URL webu, nepovinné)</label>
      <input type="text" inputmode="url" name="live_url" value="<?= h($data['live_url'] ?? '') ?>" placeholder="https://www.priklad.sk">
    </div>

    <div class="field-row">
      <div class="field">
        <label>Poradie zobrazenia (nižšie = skôr)</label>
        <input type="number" name="sort_order" value="<?= (int)$data['sort_order'] ?>">
      </div>
      <div class="field">
        <label class="checkbox-field" style="margin-top:2.2rem;">
          <input type="checkbox" name="featured" value="1" <?= $data['featured'] ? 'checked' : '' ?>>
          Zvýrazniť na domovskej stránke (featured)
        </label>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="field">
      <label>Titulná fotka (cover)</label>
      <?php if (!empty($data['cover_image'])): ?>
      <img id="cover-preview" src="<?= h($data['cover_image']) ?>" alt="" style="width:220px;height:auto;border-radius:8px;margin-bottom:.8rem;display:block;">
      <?php else: ?>
      <img id="cover-preview" src="" alt="" style="width:220px;height:auto;border-radius:8px;margin-bottom:.8rem;display:none;">
      <?php endif; ?>
      <input type="file" name="cover_image" accept="image/png,image/jpeg,image/webp" data-image-input="cover-preview">
    </div>

    <div class="field">
      <label>Galéria — pridať nové fotky</label>
      <input type="file" name="gallery[]" accept="image/png,image/jpeg,image/webp" multiple>
    </div>

    <?php if ($galleryImages): ?>
    <div class="field">
      <label>Existujúca galéria (zaškrtnite pre zmazanie)</label>
      <div class="gallery-thumbs">
        <?php foreach ($galleryImages as $img): ?>
        <figure>
          <img src="<?= h($img['image_path']) ?>" alt="">
          <label style="position:absolute;bottom:2px;left:2px;background:rgba(0,0,0,.6);border-radius:4px;padding:1px 5px;font-size:11px;display:flex;align-items:center;gap:3px;">
            <input type="checkbox" name="delete_images[]" value="<?= (int)$img['id'] ?>" style="width:auto;"> zmazať
          </label>
        </figure>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn--primary"><?= icon('check') ?> Uložiť projekt</button>
</form>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
