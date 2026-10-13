<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.blog_title');
$pageDesc = t('meta.blog_desc');
$activeNav = '';

$posts = get_posts();

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('blog.hero.eyebrow')) ?></p>
    <h1><?= h(t('blog.hero.title')) ?></h1>
    <p class="lead"><?= h(t('blog.hero.subtitle')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <?php if ($posts): ?>
    <div class="grid post-grid post-grid--featured">
      <?php foreach ($posts as $post): ?>
      <?php require __DIR__ . '/templates/post-card.php'; ?>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="text-align:center;color:var(--text-faint);padding-block:3rem;"><?= h(t('blog.empty')) ?></p>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/templates/cta-band.php'; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
