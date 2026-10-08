<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.quote_title');
$pageDesc = t('meta.quote_desc');
$activeNav = '';
$bodyClass = 'page-quote';

$cfg = pricing();
$errors = [];
$success = isset($_GET['odoslane']);

// predvolený stav konfigurátora (alebo to, čo používateľ poslal)
$sel = [
    'type' => 'web',
    'pages' => $cfg['types']['web']['pages']['included'] ?? 5,
    'products' => 'small',
    'features' => [],
    'languages' => 0,
    'care' => 'none',
    'express' => 0,
];
$old = ['name' => '', 'email' => '', 'phone' => '', 'website' => '', 'note' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($sel as $k => $v) {
        if (isset($_POST[$k])) {
            $sel[$k] = is_array($v) ? array_map('strval', (array)$_POST[$k]) : (string)$_POST[$k];
        }
    }
    $sel['express'] = !empty($_POST['express']) ? 1 : 0;
    foreach ($old as $k => $v) {
        $old[$k] = trim((string)($_POST[$k] ?? ''));
    }

    // honeypot — roboty vyplnia aj skryté pole; tvárime sa, že je všetko OK
    if (!empty($_POST['nickname'])) {
        redirect('/ponuka.php?odoslane=1');
    }
    if (!csrf_verify()) {
        $errors['form'] = t('contact.form.error');
    }
    if ($old['name'] === '') {
        $errors['name'] = true;
    }
    if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = true;
    }
    $website = normalize_url($old['website']);
    if ($website === null) {
        $errors['website'] = true;
    }

    if (!$errors) {
        // klientov výber + interný odhad z cenníka (klient ho na webe nevidí)
        $est = quote_estimate($sel);
        $range = format_price($est['min']) . ' – ' . format_price($est['max']);
        $lines = ['DOPYT Z KONFIGURÁTORA (Získať ponuku)', '', 'Výber klienta:'];
        foreach (quote_selection($sel) as $label) {
            $lines[] = '• ' . $label;
        }
        if ($old['note'] !== '') {
            $lines[] = '';
            $lines[] = 'Poznámka klienta:';
            $lines[] = $old['note'];
        }
        $lines[] = '';
        $lines[] = '— Interný odhad podľa cenníka (klient ho nevidel) —';
        foreach ($est['items'] as $it) {
            $lines[] = '  ' . $it['label'] . ': ' . ($it['from'] ? 'od ' : '') . format_price($it['price']);
        }
        $lines[] = '  Spolu jednorazovo: ' . $range . ($est['monthly'] ? ' + ' . format_price($est['monthly']) . ' / mes.' : '');
        $message = implode("\n", $lines);
        $budget = 'Odhad: ' . $range . ($est['monthly'] ? ' + ' . format_price($est['monthly']) . '/mes.' : '');

        db()->prepare('INSERT INTO messages (name, email, phone, budget, website, message) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$old['name'], $old['email'], $old['phone'], $budget, $website, $message]);

        $name = str_replace(["\r", "\n"], ' ', $old['name']);
        $body = "Meno: {$old['name']}\nE-mail: {$old['email']}\nTelefón: {$old['phone']}\nAktuálny web: " . ($website ?: '—') . "\n\n$message\n";
        @mail(ADMIN_EMAIL, 'Nový dopyt z konfigurátora Clicki od ' . $name, $body, 'From: ' . MAIL_FROM . "\r\nReply-To: " . $old['email']);

        redirect('/ponuka.php?odoslane=1#quote-contact');
    }
}

$selection = quote_selection($sel);

