<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.about_title');
$pageDesc = t('meta.about_desc');
$activeNav = 'about';

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('about.hero.eyebrow')) ?></p>
    <h1><?= h(t('about.hero.title')) ?></h1>
    <p class="lead"><?= h(t('about.hero.subtitle')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="about-story">
      <div data-reveal>
        <p class="eyebrow"><?= h(t('about.story.title')) ?></p>
        <p class="lead"><?= h(t('about.story.body')) ?></p>
      </div>
      <div class="about-story__visual" data-reveal>
        <img src="/assets/img/clicki_hero.png" alt="Clicki" style="width:55%;">
      </div>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section-head" data-reveal>
      <p class="eyebrow"><?= h(t('about.values.eyebrow')) ?></p>
      <h2><?= h(t('about.values.title')) ?></h2>
    </div>
    <div class="grid value-grid" data-reveal>
      <?php foreach (td('values') as $i => $v): ?>
      <div class="card value-card">
        <span class="value-card__num">0<?= $i + 1 ?></span>
        <div>
          <h3 style="font-size:var(--fs-lg);margin-bottom:.5rem;"><?= h($v['title']) ?></h3>
          <p><?= h($v['desc']) ?></p>
        </div>
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
    <div class="grid process-grid" data-reveal>
      <?php foreach (td('process') as $step): ?>
      <div class="process-step">
        <h3><?= h($step['title']) ?></h3>
        <p><?= h($step['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section-head section-head--center" data-reveal>
      <p class="eyebrow" style="justify-content:center"><?= h(t('about.team.eyebrow')) ?></p>
      <h2><?= h(t('about.team.title')) ?></h2>
      <p class="lead" style="margin-inline:auto;margin-top:1rem;"><?= h(t('about.team.subtitle')) ?></p>
    </div>
    <div class="grid team-grid" style="grid-template-columns:repeat(3,1fr);" data-reveal>
      <?php foreach (td('team') as $member): ?>
      <div class="card team-card">
        <div class="team-card__avatar"><?= h($member['initials']) ?></div>
        <h3><?= h($member['role']) ?></h3>
        <p><?= h($member['desc']) ?></p>
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
