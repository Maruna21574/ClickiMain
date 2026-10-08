<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = t('meta.contact_title');
$pageDesc = t('meta.contact_desc');
$activeNav = 'contact';

$errors = [];
$old = ['name' => '', 'email' => '', 'phone' => '', 'budget' => '', 'website' => '', 'message' => ''];
$success = isset($_GET['odoslane']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim((string)($_POST['name'] ?? ''));
    $old['email'] = trim((string)($_POST['email'] ?? ''));
    $old['phone'] = trim((string)($_POST['phone'] ?? ''));
    $old['budget'] = trim((string)($_POST['budget'] ?? ''));
    $old['website'] = trim((string)($_POST['website'] ?? ''));
    $old['message'] = trim((string)($_POST['message'] ?? ''));

    if (!csrf_verify()) {
        $errors[] = t('contact.form.error');
    }
    if ($old['name'] === '') {
        $errors['name'] = true;
    }
    if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = true;
    }
    if ($old['message'] === '' || mb_strlen($old['message']) < 10) {
        $errors['message'] = true;
    }
    $website = normalize_url($old['website']);
    if ($website === null) {
        $errors['website'] = true;
    }

    if (empty($errors)) {
        $stmt = db()->prepare('INSERT INTO messages (name, email, phone, budget, website, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$old['name'], $old['email'], $old['phone'], $old['budget'], $website, $old['message']]);

        $subject = 'Nový dopyt z webu Clicki od ' . str_replace(["\r", "\n"], ' ', $old['name']);
        $body = "Meno: {$old['name']}\nE-mail: {$old['email']}\nTelefón: {$old['phone']}\nRozpočet: {$old['budget']}\nAktuálny web: " . ($website ?: '—') . "\n\nSpráva:\n{$old['message']}\n";
        $headers = 'From: ' . MAIL_FROM . "\r\nReply-To: " . $old['email'];
        @mail(ADMIN_EMAIL, $subject, $body, $headers);

        redirect('/kontakt.php?odoslane=1');
    }
}

require __DIR__ . '/templates/header.php';
?>

<section class="page-hero">
  <span class="hero-glow page-hero__glow"></span>
  <div class="container">
    <p class="eyebrow"><?= h(t('contact.hero.eyebrow')) ?></p>
    <h1><?= h(t('contact.hero.title')) ?></h1>
    <p class="lead"><?= h(t('contact.hero.subtitle')) ?></p>
  </div>
</section>

