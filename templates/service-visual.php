<?php
/**
 * Vizuál pri službe na sluzby.php. Očakáva:
 *   $slug  — kľúč služby (web, seo, sprava-webu, socialne-siete, grafika, foto)
 *   $media — obrázky z portfólia pripravené v sluzby.php (chýbajúce sa jednoducho vynechajú)
 */
$img = fn(?string $src, string $class = '') => $src ? '<img src="' . h($src) . '" alt="" loading="lazy"' . ($class ? ' class="' . $class . '"' : '') . '>' : '';
$browser = function (?string $src, string $url, string $class) use ($img) {
    if (!$src) return '';
    return '<div class="sv-browser ' . $class . '"><div class="sv-browser__bar"><i></i><i></i><i></i><span>' . h($url) . '</span></div>' . $img($src) . '</div>';
};
?>
<div class="sv sv--<?= h($slug) ?>" aria-hidden="true">
<?php switch ($slug):
case 'web': ?>
  <?= $browser($media['web'][0]['src'] ?? null, $media['web'][0]['url'] ?? '', 'sv-web__a') ?>
  <?= $browser($media['web'][1]['src'] ?? null, $media['web'][1]['url'] ?? '', 'sv-web__b') ?>
  <span class="sv-chip sv-web__chip1"><?= icon('check') ?> <?= h(t('sv.web.chip1')) ?></span>
  <span class="sv-chip sv-web__chip2"><?= icon('check') ?> <?= h(t('sv.web.chip2')) ?></span>
<?php break;

case 'seo': ?>
  <?= $img($media['seo_bg'] ?? null, 'sv-bg') ?>
  <div class="sv-card sv-serp">
    <div class="sv-serp__search"><?= icon('search') ?><span><?= h(t('sv.seo.query')) ?></span></div>
    <div class="sv-serp__result">
      <span class="sv-serp__rank">#1</span>
      <div class="sv-serp__site"><span class="sv-serp__fav"></span><?= h(t('sv.seo.domain')) ?></div>
      <strong><?= h(t('sv.seo.title')) ?></strong>
      <p><?= h(t('sv.seo.desc')) ?></p>
    </div>
    <div class="sv-serp__ghost"><i></i><i></i><i></i></div>
  </div>
  <div class="sv-card sv-speed">
    <p class="sv-card__title"><?= h(t('sv.seo.speed')) ?></p>
    <div class="sv-speed__gauges">
      <?php foreach ([['sv.seo.perf', 98], ['sv.seo.a11y', 100], ['sv.seo.best', 100], ['sv.seo.seo', 100]] as [$label, $score]): ?>
      <div class="sv-gauge" style="--v: <?= $score ?>"><span><?= $score ?></span><small><?= h(t($label)) ?></small></div>
      <?php endforeach; ?>
    </div>
  </div>
<?php break;

case 'sprava-webu': ?>
  <div class="sv-card sv-status">
    <div class="sv-status__head">
      <strong><?= h(t('sv.care.title')) ?></strong>
      <span class="sv-status__online"><i></i><?= h(t('sv.care.online')) ?></span>
    </div>
    <?php foreach ([['sv.care.backup', 'sv.care.backup_val', 'layers'], ['sv.care.updates', 'sv.care.updates_val', 'trending-up'], ['sv.care.ssl', 'sv.care.ssl_val', 'shield'], ['sv.care.security', 'sv.care.security_val', 'check']] as [$k, $v, $ic]): ?>
    <div class="sv-status__row">
      <span class="sv-status__icon"><?= icon($ic) ?></span>
      <span class="sv-status__label"><?= h(t($k)) ?></span>
      <span class="sv-status__val"><?= h(t($v)) ?></span>
    </div>
    <?php endforeach; ?>
    <p class="sv-status__uptime-label"><?= h(t('sv.care.uptime')) ?> <b>99,98 %</b></p>
    <div class="sv-status__bars"><?php for ($i = 0; $i < 30; $i++): ?><i<?= $i === 17 ? ' class="is-dip"' : '' ?>></i><?php endfor; ?></div>
  </div>
  <span class="sv-toast"><?= icon('check') ?> <?= h(t('sv.care.toast')) ?></span>
<?php break;

case 'socialne-siete': ?>
  <div class="sv-phone">
    <div class="sv-phone__notch"></div>
    <div class="sv-ig">
      <div class="sv-ig__head">
        <span class="sv-ig__avatar"><img src="/assets/img/clicki_favicon_small.png" alt=""></span>
        <div><p class="sv-ig__name">clicki.sk</p><small class="sv-ig__bio"><?= h(t('footer.tagline')) ?></small></div>
      </div>
      <?php if (!empty($media['stories'])): ?>
      <div class="sv-ig__stories">
        <?php foreach ($media['stories'] as $src): ?>
        <span class="sv-ig__story"><?= $img($src) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="sv-ig__grid">
        <?php foreach ($media['social'] ?? [] as $src): ?>
        <?= $img($src) ?>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="sv-phone__nav"><i></i><i></i><i></i><i></i><i></i></div>
  </div>
  <span class="sv-chip sv-social__chip"><?= icon('share') ?> <?= h(t('sv.social.chip')) ?></span>
<?php break;

case 'grafika': ?>
  <div class="sv-brand">
    <div class="sv-brand__tile sv-brand__logo"><img src="/assets/img/clicki_logo_white.png" alt=""></div>
    <div class="sv-brand__tile sv-brand__mark"><img src="/assets/img/clicki_hero.png" alt=""></div>
    <div class="sv-brand__tile sv-brand__swatch sv-brand__swatch--pink"><small><?= h(t('sv.brand.primary')) ?></small><b>#F43182</b></div>
    <div class="sv-brand__tile sv-brand__swatch sv-brand__swatch--black"><small><?= h(t('sv.brand.base')) ?></small><b>#0B0B0D</b></div>
    <div class="sv-brand__tile sv-brand__swatch sv-brand__swatch--chrome"><small><?= h(t('sv.brand.chrome')) ?></small><b>Gradient</b></div>
    <div class="sv-brand__tile sv-brand__type"><small><?= h(t('sv.brand.type')) ?></small><span class="sv-brand__aa">Aa</span><em>Space Grotesk · Inter</em></div>
  </div>
<?php break;

case 'foto': ?>
  <?php foreach (array_slice($media['photos'] ?? [], 0, 3) as $i => $src): ?>
  <div class="sv-photo sv-photo--<?= $i + 1 ?>"><?= $img($src) ?></div>
  <?php endforeach; ?>
  <span class="sv-chip sv-photo__credit"><?= icon('camera') ?> <?= h(t('sv.photo.credit')) ?></span>
<?php break;

default: ?>
  <?= icon('star', 'icon icon-big') ?>
<?php endswitch; ?>
</div>
