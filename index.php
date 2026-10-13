<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.home_title');
$pageDesc = t('meta.home_desc');
$activeNav = 'home';

$homeFaq = [
    ['Koľko stojí tvorba webstránky?', 'Cena závisí od rozsahu, počtu podstránok a funkcií. Jednoduchú webstránku vieme postaviť výhodne, web na mieru alebo e-shop stoja viac. Nezáväznú cenovú ponuku dostanete do 12 hodín, odhad si viete naklikať aj v konfigurátore.'],
    ['Robíte weby na mieru alebo len podľa šablón?', 'Robíme weby na mieru: vlastný dizajn a čistý kód bez zbytočných doplnkov, vďaka čomu sú rýchle a jedinečné.'],
    ['Postavíte aj e-shop?', 'Áno. Tvorba e-shopov na mieru zahŕňa platobnú bránu, dopravcov, faktúry aj SEO. Pomôžeme vám zvoliť riešenie podľa počtu produktov.'],
    ['Ako dlho trvá tvorba webu?', 'Jednoduchý web býva hotový za 2 až 4 týždne, rozsiahlejší web alebo e-shop za 4 až 8 týždňov.'],
    ['Robíte aj svadobnú fotografiu?', 'Áno, ponúkame svadobného fotografa aj portrétne a rodinné fotenie po celom Slovensku.'],
    ['Pôsobíte aj mimo vášho mesta?', 'Pracujeme na diaľku pre klientov z celého Slovenska. Konzultácia prebieha telefonicky alebo online, fotenie vieme dohodnúť priamo u vás.'],
];
$headExtra = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], $homeFaq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . "</script>\n";

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
  <canvas class="hero__bg" data-hero-bg aria-hidden="true"></canvas>
  <div class="container hero__content">
    <p class="eyebrow"><?= h(t('home.hero.eyebrow')) ?></p>
    <h1 class="hero__title">
      <span class="line"><?= h(t('home.hero.title_start')) ?></span>
      <span class="line text-gradient--animated"><?= h(t('home.hero.title_highlight')) ?></span>
    </h1>
    <p class="hero__subtitle lead"><?= brand(t('home.hero.subtitle')) ?></p>
    <div class="hero__actions">
      <a href="/ponuka" class="btn btn--primary"><?= h(t('home.hero.cta_primary')) ?> <?= icon('arrow-right') ?></a>
      <a href="/portfolio" class="btn btn--ghost"><?= h(t('home.hero.cta_secondary')) ?> <?= icon('arrow-right') ?></a>
    </div>
  </div>
  <div class="hero__scroll">
    <span class="hero__scroll-line"><span class="scroll-cue__dot" style="position:absolute;top:0;left:-2px;width:5px;height:5px;border-radius:50%;background:var(--pink);"></span></span>
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
      <a href="/sluzby#<?= h($slug) ?>" class="card service-card">
        <span class="icon-tile"><?= icon($serviceIcons[$slug] ?? 'star') ?></span>
        <h3><?= h($svc['title']) ?></h3>
        <p><?= h($svc['teaser']) ?></p>
        <span class="service-card__link"><?= h(t('services.cta')) ?> <?= icon('arrow-up-right') ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top:3rem;">
      <a href="/sluzby" class="btn btn--chrome"><?= h(t('home.services.link')) ?> <?= icon('arrow-right') ?></a>
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
      <?php require __DIR__ . '/templates/project-card.php'; ?>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top:3rem;">
      <a href="/portfolio" class="btn btn--ghost"><?= h(t('home.featured.link')) ?> <?= icon('arrow-right') ?></a>
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

<section class="section section--tight">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(current_lang() === 'en' ? 'What we are asked for most' : 'Najčastejšie hľadané') ?></p>
      <h2><?= h(current_lang() === 'en' ? 'Websites, e-shops, SEO and photography' : 'Tvorba webstránok, e-shopov, SEO a svadobná fotografia') ?></h2>
    </div>
    <div class="lp-related lp-related--wide" data-reveal>
      <?php foreach (array_intersect_key(landing_pages(), array_flip(['tvorba-webstranok', 'web-na-mieru', 'tvorba-eshopov', 'svadobna-fotografia', 'seo-optimalizacia', 'sprava-webu'])) as $lpSlug => $lpItem): ?>
      <a href="/<?= h($lpSlug) ?>" class="lp-related__item">
        <strong><?= h($lpItem['nav']) ?></strong>
        <span><?= h(excerpt($lpItem['lead'], 120)) ?></span>
        <em><?= icon('arrow-right') ?></em>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/reviews.php'; ?>

<section class="section section--alt">
  <div class="container--narrow">
    <div class="section-head section-head--center" data-reveal>
      <h2>Najčastejšie otázky o tvorbe webov a e-shopov</h2>
    </div>
    <div class="faq-list" data-reveal>
      <?php foreach ($homeFaq as $item): ?>
      <div class="faq-item">
        <button type="button" class="faq-item__q" aria-expanded="false"><?= h($item[0]) ?> <?= icon('chevron-down') ?></button>
        <div class="faq-item__a"><p><?= h($item[1]) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/cta-band.php'; ?>

<?php $extraScripts = ['/assets/js/reviews.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