<section class="section section--tight">
  <div class="container">
    <div class="contact-layout">
      <div data-reveal>
        <?php if ($success): ?>
          <div class="form-alert form-alert--success"><?= h(t('contact.form.success')) ?></div>
        <?php endif; ?>
        <?php if (!empty($errors) && is_array($errors) && isset($errors[0])): ?>
          <div class="form-alert form-alert--error"><?= h($errors[0]) ?></div>
        <?php elseif (!empty($errors)): ?>
          <div class="form-alert form-alert--error"><?= h(t('contact.form.validation')) ?></div>
        <?php endif; ?>

        <form method="post" action="/kontakt.php" novalidate>
          <?= csrf_field() ?>
          <div class="field-row">
            <div class="field">
              <label for="name"><?= h(t('contact.form.name')) ?> *</label>
              <input type="text" id="name" name="name" value="<?= h($old['name']) ?>" required>
              <?php if (!empty($errors['name'])): ?><p class="field-error"><?= h(t('contact.form.validation')) ?></p><?php endif; ?>
            </div>
            <div class="field">
              <label for="email"><?= h(t('contact.form.email')) ?> *</label>
              <input type="email" id="email" name="email" value="<?= h($old['email']) ?>" required>
              <?php if (!empty($errors['email'])): ?><p class="field-error"><?= h(t('contact.form.validation')) ?></p><?php endif; ?>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="phone"><?= h(t('contact.form.phone')) ?></label>
              <input type="tel" id="phone" name="phone" value="<?= h($old['phone']) ?>">
            </div>
            <div class="field">
              <label for="budget"><?= h(t('contact.form.budget')) ?></label>
              <select id="budget" name="budget">
                <option value=""><?= h(t('contact.form.budget_placeholder')) ?></option>
                <option value="<?= h(t('contact.form.budget_1')) ?>" <?= $old['budget'] === t('contact.form.budget_1') ? 'selected' : '' ?>><?= h(t('contact.form.budget_1')) ?></option>
                <option value="<?= h(t('contact.form.budget_2')) ?>" <?= $old['budget'] === t('contact.form.budget_2') ? 'selected' : '' ?>><?= h(t('contact.form.budget_2')) ?></option>
                <option value="<?= h(t('contact.form.budget_3')) ?>" <?= $old['budget'] === t('contact.form.budget_3') ? 'selected' : '' ?>><?= h(t('contact.form.budget_3')) ?></option>
                <option value="<?= h(t('contact.form.budget_4')) ?>" <?= $old['budget'] === t('contact.form.budget_4') ? 'selected' : '' ?>><?= h(t('contact.form.budget_4')) ?></option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="website"><?= h(t('contact.form.website')) ?></label>
            <input type="text" inputmode="url" id="website" name="website" value="<?= h($old['website']) ?>" placeholder="<?= h(t('contact.form.website_placeholder')) ?>" autocomplete="url">
            <?php if (!empty($errors['website'])): ?><p class="field-error"><?= h(t('contact.form.website_invalid')) ?></p><?php else: ?><p class="field-hint"><?= h(t('contact.form.website_hint')) ?></p><?php endif; ?>
          </div>
          <div class="field">
            <label for="message"><?= h(t('contact.form.message')) ?> *</label>
            <textarea id="message" name="message" placeholder="<?= h(t('contact.form.message_placeholder')) ?>" required><?= h($old['message']) ?></textarea>
            <?php if (!empty($errors['message'])): ?><p class="field-error"><?= h(t('contact.form.validation')) ?></p><?php endif; ?>
          </div>
          <button type="submit" class="btn btn--primary btn--block"><?= icon('arrow-right') ?> <?= h(t('contact.form.submit')) ?></button>
        </form>
      </div>

      <div class="card contact-info-card" data-reveal>
        <h3 style="font-size:var(--fs-lg);margin-bottom:.5rem;"><?= h(t('contact.info.title')) ?></h3>
        <div class="contact-info-row">
          <?= icon('mail') ?>
          <div><h4><?= h(t('contact.info.email_label')) ?></h4><a href="mailto:<?= h(ADMIN_EMAIL) ?>"><?= h(ADMIN_EMAIL) ?></a></div>
        </div>
        <div class="contact-info-row">
          <?= icon('phone') ?>
          <div><h4><?= h(t('contact.info.phone_label')) ?></h4><a href="tel:+421900000000">+421 900 000 000</a></div>
        </div>
        <div class="contact-info-row">
          <?= icon('map-pin') ?>
          <div><h4><?= h(t('contact.info.location_label')) ?></h4><p><?= h(t('contact.info.location_value')) ?></p></div>
        </div>
        <div class="contact-info-row">
          <?= icon('clock') ?>
          <div><h4><?= h(t('contact.info.response_label')) ?></h4><p><?= h(t('contact.info.response_value')) ?></p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container--narrow">
    <div class="section-head section-head--center" data-reveal>
      <p class="eyebrow" style="justify-content:center"><?= h(t('contact.faq.eyebrow')) ?></p>
      <h2><?= h(t('contact.faq.title')) ?></h2>
    </div>
    <div class="faq-list" data-reveal>
      <?php foreach (td('faq') as $item): ?>
      <div class="faq-item">
        <button type="button" class="faq-item__q" aria-expanded="false"><?= h($item['q']) ?> <?= icon('chevron-down') ?></button>
        <div class="faq-item__a"><p><?= h($item['a']) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/templates/footer.php'; ?>
