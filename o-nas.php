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
// ikony pre položky zo slovníka (poradie zodpovedá poliam values / process)
$valueIcons = ['star', 'trending-up', 'clock', 'share'];
$processIcons = ['mail', 'palette', 'code', 'shield'];

// koláž v hero: posledné zvýraznené weby z portfólia
$showcase = get_projects('web', 3, true);

// pás klientov: klienti webových projektov (bez duplicít)
$clients = array_values(array_unique(array_filter(array_column(get_projects('web'), 'client'))));

// fotka do bento karty — titulka svadobného albumu, ak existuje
$photoProject = get_project_by_slug('svadobna-fotografia');

require __DIR__ . '/templates/header.php';
?>

<section class="about-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container about-hero__grid">
    <div data-reveal>
      <p class="eyebrow"><?= h(t('about.hero.eyebrow')) ?></p>
      <h1><?= brand(t('about.hero.title')) ?></h1>
      <p class="lead about-hero__lead"><?= h(t('about.hero.subtitle')) ?></p>

      <div class="about-hero__chips">
        <?php foreach ($services as $slug => $svc): ?>
        <a href="/sluzby.php#<?= h($slug) ?>" class="chip"><?= icon($serviceIcons[$slug] ?? 'star') ?> <?= h($svc['title']) ?></a>
        <?php endforeach; ?>
      </div>

      <div class="about-hero__actions">
        <a href="/portfolio.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('about.hero.cta_portfolio')) ?></a>
        <a href="/kontakt.php" class="btn btn--ghost"><?= h(t('cta.button')) ?></a>
      </div>
    </div>

    <?php if ($showcase): ?>
    <div class="collage" data-reveal aria-hidden="true">
      <span class="collage__glow"></span>
      <?php foreach ($showcase as $p): ?>
      <a href="/projekt.php?slug=<?= urlencode($p['slug']) ?>" class="collage__item" tabindex="-1">
        <span class="collage__card"><img src="<?= h(cover_image($p)) ?>" alt="" loading="lazy"></span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php if ($clients): ?>
<section class="client-strip" aria-label="<?= h(t('about.clients.eyebrow')) ?>">
  <p class="client-strip__label"><?= h(t('about.clients.eyebrow')) ?></p>
  <div class="client-strip__viewport">
    <div class="client-strip__track">
      <?php for ($copy = 0; $copy < 2; $copy++): ?>
      <div class="client-strip__group"<?= $copy ? ' aria-hidden="true"' : '' ?>>
        <?php foreach ($clients as $c): ?>
        <span class="client-strip__name"><?= h($c) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container bento">
    <div class="bento__card bento__story" data-reveal>
      <p class="eyebrow"><?= h(t('about.story.title')) ?></p>
      <p class="bento__story-text"><?= brand(t('about.story.body')) ?></p>
    </div>

    <a href="/portfolio.php?kategoria=foto" class="bento__card bento__photo" data-reveal>
      <?php if ($photoProject): ?>
      <img src="<?= h(cover_image($photoProject)) ?>" alt="<?= h(t('about.bento.photo_title')) ?>" loading="lazy">
      <?php endif; ?>
      <span class="bento__photo-body">
        <strong><?= h(t('about.bento.photo_title')) ?></strong>
        <span><?= h(t('about.bento.photo_text')) ?></span>
        <span class="bento__link"><?= h(t('about.bento.photo_link')) ?> <?= icon('arrow-up-right') ?></span>
      </span>
    </a>

    <div class="bento__card bento__services" data-reveal>
      <h3><?= h(t('about.bento.services_title')) ?></h3>
      <ul>
        <?php foreach ($services as $slug => $svc): ?>
        <li><a href="/sluzby.php#<?= h($slug) ?>"><?= icon($serviceIcons[$slug] ?? 'star') ?> <?= h($svc['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a href="/sluzby.php" class="bento__link"><?= h(t('about.bento.services_link')) ?> <?= icon('arrow-right') ?></a>
    </div>
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

<section class="section">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(t('about.process.eyebrow')) ?></p>
      <h2><?= h(t('about.process.title')) ?></h2>
    </div>
    <ol class="timeline" data-reveal>
      <?php foreach (td('process') as $i => $step): ?>
      <li class="timeline__step">
        <span class="timeline__node"><?= icon($processIcons[$i] ?? 'check') ?></span>
        <h3><?= h($step['title']) ?></h3>
        <p><?= h($step['desc']) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
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
