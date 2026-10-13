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
        <p class="lead" style="margin-block:1.5rem;"><a href="/portfolio" class="btn btn--primary"><?= h(t('project.back')) ?> <?= icon('arrow-right') ?></a></p>
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

$title = field($project, 'title');
$cover = cover_image($project);
// posledný obrázok webu býva mobilný náhľad — ten dostane vlastný blok
$mobileImg = null;
$screens = $images;
if (!$isPhoto && $images && is_mobile_mockup(SITE_ROOT . end($images)['image_path'])) {
    $mobileImg = end($images)['image_path'];
    array_pop($screens);
}
$galleryPaths = array_column($screens, 'image_path');

// obrázky pre hero náhľad: titulná fotka + ďalšie z galérie (bez duplikátov titulnej)
$coverHash = (str_starts_with($cover, UPLOADS_URL . '/') && is_file(SITE_ROOT . $cover)) ? md5_file(SITE_ROOT . $cover) : null;
$heroImgs = [$cover];
foreach ($galleryPaths as $gp) {
    if (count($heroImgs) >= ($isPhoto ? 3 : 6)) {
        break;
    }
    $f = SITE_ROOT . $gp;
    if ($coverHash && is_file($f) && md5_file($f) === $coverHash) {
        continue;
    }
    $heroImgs[] = $gp;
}

$paragraphs = array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', field($project, 'description')))));
$tags = array_values(array_filter(array_map('trim', explode(',', field($project, 'tags')))));
$host = $liveUrl ? preg_replace('#^https?://(www\.)?#i', '', rtrim($liveUrl, '/')) : 'clicki.sk';

require __DIR__ . '/templates/header.php';
?>

<section class="pd-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <a href="/portfolio" class="back-link"><?= icon('arrow-right', 'icon') ?> <?= h(t('project.back')) ?></a>
    <div class="pd-hero__grid">
      <div class="pd-hero__text">
        <p class="eyebrow"><?= h(cat_name($project)) ?></p>
        <h1><?= preg_replace('/\S+-\S+/u', '<span class="nowrap">$0</span>', h($title)) ?></h1>
        <p class="lead"><?= h(field($project, 'summary')) ?></p>
        <div class="pd-hero__actions">
          <?php if ($liveUrl): ?>
          <a href="<?= h($liveUrl) ?>" class="btn btn--primary" target="_blank" rel="noopener"><?= h($liveLabel) ?> <?= icon('arrow-up-right') ?></a>
          <?php endif; ?>
          <a href="/ponuka" class="btn btn--ghost"><?= h(t('project.similar_cta')) ?> <?= icon('arrow-right') ?></a>
        </div>
      </div>

      <div class="pd-hero__visual">
        <?php if ($isPhoto): ?>
        <div class="pd-fan<?= count($heroImgs) < 2 ? ' pd-fan--single' : '' ?>">
          <?php foreach ($heroImgs as $i => $src): ?>
          <img src="<?= h($src) ?>" alt="<?= $i === 0 ? h($title) : '' ?>"<?= $i === 0 ? ' fetchpriority="high"' : '' ?>>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="pd-mock" data-tilt>
          <div class="pd-mock__bar">
            <i></i><i></i><i></i>
            <span class="pd-mock__url"><?= icon('shield') ?><?= h($host) ?></span>
          </div>
          <div class="pd-mock__view">
            <div class="pd-mock__strip" data-tour style="--n: <?= count($heroImgs) ?>">
              <?php foreach ($heroImgs as $i => $src): ?>
              <img src="<?= h($src) ?>" alt="<?= $i === 0 ? h($title) : '' ?>"<?= $i === 0 ? ' fetchpriority="high"' : ' loading="lazy"' ?>>
              <?php endforeach; ?>
            </div>
            <?php if (count($heroImgs) > 1): ?>
            <div class="pd-mock__dots" data-tour-dots>
              <?php foreach ($heroImgs as $i => $src): ?><button type="button" class="<?= $i === 0 ? 'is-on' : '' ?>" aria-label="<?= $i + 1 ?>"></button><?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container pd-body">
    <aside class="pd-aside" data-reveal>
      <dl class="pd-facts">
        <div><dt><?= h(t('project.client')) ?></dt><dd><?= h($project['client']) ?></dd></div>
        <div><dt><?= h(t('project.year')) ?></dt><dd><?= h($project['year']) ?></dd></div>
        <div><dt><?= h(t('project.category')) ?></dt><dd><?= h(cat_name($project)) ?></dd></div>
        <?php if ($liveUrl): ?>
        <div><dt><?= h($liveMetaLabel) ?></dt><dd><a href="<?= h($liveUrl) ?>" target="_blank" rel="noopener"><?= h($liveMetaText) ?></a></dd></div>
        <?php endif; ?>
      </dl>
      <?php if ($tags): ?>
      <div class="pd-aside__tags">
        <p class="pd-aside__label"><?= h(t('project.tags')) ?></p>
        <div class="pd-tags"><?php foreach ($tags as $tag): ?><span class="pd-tag"><?= h($tag) ?></span><?php endforeach; ?></div>
      </div>
      <?php endif; ?>
    </aside>

    <div class="pd-text">
      <p class="eyebrow" data-reveal><?= h(t('project.about')) ?></p>
      <?php foreach ($paragraphs as $para): ?>
      <p data-reveal><?= nl2br(h($para)) ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($screens || $mobileImg): ?>
