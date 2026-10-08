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
          <a href="#" aria-label="LinkedIn"><?= icon('linkedin') ?></a>
          <a href="#" aria-label="YouTube"><?= icon('youtube') ?></a>
        </div>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.nav_title')) ?></h4>
        <ul>
          <li><a href="/index.php"><?= h(t('nav.home')) ?></a></li>
          <li><a href="/sluzby.php"><?= h(t('nav.services')) ?></a></li>
          <li><a href="/portfolio.php"><?= h(t('nav.portfolio')) ?></a></li>
          <li><a href="/o-nas.php"><?= h(t('nav.about')) ?></a></li>
          <li><a href="/kontakt.php"><?= h(t('nav.contact')) ?></a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.services_title')) ?></h4>
        <ul>
          <?php foreach (td('services') as $slug => $svc): ?>
          <li><a href="/sluzby.php#<?= h($slug) ?>"><?= h($svc['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= h(t('footer.contact_title')) ?></h4>
        <ul>
          <li><a href="mailto:<?= h(ADMIN_EMAIL) ?>"><?= h(ADMIN_EMAIL) ?></a></li>
          <li><a href="/ponuka.php"><?= h(t('nav.cta')) ?></a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= brand('Clicki') ?>. <?= h(t('footer.rights')) ?></span>
      <span><?= h(t('footer.made_with')) ?></span>
      <a href="#main" class="back-to-top"><?= icon('chevron-down', 'icon') ?> <?= h(t('footer.back_to_top')) ?></a>
    </div>
  </div>
</footer>

<button type="button" class="scroll-top" aria-label="<?= h(t('footer.back_to_top')) ?>"><?= icon('chevron-down') ?></button>

<?php
// dáta pre rýchle vyhľadávanie (Ctrl/⌘ + K) — stránky, služby, projekty z DB a akcie
$cmdkIcons = ['web' => 'code', 'seo' => 'search', 'sprava-webu' => 'shield', 'socialne-siete' => 'share', 'grafika' => 'palette', 'foto' => 'camera'];
$cmdkItems = [
    ['g' => 'actions', 't' => t('nav.cta'), 'u' => '/ponuka.php', 'i' => 'arrow-right'],
    ['g' => 'actions', 't' => t('cmdk.action_email'), 's' => ADMIN_EMAIL, 'u' => 'mailto:' . ADMIN_EMAIL, 'i' => 'mail'],
    ['g' => 'actions', 't' => t('cmdk.action_lang'), 'u' => lang_url(current_lang() === 'sk' ? 'en' : 'sk'), 'i' => 'globe'],
    ['g' => 'pages', 't' => t('nav.home'), 'u' => '/', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.services'), 'u' => '/sluzby.php', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.portfolio'), 'u' => '/portfolio.php', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.about'), 'u' => '/o-nas.php', 'i' => 'layers'],
    ['g' => 'pages', 't' => t('nav.contact'), 'u' => '/kontakt.php', 'i' => 'layers'],
];
foreach (td('services') as $slug => $svc) {
    $cmdkItems[] = ['g' => 'services', 't' => $svc['title'], 's' => $svc['teaser'], 'u' => '/sluzby.php#' . $slug, 'i' => $cmdkIcons[$slug] ?? 'star'];
}
foreach (get_projects() as $p) {
    $cmdkItems[] = ['g' => 'projects', 't' => field($p, 'title'), 's' => cat_name($p) . ($p['client'] ? ' · ' . $p['client'] : ''), 'u' => '/projekt.php?slug=' . rawurlencode($p['slug']), 'img' => cover_image($p)];
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

<script src="/assets/js/main.js" defer></script>
<script src="/assets/js/fx.js" defer></script>
<?php if (!empty($extraScripts)) foreach ($extraScripts as $src): ?>
<script src="<?= h($src) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
