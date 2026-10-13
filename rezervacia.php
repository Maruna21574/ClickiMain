<?php
require_once __DIR__ . '/inc/functions.php';

$lang = current_lang();
$S = [
    'sk' => [
        'title' => 'Rezervácia konzultácie — 15-minútový hovor zadarmo | Clicki',
        'desc' => 'Vyberte si termín 15-minútového bezplatného hovoru. Zavoláme vám a prejdeme, čo potrebujete: web, e-shop, SEO alebo fotografiu.',
        'eyebrow' => 'Konzultácia zadarmo',
        'h1' => 'Rezervujte si 15-minútový hovor',
        'lead' => 'Vyberte si termín a my vám zavoláme. Prejdeme, čo potrebujete, a navrhneme ďalšie kroky. Nezáväzne a zadarmo.',
        'step1' => '1. Vyberte deň a čas',
        'earlier' => 'Skoršie týždne',
        'later' => 'Neskoršie týždne',
        'other' => 'Nevyhovuje vám žiadny termín? Iný čas vám radi dohodneme,',
        'other_write' => 'napíšte nám',
        'or' => 'alebo zavolajte na',
        'step2' => '2. Vaše údaje',
        'name' => 'Meno',
        'phone' => 'Telefónne číslo',
        'email' => 'E-mail (nepovinné)',
        'topic' => 'O čom si chcete pohovoriť (nepovinné)',
        'topic_ph' => 'Napríklad: nový web pre penzión, e-shop, SEO…',
        'submit' => 'Rezervovať hovor',
        'none' => 'Momentálne nemáme voľné termíny. Napíšte nám alebo zavolajte, radi sa dohodneme.',
        'err_slot' => 'Vyberte termín hovoru.',
        'err_taken' => 'Tento termín už medzitým niekto obsadil. Vyberte prosím iný.',
        'err_name' => 'Zadajte meno.',
        'err_phone' => 'Zadajte platné telefónne číslo.',
        'err_email' => 'E-mail nie je platný.',
        'err_limit' => 'Odoslali ste príliš veľa požiadaviek. Skúste to prosím o chvíľu alebo nám zavolajte.',
        'err_session' => 'Relácia vypršala, skúste to prosím znova.',
        'ok_h' => 'Hovor je rezervovaný',
        'ok_p' => 'Zavoláme vám v dohodnutom termíne na číslo',
        'ok_back' => 'Späť na úvod',
        'call_h' => 'Radšej hneď?',
        'call_p' => 'Zavolajte nám priamo alebo napíšte na WhatsApp.',
        'mail_subject' => 'Rezervácia hovoru',
        'mail_user' => "Ďakujeme za rezerváciu. Zavoláme vám %s o %s na číslo %s.\n\nAk potrebujete termín zmeniť, odpovedzte na tento e-mail alebo nám zavolajte na " . CONTACT_PHONE_DISPLAY . ".\n\nClicki",
    ],
    'en' => [
        'title' => 'Book a consultation — free 15-minute call | Clicki',
        'desc' => 'Pick a time for a free 15-minute call. We will phone you and go through what you need: website, e-shop, SEO or photography.',
        'eyebrow' => 'Free consultation',
        'h1' => 'Book a 15-minute call',
        'lead' => 'Pick a time and we will call you. We will go through what you need and suggest next steps. No obligation, free of charge.',
        'step1' => '1. Choose a day and time',
        'earlier' => 'Earlier weeks',
        'later' => 'Later weeks',
        'other' => 'None of the slots suit you? We will gladly arrange another time,',
        'other_write' => 'write to us',
        'or' => 'or call',
        'step2' => '2. Your details',
        'name' => 'Name',
        'phone' => 'Phone number',
        'email' => 'E-mail (optional)',
        'topic' => 'What would you like to talk about (optional)',
        'topic_ph' => 'For example: a new website for a guesthouse, e-shop, SEO…',
        'submit' => 'Book the call',
        'none' => 'We have no free slots at the moment. Write or call us and we will arrange something.',
        'err_slot' => 'Choose a time for the call.',
        'err_taken' => 'That slot has just been taken. Please choose another.',
        'err_name' => 'Enter your name.',
        'err_phone' => 'Enter a valid phone number.',
        'err_email' => 'The e-mail is not valid.',
        'err_limit' => 'You have sent too many requests. Please try again in a moment or call us.',
        'err_session' => 'Your session expired, please try again.',
        'ok_h' => 'Your call is booked',
        'ok_p' => 'We will call you at the agreed time on',
        'ok_back' => 'Back to home',
        'call_h' => 'Rather right now?',
        'call_p' => 'Call us directly or message us on WhatsApp.',
        'mail_subject' => 'Call booking',
        'mail_user' => "Thank you for booking. We will call you on %s at %s on %s.\n\nIf you need to change the time, reply to this e-mail or call us on " . CONTACT_PHONE_DISPLAY . ".\n\nClicki",
    ],
];
$L = $S[$lang] ?? $S['sk'];

