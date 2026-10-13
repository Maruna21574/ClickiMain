<?php
/**
 * Slider recenzií v štýle Google (dáta v inc/reviews.php).
 * Vloženie: <?php require __DIR__ . '/templates/reviews.php'; ?>  + skript /assets/js/reviews.js
 */
$reviewsCfg = require __DIR__ . '/../inc/reviews.php';
if (empty($reviewsCfg['enabled']) || empty($reviewsCfg['items'])) {
    return;
}
$reviewItems = $reviewsCfg['items'];
$avg = array_sum(array_column($reviewItems, 'stars')) / count($reviewItems);

$gLogo = '<svg class="g-logo" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';
$stars = function (float $n): string {
    $out = '<span class="stars" role="img" aria-label="' . h(number_format($n, 1, ',', '')) . ' / 5">';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<svg viewBox="0 0 24 24" class="star' . ($i <= round($n) ? ' is-on' : '') . '" aria-hidden="true"><path d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3 6.1 20.6l1.3-6.6L2.5 9.4l6.6-.8L12 2.5z"/></svg>';
    }
    return $out . '</span>';
};
?>
<section class="section reviews" data-reviews>
  <div class="container">
    <div class="reviews__head" data-reveal>
      <div>
        <p class="eyebrow"><?= h(t('reviews.eyebrow')) ?></p>
        <h2><?= h(t('reviews.title')) ?></h2>
      </div>
      <div class="reviews__badge">
        <?= $gLogo ?>
        <div>
          <strong><?= h(number_format($avg, 1, ',', '')) ?></strong>
          <?= $stars($avg) ?>
          <span class="reviews__badge-label"><?= h(t('reviews.on_google')) ?></span>
        </div>
      </div>
    </div>

    <div class="reviews__slider" data-reveal>
      <div class="reviews__track" data-reviews-track tabindex="0" aria-label="<?= h(t('reviews.title')) ?>">
        <?php foreach ($reviewItems as $r): ?>
        <article class="review">
          <header class="review__top">
            <span class="review__avatar" style="background:<?= h($r['color']) ?>"><?= h(mb_strtoupper(mb_substr($r['name'], 0, 1))) ?></span>
            <div>
              <strong class="review__name"><?= h($r['name']) ?></strong>
              <span class="review__time"><?= h(pl($r['time'])) ?></span>
            </div>
            <?= $gLogo ?>
          </header>
          <?= $stars((float)$r['stars']) ?>
          <p class="review__text"><?= h(pl($r['text'])) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
      <div class="reviews__nav">
        <button type="button" data-reviews-prev aria-label="<?= h(t('reviews.prev')) ?>"><?= icon('arrow-right') ?></button>
        <button type="button" data-reviews-next aria-label="<?= h(t('reviews.next')) ?>"><?= icon('arrow-right') ?></button>
      </div>
    </div>

    <?php if (!empty($reviewsCfg['url'])): ?>
    <p class="reviews__more"><a href="<?= h($reviewsCfg['url']) ?>" target="_blank" rel="noopener" class="btn btn--ghost"><?= h(t('reviews.all')) ?> <?= icon('arrow-up-right') ?></a></p>
    <?php endif; ?>
  </div>
</section>
