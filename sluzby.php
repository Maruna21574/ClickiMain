<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.services_title');
$pageDesc = t('meta.services_desc');
$activeNav = 'services';

$services = td('services');
$serviceIcons = [
    'web' => 'code',
    'seo' => 'search',
    'sprava-webu' => 'shield',
    'socialne-siete' => 'share',
    'grafika' => 'palette',
    'foto' => 'camera',
];
// video v hero: stačí nahrať assets/video/services.mp4 (voliteľne services-poster.jpg), bez súboru sa nezobrazí
$heroVideo = is_file(SITE_ROOT . '/assets/video/services.mp4');
$heroPoster = is_file(SITE_ROOT . '/assets/video/services-poster.jpg') ? '/assets/video/services-poster.jpg' : '';

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

<section class="page-hero<?= $heroVideo ? ' page-hero--video' : '' ?>">
  <?php if ($heroVideo): ?>
  <video class="page-hero__video" autoplay muted loop playsinline preload="metadata"<?= $heroPoster ? ' poster="' . h($heroPoster) . '"' : '' ?> aria-hidden="true" tabindex="-1">
    <source src="/assets/video/services.mp4" type="video/mp4">
  </video>
  <span class="page-hero__shade" aria-hidden="true"></span>
  <?php else: ?>
  <span class="hero-glow page-hero__glow"></span>
  <?php endif; ?>
  <div class="container">
    <p class="eyebrow"><?= h(t('services.hero.eyebrow')) ?></p>
    <h1><?= h(t('services.hero.title')) ?></h1>
    <p class="lead"><?= h(t('services.hero.subtitle')) ?></p>
  </div>
</section>

<nav class="svc-nav" data-svc-nav aria-label="<?= h(t('services.nav')) ?>">
  <div class="container svc-nav__inner">
    <?php foreach ($services as $slug => $svc): ?>
    <a href="#<?= h($slug) ?>" class="svc-nav__link" data-svc-link="<?= h($slug) ?>"><?= icon($serviceIcons[$slug] ?? 'star') ?> <span><?= h($svc['title']) ?></span></a>
    <?php endforeach; ?>
  </div>
</nav>

<div class="container">
  <?php $svcNo = 0; foreach ($services as $slug => $svc): $svcNo++; ?>
  <section class="service-detail" id="<?= h($slug) ?>">
    <div class="service-detail__grid">
      <div data-reveal>
        <span class="service-detail__num" aria-hidden="true"><?= sprintf('%02d', $svcNo) ?></span>
        <h2><?= h($svc['title']) ?></h2>
        <p class="service-detail__lead"><?= h($svc['lead']) ?></p>

        <div class="svc-tabs" data-svc-tabs>
          <div class="svc-tabs__bar" role="tablist">
            <button type="button" role="tab" class="is-active" aria-selected="true"><?= h(t('services.bullets_title')) ?> <b><?= count($svc['bullets']) ?></b></button>
            <button type="button" role="tab" aria-selected="false"><?= h(t('services.deliverables_title')) ?> <b><?= count($svc['deliverables']) ?></b></button>
          </div>
          <div class="svc-tabs__panels">
            <div class="svc-tabs__panel is-active" role="tabpanel">
              <h4 class="svc-tabs__h"><?= h(t('services.bullets_title')) ?></h4>
              <ul class="check-list">
                <?php foreach ($svc['bullets'] as $b): ?>
                <li><?= icon('check') ?> <span><?= h($b) ?></span></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="svc-tabs__panel" role="tabpanel">
              <h4 class="svc-tabs__h"><?= h(t('services.deliverables_title')) ?></h4>
              <ul class="svc-deliver">
                <?php foreach ($svc['deliverables'] as $d): ?>
                <li><?= h($d) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>

        <div class="service-detail__actions">
          <a href="/kontakt.php" class="btn btn--primary"><?= h(t('services.cta')) ?> <?= icon('arrow-right') ?></a>
          <?php $lpMap = ['web' => 'tvorba-webstranok', 'seo' => 'seo-optimalizacia', 'sprava-webu' => 'sprava-webu', 'socialne-siete' => 'socialne-siete', 'grafika' => 'graficky-dizajn', 'foto' => 'svadobna-fotografia']; ?>
          <?php if (isset($lpMap[$slug])): ?><a href="/<?= h($lpMap[$slug]) ?>" class="btn btn--ghost"><?= h(landing_pages()[$lpMap[$slug]]['nav']) ?> <?= icon('arrow-right') ?></a><?php endif; ?>
        </div>
      </div>

      <div class="service-detail__visual" data-reveal>
        <?php require __DIR__ . '/templates/service-visual.php'; ?>
      </div>
    </div>
  </section>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/templates/cta-band.php'; ?>

<?php require __DIR__ . '/templates/footer.php'; ?>