$pageTitle = $L['title'];
$pageDesc = $L['desc'];
$activeNav = 'contact';
$canonical = site_url('/konzultacia');

$errors = [];
$old = ['name' => '', 'phone' => '', 'email' => '', 'topic' => '', 'slot' => ''];
$done = (isset($_GET['rezervovane']) && !empty($_SESSION['booking_done'])) ? $_SESSION['booking_done'] : null;

if (!$done && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $v) {
        $old[$k] = trim((string)($_POST[$k] ?? ''));
    }

    if (!csrf_verify()) {
        $errors['form'] = $L['err_session'];
    } else {
        $spam = spam_check('rezervacia', [$old['name'], $old['topic'], $old['email']]);
        if ($spam === 'silent') {
            redirect('/konzultacia?rezervovane=1');
        }
        if ($spam === 'limit') {
            $errors['form'] = $L['err_limit'];
        }
    }

    $date = $time = '';
    if (preg_match('/^(\d{4}-\d{2}-\d{2})\|(\d{2}:\d{2})$/', $old['slot'], $m)) {
        [$date, $time] = [$m[1], $m[2]];
    }
    if (!$errors) {
        if ($date === '') {
            $errors['slot'] = $L['err_slot'];
        }
        if (mb_strlen($old['name']) < 2) {
            $errors['name'] = $L['err_name'];
        }
        if (strlen(preg_replace('/\D/', '', $old['phone'])) < 9) {
            $errors['phone'] = $L['err_phone'];
        }
        if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = $L['err_email'];
        }
        if (empty($_POST['consent'])) {
            $errors['consent'] = true;
        }
    }
    if (!$errors && !booking_slot_is_free($date, $time)) {
        $errors['slot'] = $L['err_taken'];
    }

    if (!$errors) {
        try {
            db()->prepare('INSERT INTO bookings (slot_date, slot_time, name, phone, email, topic) VALUES (?, ?, ?, ?, ?, ?)')
                ->execute([$date, $time, $old['name'], $old['phone'], $old['email'], $old['topic']]);
        } catch (PDOException $e) {
            $errors['slot'] = $L['err_taken']; // medzitým obsadený (unikátny index)
        }
    }

    if (!$errors) {
        spam_done('rezervacia');
        $niceDate = date('j.n.Y', strtotime($date));
        $_SESSION['booking_done'] = ['date' => $niceDate, 'time' => $time, 'phone' => $old['phone']];

        $body = "Rezervácia hovoru\nTermín: $niceDate o $time\nMeno: {$old['name']}\nTelefón: {$old['phone']}\nE-mail: " . ($old['email'] ?: '—') . "\nTéma: " . ($old['topic'] ?: '—') . "\n";
        @mail(ADMIN_EMAIL, 'Rezervácia hovoru: ' . $niceDate . ' ' . $time . ' — ' . str_replace(["\r", "\n"], ' ', $old['name']), $body,
            'From: ' . MAIL_FROM . ($old['email'] ? "\r\nReply-To: " . $old['email'] : ''));
        if ($old['email'] !== '') {
            @mail($old['email'], $L['mail_subject'] . ': ' . $niceDate . ' ' . $time, sprintf($L['mail_user'], $niceDate, $time, $old['phone']),
                'From: ' . MAIL_FROM . "\r\nReply-To: " . ADMIN_EMAIL . "\r\nContent-Type: text/plain; charset=UTF-8");
        }
        // aj do Správ, nech je všetko na jednom mieste
        db()->prepare('INSERT INTO messages (name, email, phone, budget, website, message) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$old['name'], $old['email'], $old['phone'], 'Rezervácia hovoru', '', "REZERVÁCIA HOVORU\nTermín: $niceDate o $time\nTéma: " . ($old['topic'] ?: '—')]);

        redirect('/konzultacia?rezervovane=1');
    }
}

