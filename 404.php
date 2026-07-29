<?php
require_once __DIR__ . '/inc/functions.php';
http_response_code(404);

$pageTitle = t('error404.title') . ' — Clicki';
$activeNav = '';

require __DIR__ . '/templates/header.php';
?>

<section class="error-page">
  <span class="hero-glow" style="width:480px;height:480px;top:10%;left:50%;transform:translateX(-50%);"></span>
  <div class="container text-center" style="position:relative;z-index:1;">
    <span class="icon-tile icon-tile--chrome" style="margin-inline:auto;"><?= icon('drone') ?></span>
    <div class="error-page__code text-gradient" style="margin-top:1rem;">404</div>
    <h1 style="margin-top:1rem;"><?= h(t('error404.title')) ?></h1>
    <p class="lead" style="margin:1.2rem auto 2rem;max-width:480px;"><?= h(t('error404.subtitle')) ?></p>
    <a href="/index.php" class="btn btn--primary"><?= icon('arrow-right') ?> <?= h(t('error404.button')) ?></a>
  </div>
</section>

<?php require __DIR__ . '/templates/footer.php'; ?>
