<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.about_title');
$pageDesc = t('meta.about_desc');
$activeNav = 'about';

$services = td('services');
$serviceIcons = [
    'web' => 'code',
    'seo' => 'search',
    'sprava-webu' => 'shield',
    'socialne-siete' => 'share',
    'grafika' => 'palette',
    'foto' => 'camera',
];
// ikony pre položky zo slovníka (poradie zodpovedá poľu values)
$valueIcons = ['star', 'trending-up', 'clock', 'share'];


// koláž v hero: posledné zvýraznené weby z portfólia
$showcase = get_projects('web', 3, true);


require __DIR__ . '/templates/header.php';
?>

<?php $heroVideoName = 'about'; require __DIR__ . '/templates/hero-video.php'; ?>
<section class="about-hero<?= $heroHasVideo ? ' page-hero--video' : '' ?>">
  <?php if ($heroHasVideo): require __DIR__ . '/templates/hero-video-media.php'; else: ?>
  <span class="hero-glow page-hero__glow"></span>
  <?php endif; ?>
  <canvas class="hero__bg" data-hero-bg aria-hidden="true"></canvas>
  <div class="container about-hero__grid">
    <div data-reveal>
      <p class="eyebrow"><?= h(t('about.hero.eyebrow')) ?></p>
      <h1><?= brand(t('about.hero.title')) ?></h1>
      <p class="lead about-hero__lead"><?= h(t('about.hero.subtitle')) ?></p>

      <div class="about-hero__actions">
        <a href="/portfolio" class="btn btn--primary"><?= h(t('about.hero.cta_portfolio')) ?> <?= icon('arrow-right') ?></a>
        <a href="/kontakt" class="btn btn--ghost"><?= h(t('cta.button')) ?> <?= icon('arrow-right') ?></a>
      </div>
    </div>

    <?php if ($showcase): ?>
    <div class="collage" data-reveal aria-hidden="true">
      <span class="collage__glow"></span>
      <?php foreach ($showcase as $p): ?>
      <a href="/projekt?slug=<?= urlencode($p['slug']) ?>" class="collage__item" tabindex="-1">
        <span class="collage__card"><img src="<?= h(cover_image($p)) ?>" alt="" loading="lazy"></span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(t('about.values.eyebrow')) ?></p>
      <h2><?= h(t('about.values.title')) ?></h2>
    </div>
    <div class="grid principles" data-reveal>
      <?php foreach (td('values') as $i => $v): ?>
      <div class="principle">
        <span class="icon-tile"><?= icon($valueIcons[$i] ?? 'star') ?></span>
        <h3><?= h($v['title']) ?></h3>
        <p><?= h($v['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/reviews.php'; ?>

<?php require __DIR__ . '/templates/cta-band.php'; ?>

<?php $extraScripts = ['/assets/js/reviews.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