$days = $done ? [] : booking_available();

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero page-hero--center">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h($L['eyebrow']) ?></p>
    <h1><?= h($done ? $L['ok_h'] : $L['h1']) ?></h1>
    <p class="lead"><?= h($done ? $L['ok_p'] . ' ' . $done['phone'] . ' — ' . $done['date'] . ', ' . $done['time'] : $L['lead']) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container container--narrow">
    <?php if ($done): ?>
    <div class="book-done" data-reveal>
      <span class="book-done__icon"><?= icon('check') ?></span>
      <div class="lp-hero__actions" style="justify-content:center;margin-top:0;">
        <a href="/" class="btn btn--primary"><?= h($L['ok_back']) ?> <?= icon('arrow-right') ?></a>
        <a href="/portfolio.php" class="btn btn--ghost"><?= h(t('nav.portfolio')) ?></a>
      </div>
    </div>
    <?php else: ?>

    <?php if (!empty($errors['form'])): ?><div class="form-alert form-alert--error"><?= h($errors['form']) ?></div><?php endif; ?>

    <?php if (!$days): ?>
    <p class="book-none"><?= h($L['none']) ?></p>
    <?php else: ?>
    <form method="post" action="/konzultacia" class="book-form" data-book novalidate>
      <?= csrf_field() ?>
      <?= spam_fields('rezervacia') ?>

      <h2 class="book-step"><?= h($L['step1']) ?></h2>
      <div class="book-cal">
        <div class="book-cal__head" aria-hidden="true">
          <?php foreach (($lang === 'en' ? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] : ['Po', 'Ut', 'St', 'Št', 'Pi']) as $wdName): ?><span><?= h($wdName) ?></span><?php endforeach; ?>
        </div>
        <?php
        $weeks = [];
        foreach ($days as $i => $d) {
            $weeks[date('o-W', strtotime($d['date']))][] = $d + ['idx' => $i];
        }
        $weeks = array_values($weeks);
        ?>
        <div class="book-weeks" role="tablist">
          <?php foreach ($weeks as $wi => $week): ?>
          <div class="book-days book-week" data-week="<?= $wi ?>">
            <?php foreach ($week as $d): [$wd, $dm] = booking_day_label($d['date'], $lang); $col = (int)date('N', strtotime($d['date'])); ?>
            <button type="button" role="tab" class="book-day<?= $d['idx'] === 0 ? ' is-active' : '' ?>" style="grid-column: <?= $col ?>;" data-day="<?= h($d['date']) ?>" aria-label="<?= h($wd . ' ' . $dm) ?>">
              <strong><?= h($dm) ?></strong>
            </button>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if (count($weeks) > 2): ?>
        <div class="book-pager" data-book-pager hidden>
          <button type="button" class="btn btn--ghost btn--sm" data-page="-1">← <?= h($L['earlier']) ?></button>
          <button type="button" class="btn btn--ghost btn--sm" data-page="1"><?= h($L['later']) ?> →</button>
        </div>
        <?php endif; ?>
      </div>

      <p class="book-other"><?= h($L['other']) ?> <a href="/kontakt.php"><?= h($L['other_write']) ?></a> <?= h($L['or']) ?> <a href="tel:<?= h(CONTACT_PHONE) ?>"><?= h(CONTACT_PHONE_DISPLAY) ?></a>.</p>
      <?php foreach ($days as $i => $d): ?>
      <div class="book-times<?= $i === 0 ? ' is-active' : '' ?>" data-times="<?= h($d['date']) ?>">
        <?php foreach ($d['times'] as $tm): $val = $d['date'] . '|' . $tm; ?>
        <label class="book-time">
          <input type="radio" name="slot" value="<?= h($val) ?>" <?= $old['slot'] === $val ? 'checked' : '' ?>>
          <span><?= h($tm) ?></span>
        </label>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
      <?php if (!empty($errors['slot'])): ?><p class="field-error"><?= h($errors['slot']) ?></p><?php endif; ?>

      <h2 class="book-step"><?= h($L['step2']) ?></h2>
      <div class="field-row">
        <div class="field">
          <label for="b-name"><?= h($L['name']) ?> *</label>
          <input type="text" id="b-name" name="name" value="<?= h($old['name']) ?>" autocomplete="name" required>
          <?php if (!empty($errors['name'])): ?><p class="field-error"><?= h($errors['name']) ?></p><?php endif; ?>
        </div>
        <div class="field">
          <label for="b-phone"><?= h($L['phone']) ?> *</label>
          <input type="tel" id="b-phone" name="phone" value="<?= h($old['phone']) ?>" autocomplete="tel" placeholder="+421 …" required>
          <?php if (!empty($errors['phone'])): ?><p class="field-error"><?= h($errors['phone']) ?></p><?php endif; ?>
        </div>
      </div>
      <div class="field">
        <label for="b-email"><?= h($L['email']) ?></label>
        <input type="email" id="b-email" name="email" value="<?= h($old['email']) ?>" autocomplete="email">
        <?php if (!empty($errors['email'])): ?><p class="field-error"><?= h($errors['email']) ?></p><?php endif; ?>
      </div>
      <div class="field">
        <label for="b-topic"><?= h($L['topic']) ?></label>
        <textarea id="b-topic" name="topic" rows="3" placeholder="<?= h($L['topic_ph']) ?>"><?= h($old['topic']) ?></textarea>
      </div>

      <?php $consentChecked = !empty($_POST['consent']); $consentError = !empty($errors['consent']); require __DIR__ . '/templates/consent-field.php'; ?>
      <button type="submit" class="btn btn--primary btn--block"><?= h($L['submit']) ?> <?= icon('arrow-right') ?></button>
    </form>
    <?php endif; ?>

    <div class="book-call">
      <div>
        <strong><?= h($L['call_h']) ?></strong>
        <p><?= h($L['call_p']) ?></p>
      </div>
      <div class="book-call__links">
        <a class="btn btn--ghost btn--sm" href="tel:<?= h(CONTACT_PHONE) ?>"><?= icon('phone') ?> <?= h(CONTACT_PHONE_DISPLAY) ?></a>
        <a class="btn btn--ghost btn--sm" href="https://wa.me/<?= h(CONTACT_WHATSAPP) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> WhatsApp</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<script>
