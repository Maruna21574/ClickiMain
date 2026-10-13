<?php
/** Spoločná šablóna právnych stránok. Očakáva $page (pole z legal_content()) a $legalKey ('privacy' | 'cookies'). */
?>
<section class="page-hero page-hero--center">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h($page['eyebrow']) ?></p>
    <h1><?= h($page['title']) ?></h1>
    <p class="lead"><?= h($page['intro']) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container container--narrow">
    <div class="legal" data-reveal>
      <p class="legal__updated"><?= h($page['updated_label']) ?>: <?= h($page['updated']) ?></p>

      <?php foreach ($page['sections'] as $s): ?>
      <h2><?= h($s['h']) ?></h2>
      <?php foreach ($s['p'] ?? [] as $para): ?><p><?= legal_inline($para) ?></p><?php endforeach; ?>
      <?php if (!empty($s['ul'])): ?>
      <ul>
        <?php foreach ($s['ul'] as $li): ?><li><?= legal_inline($li) ?></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <?php foreach ($s['p2'] ?? [] as $para): ?><p><?= legal_inline($para) ?></p><?php endforeach; ?>
      <?php endforeach; ?>

      <?php if ($legalKey === 'cookies'): ?>
      <h2><?= h($page['table_h']) ?></h2>
      <div class="legal__table-wrap">
        <table class="legal__table">
          <thead><tr><?php foreach ($page['table_cols'] as $c): ?><th><?= h($c) ?></th><?php endforeach; ?></tr></thead>
          <tbody>
            <?php foreach ($page['table'] as $row): ?>
            <tr>
              <td><code><?= h($row[0]) ?></code></td>
              <td><?= h($row[1]) ?></td>
              <td><?= h($row[2]) ?></td>
              <td><?= h($row[3]) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <h2><?= h($page['settings_h']) ?></h2>
      <p><?= h($page['settings_p']) ?></p>
      <p><button type="button" class="btn btn--ghost btn--sm" data-cookie-settings><?= h($page['settings_btn']) ?></button></p>
      <p><a href="/gdpr.php"><?= h($page['privacy_link']) ?> →</a></p>
      <?php else: ?>
      <h2><?= h($page['contact_h']) ?></h2>
      <p><?= h($page['contact_p']) ?> <a href="mailto:<?= h(ADMIN_EMAIL) ?>"><?= h(ADMIN_EMAIL) ?></a></p>
      <?php if (LEGAL_NAME !== '' || LEGAL_ICO !== '' || LEGAL_ADDRESS !== ''): ?>
      <h3><?= h($page['operator_h']) ?></h3>
      <ul>
        <?php if (LEGAL_NAME !== ''): ?><li><?= h(LEGAL_NAME) ?></li><?php endif; ?>
        <?php if (LEGAL_ADDRESS !== ''): ?><li><?= h(LEGAL_ADDRESS) ?></li><?php endif; ?>
        <?php if (LEGAL_ICO !== ''): ?><li>IČO: <?= h(LEGAL_ICO) ?></li><?php endif; ?>
      </ul>
      <?php endif; ?>
      <p><a href="/cookies.php"><?= h(t('footer.cookies')) ?> →</a></p>
      <?php endif; ?>
    </div>
  </div>
</section>
