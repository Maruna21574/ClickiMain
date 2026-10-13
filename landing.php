<?php
/**
 * SEO vstupná stránka (tvorba webstránok, web na mieru, tvorba e-shopov, svadobná fotografia, SEO...).
 * Obsah je v inc/landing.php, adresa /{slug} sa mapuje cez .htaccess.
 */
require_once __DIR__ . '/inc/functions.php';

$pages = landing_pages();
$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
$lp = $pages[$slug] ?? null;

if (!$lp) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$pageTitle = $lp['title'];
$pageDesc = $lp['desc'];
$activeNav = 'services';
$canonical = site_url('/' . $slug);

// projekty / galéria do sekcie "ukážky"
$showcase = [];
$gallery = [];
if (get_category_by_slug((string)$lp['projects'])) {
    $showcase = array_slice(get_projects($lp['projects']), 0, 3);
} else {
    $p = get_project_by_slug($lp['projects']);
    if ($p) {
        $gallery[] = $p['cover_image'];
        foreach (get_project_images((int)$p['id']) as $im) {
            $gallery[] = $im['image_path'];
        }
        $gallery = array_slice(array_values(array_unique(array_filter($gallery))), 0, 8);
    }
}

// štruktúrované dáta: služba, FAQ, omrvinková navigácia
$ld = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => $lp['nav'],
            'description' => $lp['desc'],
            'provider' => ['@id' => site_url('/#organization')],
            'areaServed' => 'SK',
            'url' => $canonical,
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($f) => [
                '@type' => 'Question',
                'name' => $f[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
            ], $lp['faq']),
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Clicki', 'item' => site_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => t('nav.services'), 'item' => site_url('/sluzby.php')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $lp['nav'], 'item' => $canonical],
            ],
        ],
    ],
];
$headExtra = '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . "</script>\n";

$ctaTitle = $lp['cta'];
$ctaText = t('cta.subtitle');
$ctaButton = t('cta.button');

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <a href="/">Clicki</a> <span>/</span> <a href="/sluzby.php"><?= h(t('nav.services')) ?></a> <span>/</span> <span aria-current="page"><?= h($lp['nav']) ?></span>
    </nav>
    <p class="eyebrow"><?= h($lp['eyebrow']) ?></p>
    <h1><?= h($lp['h1']) ?></h1>
    <p class="lead"><?= h($lp['lead']) ?></p>
    <div class="lp-hero__actions">
      <a href="/ponuka.php" class="btn btn--primary"><?= h($lp['cta']) ?> <?= icon('arrow-right') ?></a>
      <a href="/kontakt.php" class="btn btn--ghost"><?= h(t('nav.contact')) ?></a>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container container--narrow lp-content">
    <?php foreach ($lp['sections'] as $s): ?>
    <div class="lp-block" data-reveal>
      <h2><?= h($s['h']) ?></h2>
      <?php foreach ($s['p'] ?? [] as $para): ?><p><?= legal_inline($para) ?></p><?php endforeach; ?>
      <?php if (!empty($s['ul'])): ?>
      <ul class="lp-list">
        <?php foreach ($s['ul'] as $li): ?><li><?= icon('check') ?> <span><?= legal_inline($li) ?></span></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<?php if ($gallery): ?>
<section class="section section--tight">
  <div class="container">
    <div class="lp-gallery" data-reveal>
      <?php foreach ($gallery as $i => $img): ?>
      <img src="<?= h($img) ?>" alt="<?= h($lp['nav'] . ' — ukážka ' . ($i + 1)) ?>" loading="lazy">
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--alt">
  <div class="container">
    <div class="section-head" data-reveal>
      <h2><?= h($lp['nav']) ?>: <?= h(current_lang() === 'en' ? 'how it works' : 'ako to prebieha') ?></h2>
    </div>
    <div class="lp-steps" data-reveal>
      <?php foreach ($lp['steps'] as $i => $st): ?>
      <div class="lp-step">
        <span class="lp-step__num"><?= sprintf('%02d', $i + 1) ?></span>
        <h3><?= h($st[0]) ?></h3>
        <p><?= h($st[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($showcase): ?>
<section class="section">
  <div class="container">
    <div class="more-projects__head">
      <h2><?= h($lp['nav']) ?>: <?= h(current_lang() === 'en' ? 'our work' : 'naše realizácie') ?></h2>
      <a href="/portfolio.php" class="btn btn--ghost btn--sm"><?= h(t('project.more_all')) ?> <?= icon('arrow-right') ?></a>
    </div>
    <div class="grid portfolio-grid">
      <?php foreach ($showcase as $p): ?>
      <?php require __DIR__ . '/templates/project-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--alt">
  <div class="container--narrow">
    <div class="section-head section-head--center" data-reveal>
      <h2><?= h(current_lang() === 'en' ? 'Frequently asked questions' : 'Najčastejšie otázky') ?></h2>
    </div>
    <div class="faq-list" data-reveal>
      <?php foreach ($lp['faq'] as $item): ?>
      <div class="faq-item">
        <button type="button" class="faq-item__q" aria-expanded="false"><?= h($item[0]) ?> <?= icon('chevron-down') ?></button>
        <div class="faq-item__a"><p><?= h($item[1]) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <h2 class="lp-related__title"><?= h(current_lang() === 'en' ? 'You may also be interested in' : 'Mohlo by vás zaujímať') ?></h2>
    <div class="lp-related">
      <?php foreach ($lp['related'] as $rs): if (!isset($pages[$rs])) continue; ?>
      <a href="/<?= h($rs) ?>" class="lp-related__item">
        <strong><?= h($pages[$rs]['nav']) ?></strong>
        <span><?= h(excerpt($pages[$rs]['lead'], 110)) ?></span>
        <em><?= icon('arrow-right') ?></em>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/cta-band.php'; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
