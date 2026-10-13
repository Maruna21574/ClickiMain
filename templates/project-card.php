<?php
/**
 * Karta projektu (úvod, portfólio, podobné projekty).
 * Očakáva $p (riadok projektu); voliteľne $cardAttr = hotové (už escapované) HTML atribúty.
 *
 * Pri weboch sa pod titulnou fotkou skrýva "pás" screenshotov z galérie — pri nabehnutí
 * myšou sa pomaly posúva hore, ako keby sa web scrolloval (CSS + fx.js).
 */
$cardAttr = $cardAttr ?? '';
$cover = cover_image($p);
$strip = [];
if (($p['category_slug'] ?? '') === 'web') {
    $strip = array_slice(array_column(get_project_images((int)$p['id']), 'image_path'), 0, 5);
}
?>
<a href="/projekt.php?slug=<?= urlencode($p['slug']) ?>" class="project-card" <?= $cardAttr ?>>
  <div class="project-card__media">
    <div class="project-card__inner">
      <img src="<?= h($cover) ?>" alt="<?= h(field($p, 'title')) ?>" loading="lazy">
      <?php if ($strip): ?>
      <div class="project-card__strip" data-strip style="--n: <?= count($strip) + 1 ?>" aria-hidden="true">
        <img src="<?= h($cover) ?>" alt="">
        <?php foreach ($strip as $src): ?><img data-src="<?= h($src) ?>" alt=""><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="project-card__body">
    <span class="project-card__cat"><?= h(cat_name($p)) ?></span>
    <h3 class="project-card__title"><?= h(field($p, 'title')) ?></h3>
    <div class="project-card__meta"><span><?= h($p['client']) ?></span></div>
  </div>
</a>
