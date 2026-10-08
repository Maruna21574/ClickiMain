<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.home_title');
$pageDesc = t('meta.home_desc');
$activeNav = 'home';

$serviceIcons = [
    'web' => 'code',
    'seo' => 'search',
    'sprava-webu' => 'shield',
    'socialne-siete' => 'share',
    'grafika' => 'palette',
    'foto' => 'camera',
];
$services = td('services');
$featured = get_projects(null, 6, true);

require __DIR__ . '/templates/header.php';
?>

<section class="hero">
  <span class="hero-glow hero__glow-a"></span>
  <span class="hero-glow hero__glow-b"></span>
  <div class="container hero__content">
    <p class="eyebrow"><?= h(t('home.hero.eyebrow')) ?></p>
    <h1 class="hero__title">
      <span class="line"><?= h(t('home.hero.title_start')) ?></span>
      <span class="line text-gradient--animated"><?= h(t('home.hero.title_highlight')) ?></span>
    </h1>
    <p class="hero__subtitle lead"><?= brand(t('home.hero.subtitle')) ?></p>
    <div class="hero__actions">
      <a href="/ponuka.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('home.hero.cta_primary')) ?></a>
      <a href="/portfolio.php" class="btn btn--ghost"><?= h(t('home.hero.cta_secondary')) ?></a>
    </div>
  </div>
  <div class="hero__scroll">
    <span class="hero__scroll-line"><span class="scroll-cue__dot" style="position:absolute;top:0;left:-2px;width:5px;height:5px;border-radius:50%;background:var(--pink);"></span></span>
    <span><?= h(t('home.hero.scroll')) ?></span>
  </div>
</section>

<div class="marquee">
  <div class="marquee__track">
    <div class="marquee__group">
      <?php for ($i = 0; $i < 2; $i++): ?>
      <?php foreach (explode('—', t('home.marquee')) as $word): if (trim($word) === '') continue; ?>
        <span><?= h(trim($word)) ?></span>
      <?php endforeach; ?>
      <?php endfor; ?>
    </div>
  </div>
</div>

<section class="section section--tight">
  <div class="container">
    <div class="grid stats-grid" data-reveal>
      <div class="stat"><div class="stat__value" data-counter><?= h(t('home.stats.1_value')) ?></div><div class="stat__label"><?= h(t('home.stats.1_label')) ?></div></div>
      <div class="stat"><div class="stat__value" data-counter><?= h(t('home.stats.2_value')) ?></div><div class="stat__label"><?= h(t('home.stats.2_label')) ?></div></div>
      <div class="stat"><div class="stat__value" data-counter><?= h(t('home.stats.3_value')) ?></div><div class="stat__label"><?= h(t('home.stats.3_label')) ?></div></div>
      <div class="stat"><div class="stat__value" data-counter><?= h(t('home.stats.4_value')) ?></div><div class="stat__label"><?= h(t('home.stats.4_label')) ?></div></div>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section-head section-head--center" data-reveal>
      <p class="eyebrow" style="justify-content:center"><?= h(t('home.services.eyebrow')) ?></p>
      <h2><?= h(t('home.services.title')) ?></h2>
      <p class="lead" style="margin-inline:auto;margin-top:1rem;"><?= h(t('home.services.subtitle')) ?></p>
    </div>

    <div class="grid services-grid" data-reveal>
      <?php foreach ($services as $slug => $svc): ?>
      <a href="/sluzby.php#<?= h($slug) ?>" class="card service-card">
        <span class="icon-tile"><?= icon($serviceIcons[$slug] ?? 'star') ?></span>
        <h3><?= h($svc['title']) ?></h3>
        <p><?= h($svc['teaser']) ?></p>
        <span class="service-card__link"><?= h(t('services.cta')) ?> <?= icon('arrow-up-right') ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top:3rem;">
      <a href="/sluzby.php" class="btn btn--chrome"><?= h(t('home.services.link')) ?> <?= icon('arrow-right') ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(t('home.process.eyebrow')) ?></p>
      <h2><?= h(t('home.process.title')) ?></h2>
    </div>
    <ol class="timeline" data-reveal>
      <?php foreach (td('process') as $i => $step): ?>
      <li class="timeline__step">
        <span class="timeline__node"><?= icon(['mail', 'palette', 'code', 'shield'][$i] ?? 'check') ?></span>
        <h3><?= h($step['title']) ?></h3>
        <p><?= h($step['desc']) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(t('home.featured.eyebrow')) ?></p>
      <h2><?= h(t('home.featured.title')) ?></h2>
      <p class="lead" style="margin-top:1rem;"><?= h(t('home.featured.subtitle')) ?></p>
    </div>

    <div class="grid portfolio-grid" data-reveal>
      <?php foreach ($featured as $p): ?>
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

    <div class="text-center" style="margin-top:3rem;">
      <a href="/portfolio.php" class="btn btn--ghost"><?= h(t('home.featured.link')) ?> <?= icon('arrow-right') ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head section-head--center" data-reveal>
      <p class="eyebrow" style="justify-content:center"><?= brand(t('home.why.eyebrow')) ?></p>
      <h2><?= h(t('home.why.title')) ?></h2>
    </div>
    <div class="grid diff-grid" data-reveal>
      <?php foreach (td('differentiators') as $d): ?>
      <div class="card diff-card">
        <span class="icon-tile"><?= icon($d['icon']) ?></span>
        <h3><?= h($d['title']) ?></h3>
        <p><?= h($d['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-band" data-reveal>
      <h2><?= h(t('cta.title')) ?></h2>
      <p><?= h(t('cta.subtitle')) ?></p>
      <a href="/kontakt.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('cta.button')) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/footer.php'; ?>
