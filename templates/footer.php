</main>

<footer class="site-footer">
  <div class="container">
    <div class="grid footer-grid">
      <div class="footer-brand">
        <img src="/assets/img/clicki_logo_white.png" alt="Clicki">
        <p><?= h(t('footer.tagline')) ?></p>
        <div class="footer-social">
          <a href="#" aria-label="Instagram"><?= icon('instagram') ?></a>
          <a href="#" aria-label="Facebook"><?= icon('facebook') ?></a>
        </div>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.nav_title')) ?></h4>
        <ul>
          <li><a href="/"><?= h(t('nav.home')) ?></a></li>
          <li><a href="/sluzby"><?= h(t('nav.services')) ?></a></li>
          <li><a href="/portfolio"><?= h(t('nav.portfolio')) ?></a></li>
          <li><a href="/o-nas"><?= h(t('nav.about')) ?></a></li>
          <li><a href="/kontakt"><?= h(t('nav.contact')) ?></a></li>
          <li><a href="/blog"><?= h(t('footer.blog')) ?></a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.services_title')) ?></h4>
        <ul>
          <?php $lpMap = ['web' => 'tvorba-webstranok', 'seo' => 'seo-optimalizacia', 'sprava-webu' => 'sprava-webu', 'socialne-siete' => 'socialne-siete', 'grafika' => 'graficky-dizajn', 'foto' => 'svadobna-fotografia']; ?>
          <?php foreach (td('services') as $slug => $svc): ?>
          <li><a href="<?= isset($lpMap[$slug]) ? '/' . h($lpMap[$slug]) : '/sluzby#' . h($slug) ?>"><?= h($svc['title']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="/web-na-mieru">Web na mieru</a></li>
          <li><a href="/tvorba-eshopov">Tvorba e-shopov</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.contact_title')) ?></h4>
        <ul>
          <li><a href="mailto:<?= h(ADMIN_EMAIL) ?>"><?= h(ADMIN_EMAIL) ?></a></li>
          <li><a href="/ponuka"><?= h(t('nav.cta')) ?></a></li>
        </ul>
      </div>
    </div>

    <nav class="footer-topics" aria-label="Témy">
      <?php foreach (landing_pages() as $tpSlug => $tp): ?>
      <a href="/<?= h($tpSlug) ?>"><?= h($tp['nav']) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= brand('Clicki') ?>. <?= h(t('footer.rights')) ?></span>
      <span><?= h(t('footer.made_with')) ?></span>
      <nav class="footer-legal" aria-label="<?= h(t('footer.privacy')) ?>">
        <a href="/gdpr"><?= h(t('footer.privacy')) ?></a>
        <a href="/cookies"><?= h(t('footer.cookies')) ?></a>
      </nav>
    </div>
  </div>
</footer>

<?php require __DIR__ . '/contact-fab.php'; ?>

<button type="button" class="scroll-top" aria-label="<?= h(t('footer.back_to_top')) ?>"><?= icon('chevron-down') ?></button>

<?php
// dáta pre rýchle vyhľadávanie (Ctrl/⌘ + K) — stránky, služby, projekty z DB a akcie
$cmdkIcons = ['web' => 'code', 'seo' => 'search', 'sprava-webu' => 'shield', 'socialne-siete' => 'share', 'grafika' => 'palette', 'foto' => 'camera'];
$cmdkItems = [
    ['g' => 'actions', 't' => t('nav.cta'), 'u' => '/ponuka', 'i' => 'arrow-right'],
    ['g' => 'actions', 't' => t('cmdk.action_email'), 's' => ADMIN_EMAIL, 'u' => 'mailto:' . ADMIN_EMAIL, 'i' => 'mail'],
    ['g' => 'actions', 't' => t('cmdk.action_lang'), 'u' => lang_url(current_lang() === 'sk' ? 'en' : 'sk'), 'i' => 'globe'],
    ['g' => 'pages', 't' => t('nav.home'), 'u' => '/', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.services'), 'u' => '/sluzby', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.portfolio'), 'u' => '/portfolio', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.about'), 'u' => '/o-nas', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.contact'), 'u' => '/kontakt', 'i' => 'layers'],
];
foreach (td('services') as $slug => $svc) {
    $cmdkItems[] = ['g' => 'services', 't' => $svc['title'], 's' => $svc['teaser'], 'u' => '/sluzby#' . $slug, 'i' => $cmdkIcons[$slug] ?? 'star'];
}
foreach (get_projects() as $p) {
    $cmdkItems[] = ['g' => 'projects', 't' => field($p, 'title'), 's' => cat_name($p) . ($p['client'] ? ' · ' . $p['client'] : ''), 'u' => '/projekt?slug=' . rawurlencode($p['slug']), 'img' => cover_image($p)];
}
$cmdkIconSvgs = [];
foreach (array_unique(array_filter(array_column($cmdkItems, 'i'))) as $name) {
    $cmdkIconSvgs[$name] = icon($name);
}
$cmdkData = [
    'items' => $cmdkItems,
    'icons' => $cmdkIconSvgs,
    'groups' => ['actions' => t('cmdk.actions'), 'pages' => t('cmdk.pages'), 'services' => t('cmdk.services'), 'projects' => t('cmdk.projects')],
    'viewLabel' => t('fx.view'),
];
?>
<div class="cmdk" data-cmdk hidden>
  <div class="cmdk__backdrop" data-cmdk-close></div>
  <div class="cmdk__panel" role="dialog" aria-modal="true" aria-label="<?= h(t('cmdk.open')) ?>">
    <div class="cmdk__search">
      <?= icon('search') ?>
      <input type="text" class="cmdk__input" placeholder="<?= h(t('cmdk.placeholder')) ?>" autocomplete="off" spellcheck="false" role="combobox" aria-expanded="true" aria-controls="cmdk-list" aria-autocomplete="list">
      <kbd>Esc</kbd>
    </div>
    <div class="cmdk__list" id="cmdk-list" role="listbox"></div>
    <p class="cmdk__empty" hidden><?= h(t('cmdk.empty')) ?></p>
    <div class="cmdk__foot">
      <span><kbd>↑</kbd><kbd>↓</kbd> <?= h(t('cmdk.hint_nav')) ?></span>
      <span><kbd>Enter</kbd> <?= h(t('cmdk.hint_open')) ?></span>
      <span><kbd>Esc</kbd> <?= h(t('cmdk.hint_close')) ?></span>
    </div>
  </div>
</div>
<script type="application/json" id="cmdk-data"><?= json_encode($cmdkData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>


<div class="cookie-bar" data-cookie-bar role="dialog" aria-live="polite" aria-label="<?= h(t('cookie.title')) ?>" hidden>
  <div class="cookie-bar__text">
    <strong><?= h(t('cookie.title')) ?></strong>
    <p><?= h(t('cookie.text')) ?> <a href="/cookies"><?= h(t('cookie.more')) ?></a></p>
  </div>
  <div class="cookie-bar__actions">
    <button type="button" class="btn btn--ghost btn--sm" data-cookie-choice="necessary"><?= h(t('cookie.necessary')) ?></button>
    <button type="button" class="btn btn--primary btn--sm" data-cookie-choice="all"><?= h(t('cookie.accept')) ?></button>
  </div>
</div>
<script src="<?= asset('/assets/js/consent.js') ?>" defer></script>
<script src="<?= asset('/assets/js/contact-fab.js') ?>" defer></script>
<script src="<?= asset('/assets/js/main.js') ?>" defer></script>
<script src="<?= asset('/assets/js/fx.js') ?>" defer></script>
<?php if (!empty($extraScripts)) foreach ($extraScripts as $src): ?>
<script src="<?= h($src) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
