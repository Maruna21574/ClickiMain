<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.services_title');
$pageDesc = t('meta.services_desc');
$activeNav = 'services';

$services = td('services');

// obrázky pre vizuály pri službách — berú sa z portfólia, chýbajúce projekty sa preskočia
$cover = function (string $slug): ?array {
    $p = get_project_by_slug($slug);
    if (!$p || empty($p['cover_image'])) return null;
    return ['src' => $p['cover_image'], 'url' => preg_replace('#^https?://(www\.)?#i', '', rtrim($p['live_url'] ?? '', '/')), 'id' => (int)$p['id']];
};
$photoSlugs = ['svadobna-fotografia', 'portretna-fotografia', 'eventova-fotografia', 'rodinna-fotografia', 'tehotenska-fotografia', 'atelierova-fotografia'];
$photoCovers = array_values(array_filter(array_map($cover, $photoSlugs)));
// 12 fotiek do mriežky + 4 do "stories": titulky albumov doplnené fotkami zo svadobného albumu
$social = array_column($photoCovers, 'src');
if ($photoCovers) {
    foreach (array_slice(get_project_images($photoCovers[0]['id']), 0, 16 - count($social)) as $im) {
        $social[] = $im['image_path'];
    }
}
$photoBySlug = array_column(array_filter(array_map(fn($s) => ($c = $cover($s)) ? ['slug' => $s, 'src' => $c['src']] : null, $photoSlugs)), 'src', 'slug');
$media = [
    'web' => array_values(array_filter([$cover('hotel-hradok'), $cover('regen-zilina-masaze')])),
    'seo_bg' => $cover('makiva-epoxidovy-nabytok')['src'] ?? null,
    'social' => array_slice($social, 0, 12),
    'stories' => array_slice($social, 12, 4),
    'photos' => array_values(array_filter([$photoBySlug['svadobna-fotografia'] ?? null, $photoBySlug['portretna-fotografia'] ?? null, $photoBySlug['rodinna-fotografia'] ?? null])),
];

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('services.hero.eyebrow')) ?></p>
    <h1><?= h(t('services.hero.title')) ?></h1>
    <p class="lead"><?= h(t('services.hero.subtitle')) ?></p>
  </div>
</section>

<div class="container">
  <?php foreach ($services as $slug => $svc): ?>
  <section class="service-detail" id="<?= h($slug) ?>">
    <div class="service-detail__grid">
      <div data-reveal>
        <span class="service-detail__kicker"><?= h($svc['kicker']) ?></span>
        <h2><?= h($svc['title']) ?></h2>
        <p class="service-detail__lead"><?= h($svc['lead']) ?></p>

        <ul class="check-list">
          <?php foreach ($svc['bullets'] as $b): ?>
          <li><?= icon('check') ?> <span><?= h($b) ?></span></li>
          <?php endforeach; ?>
        </ul>

        <div class="deliverables-box">
          <h4><?= h(t('services.deliverables_title')) ?></h4>
          <ul>
            <?php foreach ($svc['deliverables'] as $d): ?>
            <li><?= h($d) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <a href="/kontakt.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('services.cta')) ?></a>
      </div>

      <div class="service-detail__visual" data-reveal>
        <?php require __DIR__ . '/templates/service-visual.php'; ?>
      </div>
    </div>
  </section>
  <?php endforeach; ?>
</div>

<section class="section">
  <div class="container">
    <div class="section-head section-head--center" data-reveal>
      <p class="eyebrow" style="justify-content:center"><?= h(t('services.tiers.eyebrow')) ?></p>
      <h2><?= h(t('services.tiers.title')) ?></h2>
      <p class="lead" style="margin-inline:auto;margin-top:1rem;"><?= h(t('services.tiers.subtitle')) ?></p>
    </div>
    <div class="grid tiers-grid" data-reveal>
      <?php foreach (td('tiers') as $i => $tier): ?>
      <div class="card tier-card">
        <span class="tier-card__tag">0<?= $i + 1 ?></span>
        <h3><?= h($tier['title']) ?></h3>
        <p><?= h($tier['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="cta-band" data-reveal>
      <h2><?= h(t('cta.title')) ?></h2>
      <p><?= h(t('cta.subtitle')) ?></p>
      <a href="/kontakt.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('cta.button')) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/footer.php'; ?>