<section class="section section--tight">
  <div class="container">
    <div class="pd-gallery-head" data-reveal>
      <h2><?= h($isPhoto ? t('project.photos') : t('project.screens')) ?></h2>
      <span class="pd-gallery-head__count" data-swipe-count data-total="<?= count($screens) ?>"><?= count($screens) ?></span>
    </div>

    <?php if ($isPhoto): ?>
    <div class="pd-photos" data-reveal>
      <?php foreach ($images as $img): ?>
      <a href="<?= h($img['image_path']) ?>" class="pd-photo" data-lightbox-src="<?= h($img['image_path']) ?>" data-lightbox-alt="<?= h($title) ?>">
        <img src="<?= h($img['image_path']) ?>" alt="<?= h($title) ?>" loading="lazy">
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="pd-shots" data-reveal data-swipe>
      <?php foreach ($screens as $img): ?>
      <a href="<?= h($img['image_path']) ?>" class="pd-shot" data-lightbox-src="<?= h($img['image_path']) ?>" data-lightbox-alt="<?= h($title) ?>">
        <span class="pd-shot__bar"><i></i><i></i><i></i></span>
        <span class="pd-shot__img"><img src="<?= h($img['image_path']) ?>" alt="<?= h($title) ?>" loading="lazy"></span>
      </a>
      <?php endforeach; ?>
    </div>
    <p class="pd-swipe-hint"><?= icon('arrow-right') ?> <?= h(t('project.swipe')) ?></p>
    <?php endif; ?>

    <?php if ($mobileImg): ?>
    <div class="pd-mobile" data-reveal>
      <div class="pd-mobile__text">
        <p class="eyebrow"><?= h(t('project.mobile_eyebrow')) ?></p>
        <h3><?= h(t('project.mobile_title')) ?></h3>
        <p><?= h(t('project.mobile_text')) ?></p>
      </div>
      <div class="pd-phone" data-tilt-phone>
        <div class="pd-phone__frame">
          <i class="pd-phone__btn pd-phone__btn--a"></i><i class="pd-phone__btn pd-phone__btn--b"></i><i class="pd-phone__btn pd-phone__btn--c"></i>
          <div class="pd-phone__screen"><img src="<?= h($mobileImg) ?>" alt="<?= h($title) ?>" loading="lazy"></div>
          <span class="pd-phone__island"></span>
          <span class="pd-phone__glare"></span>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<div class="lightbox" data-lightbox>
  <button type="button" class="lightbox__close" data-lightbox-close aria-label="Zavrieť"><?= icon('x') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev aria-label="Predchádzajúci"><?= icon('arrow-right', 'icon') ?></button>
  <img src="" alt="">
  <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next aria-label="Ďalší"><?= icon('arrow-right') ?></button>
</div>

<?php if ($more): ?>
<section class="section more-projects">
  <div class="container">
    <div class="more-projects__head">
      <h2><?= h(t('project.more_title')) ?></h2>
      <a href="/portfolio" class="btn btn--ghost btn--sm"><?= h(t('project.more_all')) ?> <?= icon('arrow-right') ?></a>
    </div>
    <div class="grid portfolio-grid">
      <?php foreach ($more as $p): ?>
      <?php require __DIR__ . '/templates/project-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $ctaTitle = t('project.cta_title'); $ctaText = t('project.cta_subtitle'); $ctaButton = t('project.cta_button'); ?>
<?php require __DIR__ . '/templates/cta-band.php'; ?>

<?php $extraScripts = ['/assets/js/portfolio.js', '/assets/js/projekt.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