(function () {
  var root = document.querySelector('[data-book]');
  if (!root) return;
  var days = root.querySelectorAll('.book-day');
  var panels = root.querySelectorAll('.book-times');
  root.classList.add('is-js');
  // ak je už vybraný termín (po chybe), otvor jeho deň
  var checked = root.querySelector('input[name="slot"]:checked');
  function show(date) {
    days.forEach(function (d) { d.classList.toggle('is-active', d.getAttribute('data-day') === date); });
    panels.forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-times') === date); });
  }
  var weeks = root.querySelectorAll('.book-week');
  var pager = root.querySelector('[data-book-pager]');
  var page = 0, perPage = 2, pages = Math.ceil(weeks.length / perPage);
  function renderPage() {
    weeks.forEach(function (w, i) { w.style.display = (i >= page * perPage && i < (page + 1) * perPage) ? '' : 'none'; });
    if (pager) {
      pager.hidden = false;
      pager.querySelector('[data-page="-1"]').disabled = page === 0;
      pager.querySelector('[data-page="1"]').disabled = page >= pages - 1;
    }
  }
  if (pager) {
    pager.querySelectorAll('[data-page]').forEach(function (b) {
      b.addEventListener('click', function () {
        page = Math.max(0, Math.min(pages - 1, page + parseInt(b.getAttribute('data-page'), 10)));
        renderPage();
      });
    });
  }
  if (checked) {
    var d0 = checked.value.split('|')[0];
    weeks.forEach(function (w, i) { if (w.querySelector('[data-day="' + d0 + '"]')) page = Math.floor(i / perPage); });
    show(d0);
  }
  renderPage();
  days.forEach(function (d) {
    d.addEventListener('click', function () { show(d.getAttribute('data-day')); });
  });
})();
</script>

<?php require __DIR__ . '/templates/footer.php'; ?>