// konfigurácia pre JS — iba texty, žiadne ceny
$jsCfg = [
    'types' => array_map(fn($t) => ['label' => pl($t['label']), 'pages' => isset($t['pages']) ? ['max' => $t['pages']['max'], 'included' => $t['pages']['included']] : null, 'products' => !empty($t['products'])], $cfg['types']),
    'products' => array_map(fn($p) => pl($p['label']), $cfg['products']),
    'groups' => array_map(fn($g) => ['types' => $g['types'], 'items' => array_map(fn($i) => pl($i['label']), $g['items'])], $cfg['feature_groups']),
    'languages' => array_map('pl', $cfg['languages']['options']),
    'care' => array_map(fn($c) => pl($c['label']), $cfg['care']),
    'careNone' => array_key_first($cfg['care']),
    'express' => pl($cfg['express']['label']),
    'i18n' => ['pages' => t('quote.pages_item'), 'languages' => t('quote.languages')],
];

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('quote.hero.eyebrow')) ?></p>
    <h1><?= h(t('quote.hero.title')) ?></h1>
    <p class="lead"><?= h(t('quote.hero.subtitle')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <form method="post" action="/ponuka.php#quote-contact" class="quote" id="quote-form" novalidate>
      <?= csrf_field() ?>
      <div class="quote__hp" aria-hidden="true"><label>Nickname <input type="text" name="nickname" tabindex="-1" autocomplete="off"></label></div>

      <div class="quote__layout">
        <div class="quote__steps">

          <fieldset class="quote-step" data-reveal>
            <legend class="quote-step__title"><?= h(t('quote.step.type')) ?></legend>
            <div class="opt-grid opt-grid--types">
              <?php foreach ($cfg['types'] as $key => $type): ?>
              <label class="opt">
                <input type="radio" name="type" value="<?= h($key) ?>" <?= $sel['type'] === $key ? 'checked' : '' ?>>
                <span class="opt__body opt__body--type">
                  <span class="opt__icon"><?= icon($type['icon']) ?></span>
                  <strong><?= h(pl($type['label'])) ?></strong>
                  <small><?= h(pl($type['desc'])) ?></small>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <fieldset class="quote-step" data-reveal>
            <legend class="quote-step__title"><?= h(t('quote.step.scope')) ?></legend>

            <?php foreach ($cfg['types'] as $key => $type): if (empty($type['pages'])) continue; $pg = $type['pages']; ?>
            <div class="quote-field" data-show-for="<?= h($key) ?>">
              <div class="quote-field__head">
                <label for="q-pages"><?= h(t('quote.pages')) ?></label>
                <output class="quote-field__value" for="q-pages" data-pages-out><?= (int)$sel['pages'] ?></output>
              </div>
              <input type="range" id="q-pages" name="pages" min="1" max="<?= (int)$pg['max'] ?>" value="<?= (int)$sel['pages'] ?>" class="range">
              <p class="quote-field__hint"><?= h(t('quote.pages_hint')) ?></p>
            </div>
            <?php endforeach; ?>

            <div class="quote-field" data-show-for="<?= h(implode(' ', array_keys(array_filter($cfg['types'], fn($t) => !empty($t['products']))))) ?>">
              <p class="quote-field__label"><?= h(t('quote.products')) ?></p>
              <div class="pill-group">
                <?php foreach ($cfg['products'] as $key => $p): ?>
                <label class="opt opt--pill">
                  <input type="radio" name="products" value="<?= h($key) ?>" <?= $sel['products'] === $key ? 'checked' : '' ?>>
                  <span class="opt__body"><?= h(pl($p['label'])) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="quote-field">
              <p class="quote-field__label"><?= h(t('quote.languages')) ?></p>
              <div class="pill-group">
                <?php foreach ($cfg['languages']['options'] as $n => $labels): ?>
                <label class="opt opt--pill">
                  <input type="radio" name="languages" value="<?= (int)$n ?>" <?= (int)$sel['languages'] === $n ? 'checked' : '' ?>>
                  <span class="opt__body"><?= h(pl($labels)) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
          </fieldset>

          <?php foreach ($cfg['feature_groups'] as $group): ?>
          <fieldset class="quote-step" data-types="<?= h(implode(' ', $group['types'])) ?>" data-reveal>
            <legend class="quote-step__title"><?= h(pl($group['label'])) ?></legend>
            <div class="opt-grid">
              <?php foreach ($group['items'] as $key => $item): ?>
              <label class="opt">
                <input type="checkbox" name="features[]" value="<?= h($key) ?>" <?= in_array($key, $sel['features'], true) ? 'checked' : '' ?>>
                <span class="opt__body opt__body--check">
                  <span class="opt__tick"><?= icon('check') ?></span>
                  <span class="opt__label"><?= h(pl($item['label'])) ?></span>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </fieldset>
          <?php endforeach; ?>

          <fieldset class="quote-step" data-reveal>
            <legend class="quote-step__title"><?= h(t('quote.step.care')) ?></legend>
            <div class="opt-grid opt-grid--care">
              <?php foreach ($cfg['care'] as $key => $c): ?>
              <label class="opt">
                <input type="radio" name="care" value="<?= h($key) ?>" <?= $sel['care'] === $key ? 'checked' : '' ?>>
                <span class="opt__body opt__body--type">
                  <strong><?= h(pl($c['label'])) ?></strong>
                  <small><?= h(pl($c['desc'])) ?></small>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
            <label class="opt opt--inline">
              <input type="checkbox" name="express" value="1" <?= $sel['express'] ? 'checked' : '' ?>>
              <span class="opt__body opt__body--check">
                <span class="opt__tick"><?= icon('check') ?></span>
                <span class="opt__label"><?= h(pl($cfg['express']['label'])) ?></span>
              </span>
            </label>
          </fieldset>

          <div class="quote-contact" id="quote-contact">
            <h2><?= h(t('quote.form.title')) ?></h2>
            <p class="quote-contact__sub"><?= h(t('quote.form.subtitle')) ?></p>

            <?php if ($success): ?>
            <div class="form-alert form-alert--success"><?= h(t('quote.form.success')) ?></div>
            <?php elseif (!empty($errors['form'])): ?>
            <div class="form-alert form-alert--error"><?= h($errors['form']) ?></div>
            <?php elseif ($errors): ?>
            <div class="form-alert form-alert--error"><?= h(t('contact.form.validation')) ?></div>
            <?php endif; ?>

            <div class="field-row">
              <div class="field">
                <label for="q-name"><?= h(t('contact.form.name')) ?> *</label>
                <input type="text" id="q-name" name="name" value="<?= h($old['name']) ?>" required autocomplete="name">
                <?php if (!empty($errors['name'])): ?><p class="field-error"><?= h(t('contact.form.validation')) ?></p><?php endif; ?>
              </div>
              <div class="field">
                <label for="q-email"><?= h(t('contact.form.email')) ?> *</label>
                <input type="email" id="q-email" name="email" value="<?= h($old['email']) ?>" required autocomplete="email">
                <?php if (!empty($errors['email'])): ?><p class="field-error"><?= h(t('contact.form.validation')) ?></p><?php endif; ?>
              </div>
            </div>
            <div class="field-row">
              <div class="field">
                <label for="q-phone"><?= h(t('contact.form.phone')) ?></label>
                <input type="tel" id="q-phone" name="phone" value="<?= h($old['phone']) ?>" autocomplete="tel">
              </div>
              <div class="field">
                <label for="q-website"><?= h(t('contact.form.website')) ?></label>
                <input type="text" inputmode="url" id="q-website" name="website" value="<?= h($old['website']) ?>" placeholder="<?= h(t('contact.form.website_placeholder')) ?>" autocomplete="url">
                <?php if (!empty($errors['website'])): ?><p class="field-error"><?= h(t('contact.form.website_invalid')) ?></p><?php else: ?><p class="field-hint"><?= h(t('contact.form.website_hint')) ?></p><?php endif; ?>
              </div>
            </div>
            <div class="field">
              <label for="q-note"><?= h(t('quote.form.note')) ?></label>
              <textarea id="q-note" name="note" placeholder="<?= h(t('quote.form.note_placeholder')) ?>"><?= h($old['note']) ?></textarea>
            </div>
            <button type="submit" class="btn btn--primary btn--block"><?= icon('arrow-right') ?> <?= h(t('quote.form.submit')) ?></button>
          </div>
        </div>

        <aside class="quote__summary">
          <div class="quote-summary">
            <p class="quote-summary__eyebrow"><?= h(t('quote.summary.title')) ?></p>
            <ul class="quote-summary__items" data-items>
              <?php foreach ($selection as $label): ?>
              <li><?= icon('check') ?><span><?= h($label) ?></span></li>
              <?php endforeach; ?>
            </ul>
            <a href="#quote-contact" class="btn btn--primary btn--block"><?= icon('arrow-right') ?> <?= h(t('quote.summary.cta')) ?></a>
            <p class="quote-summary__note"><?= h(pl($cfg['note'])) ?></p>
          </div>
        </aside>
      </div>

      <div class="quote-bar" aria-hidden="true">
        <div>
          <span class="quote-bar__label"><?= h(t('quote.bar.label')) ?></span>
          <strong class="quote-bar__value"><span data-bar-type><?= h($selection[0]) ?></span> <span class="quote-bar__count" data-bar-count<?= count($selection) > 1 ? '' : ' hidden' ?>>+<?= count($selection) - 1 ?></span></strong>
        </div>
        <a href="#quote-contact" class="btn btn--primary btn--sm" tabindex="-1"><?= h(t('quote.summary.cta')) ?></a>
      </div>
    </form>
  </div>
</section>

<script type="application/json" id="quote-config"><?= json_encode($jsCfg, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

<?php $extraScripts = ['/assets/js/ponuka.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
