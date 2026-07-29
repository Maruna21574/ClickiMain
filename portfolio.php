<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.portfolio_title');
$pageDesc = t('meta.portfolio_desc');
$activeNav = 'portfolio';

$categories = get_categories();
$activeCategory = isset($_GET['kategoria']) ? (string)$_GET['kategoria'] : 'all';
$validSlugs = array_column($categories, 'slug');
if ($activeCategory !== 'all' && !in_array($activeCategory, $validSlugs, true)) {
    $activeCategory = 'all';
}
$projects = get_projects();

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('portfolio.hero.eyebrow')) ?></p>
    <h1><?= h(t('portfolio.hero.title')) ?></h1>
    <p class="lead"><?= h(t('portfolio.hero.subtitle')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <div class="filter-bar" data-filter-bar>
      <button type="button" class="filter-btn<?= $activeCategory === 'all' ? ' is-active' : '' ?>" data-filter="all"><?= h(t('portfolio.filter.all')) ?></button>
      <?php foreach ($categories as $cat): ?>
      <button type="button" class="filter-btn<?= $activeCategory === $cat['slug'] ? ' is-active' : '' ?>" data-filter="<?= h($cat['slug']) ?>"><?= h(field($cat, 'name')) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="grid portfolio-grid" data-project-grid>
      <?php foreach ($projects as $p):
          $hidden = ($activeCategory !== 'all' && $p['category_slug'] !== $activeCategory);
      ?>
      <a href="/projekt.php?slug=<?= urlencode($p['slug']) ?>" class="project-card" data-category="<?= h($p['category_slug']) ?>" <?= $hidden ? 'style="display:none;"' : '' ?>>
        <div class="project-card__media"><img src="<?= h(cover_image($p)) ?>" alt="<?= h(field($p, 'title')) ?>" loading="lazy"></div>
        <div class="project-card__body">
          <span class="project-card__cat"><?= h(cat_name($p)) ?></span>
          <h3 class="project-card__title"><?= h(field($p, 'title')) ?></h3>
          <div class="project-card__meta"><span><?= h($p['client']) ?></span><span><?= h($p['year']) ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <p data-empty-msg style="display:none;text-align:center;color:var(--text-faint);padding-block:2rem;"><?= h(t('portfolio.empty')) ?></p>
  </div>
</section>

<?php $extraScripts = ['/assets/js/portfolio.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
