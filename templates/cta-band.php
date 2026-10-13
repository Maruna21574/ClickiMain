<?php
/**
 * Záverečný CTA banner. Voliteľné premenné pred vložením: $ctaTitle, $ctaText, $ctaButton.
 * Vloženie: <?php require __DIR__ . '/templates/cta-band.php'; ?>
 */
$ctaTitle = $ctaTitle ?? t('cta.title');
$ctaText = $ctaText ?? t('cta.subtitle');
$ctaButton = $ctaButton ?? t('cta.button');
// video na pozadí: stačí nahrať assets/video/cta.mp4 (voliteľne cta-poster.jpg); bez súboru sa nezobrazí
$ctaVideo = is_file(SITE_ROOT . '/assets/video/cta.mp4');
$ctaPoster = is_file(SITE_ROOT . '/assets/video/cta-poster.jpg') ? '/assets/video/cta-poster.jpg' : '';
?>
<section class="section<?= !empty($ctaAlt) ? ' section--alt' : '' ?>">
  <div class="container">
    <div class="cta-band<?= $ctaVideo ? ' cta-band--video' : '' ?>" data-reveal>
      <?php if ($ctaVideo): ?>
      <video class="cta-band__video" autoplay muted loop playsinline preload="metadata"<?= $ctaPoster ? ' poster="' . h($ctaPoster) . '"' : '' ?> aria-hidden="true" tabindex="-1">
        <source src="/assets/video/cta.mp4" type="video/mp4">
      </video>
      <span class="cta-band__shade" aria-hidden="true"></span>
      <?php endif; ?>
      <div class="cta-band__main">
        <p class="eyebrow"><?= h(t('cta.eyebrow')) ?></p>
        <h2><?= h($ctaTitle) ?></h2>
        <p class="cta-band__text"><?= h($ctaText) ?></p>
        <div class="cta-band__actions">
          <a href="/kontakt" class="btn btn--primary"><?= h($ctaButton) ?> <?= icon('arrow-right') ?></a>
          <a href="/ponuka" class="btn btn--ghost"><?= h(t('cta.quote')) ?> <?= icon('arrow-right') ?></a>
        </div>
      </div>

      <ul class="cta-band__perks">
        <li>
          <span class="icon-tile"><?= icon('clock') ?></span>
          <div><strong><?= h(t('cta.perk1.title')) ?></strong><span><?= h(t('cta.perk1.text')) ?></span></div>
        </li>
        <li>
          <span class="icon-tile"><?= icon('shield') ?></span>
          <div><strong><?= h(t('cta.perk2.title')) ?></strong><span><?= h(t('cta.perk2.text')) ?></span></div>
        </li>
        <li>
          <span class="icon-tile"><?= icon('mail') ?></span>
          <div><strong><?= h(ADMIN_EMAIL) ?></strong><span><?= h(t('cta.perk3.text')) ?></span></div>
        </li>
      </ul>
    </div>
  </div>
</section>
