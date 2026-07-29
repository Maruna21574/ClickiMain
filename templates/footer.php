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
          <li><a href="/kontakt.php"><?= h(t('nav.cta')) ?></a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> Clicki. <?= h(t('footer.rights')) ?></span>
      <span><?= h(t('footer.made_with')) ?></span>
      <a href="#main" class="back-to-top"><?= icon('chevron-down', 'icon') ?> <?= h(t('footer.back_to_top')) ?></a>
    </div>
  </div>
</footer>

<script src="/assets/js/main.js" defer></script>
<?php if (!empty($extraScripts)) foreach ($extraScripts as $src): ?>
<script src="<?= h($src) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
