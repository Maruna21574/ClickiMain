<?php
require_once __DIR__ . '/inc/functions.php';

$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
$project = $slug !== '' ? get_project_by_slug($slug) : null;

if (!$project) {
    http_response_code(404);
    $pageTitle = t('project.not_found') . ' — Clicki';
    $activeNav = 'portfolio';
    require __DIR__ . '/templates/header.php';
    ?>
    <section class="error-page">
      <div class="container text-center">
        <h1><?= h(t('project.not_found')) ?></h1>
        <p class="lead" style="margin-block:1.5rem;"><a href="/portfolio.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('project.back')) ?></a></p>
      </div>
    </section>
    <?php
    require __DIR__ . '/templates/footer.php';
    exit;
}

$pageTitle = field($project, 'title') . ' — Clicki';
$pageDesc = excerpt(field($project, 'summary'), 160);
$activeNav = 'portfolio';

$images = get_project_images((int)$project['id']);
$liveUrl = preg_match('#^https?://#i', $project['live_url'] ?? '') ? $project['live_url'] : '';
// pri fotografiách vedie odkaz na galériu fotografky, nie na živý web
$isPhoto = $project['category_slug'] === 'foto';
$liveLabel = $isPhoto ? t('project.full_gallery') : t('project.live');
$liveMetaLabel = $isPhoto ? t('project.photographer') : t('project.website');
$liveMetaText = $isPhoto ? 'Attelier Kay' : preg_replace('#^https?://(www\.)?#i', '', rtrim($liveUrl, '/'));
$more = array_filter(get_projects($project['category_slug'], 4), function ($p) use ($project) {
    return $p['id'] !== $project['id'];
});
$more = array_slice($more, 0, 3);

require __DIR__ . '/templates/header.php';
?>

<section class="section section--tight" style="padding-top:8.5rem;">
  <div class="container project-hero">
    <div data-reveal>
      <a href="/portfolio.php" class="back-link"><?= icon('arrow-right', 'icon') ?> <?= h(t('project.back')) ?></a>
      <p class="eyebrow"><?= h(cat_name($project)) ?></p>
      <h1><?= preg_replace('/\S+-\S+/u', '<span class="nowrap">$0</span>', h(field($project, 'title'))) ?></h1>
      <p class="lead" style="margin-top:1.2rem;"><?= h(field($project, 'summary')) ?></p>
      <?php if ($liveUrl): ?>
      <a href="<?= h($liveUrl) ?>" class="btn btn--primary project-live-btn" target="_blank" rel="noopener"><?= icon('arrow-up-right') ?> <?= h($liveLabel) ?></a>
      <?php endif; ?>
    </div>
    <div class="project-meta-box" data-reveal>
      <div class="project-meta-row"><span><?= h(t('project.client')) ?></span><span><?= h($project['client']) ?></span></div>
      <div class="project-meta-row"><span><?= h(t('project.year')) ?></span><span><?= h($project['year']) ?></span></div>
      <div class="project-meta-row"><span><?= h(t('project.category')) ?></span><span><?= h(cat_name($project)) ?></span></div>
      <?php if ($liveUrl): ?>
      <div class="project-meta-row"><span><?= h($liveMetaLabel) ?></span><span><a href="<?= h($liveUrl) ?>" target="_blank" rel="noopener"><?= h($liveMetaText) ?></a></span></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="container">
  <div class="project-cover<?= $isPhoto ? ' project-cover--photo' : '' ?>" data-reveal>
    <img src="<?= h(cover_image($project)) ?>" alt="<?= h(field($project, 'title')) ?>">
  </div>

  <div class="container--narrow" style="margin-inline:0;max-width:820px;">
    <p class="lead" data-reveal><?= nl2br(h(field($project, 'description'))) ?></p>
  </div>

  <?php if ($images): ?>
  <div class="section section--tight">
    <h2 style="margin-bottom:1.6rem;font-size:var(--fs-h3);"><?= h(t('project.gallery')) ?></h2>
    <div class="grid project-gallery<?= $isPhoto ? ' project-gallery--photo' : '' ?>" data-reveal>
      <?php foreach ($images as $img): ?>
      <a href="<?= h($img['image_path']) ?>" data-lightbox-src="<?= h($img['image_path']) ?>" data-lightbox-alt="<?= h(field($project, 'title')) ?>">
        <img src="<?= h($img['image_path']) ?>" alt="<?= h(field($project, 'title')) ?>" loading="lazy">
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<div class="lightbox" data-lightbox>
  <button type="button" class="lightbox__close" data-lightbox-close aria-label="Zavrieť"><?= icon('x') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev aria-label="Predchádzajúci"><?= icon('arrow-right', 'icon') ?></button>
  <img src="" alt="">
  <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next aria-label="Ďalší"><?= icon('arrow-right') ?></button>
</div>

<?php if ($more): ?>
<section class="section section--alt">
  <div class="container">
    <h2 style="margin-bottom:2rem;"><?= h(t('project.more_title')) ?></h2>
    <div class="grid portfolio-grid">
      <?php foreach ($more as $p): ?>
      <a href="/projekt.php?slug=<?= urlencode($p['slug']) ?>" class="project-card">
        <div class="project-card__media"><img src="<?= h(cover_image($p)) ?>" alt="<?= h(field($p, 'title')) ?>" loading="lazy"></div>
        <div class="project-card__body">
          <span class="project-card__cat"><?= h(cat_name($p)) ?></span>
          <h3 class="project-card__title"><?= h(field($p, 'title')) ?></h3>
          <div class="project-card__meta"><span><?= h($p['client']) ?></span><span><?= h($p['year']) ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <div class="cta-band" data-reveal>
      <h2><?= h(t('project.cta_title')) ?></h2>
      <p><?= h(t('project.cta_subtitle')) ?></p>
      <a href="/kontakt.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('project.cta_button')) ?></a>
    </div>
  </div>
</section>

<?php $extraScripts = ['/assets/js/portfolio.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
