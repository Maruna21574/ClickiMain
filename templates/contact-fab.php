<?php /** Plávajúce tlačidlo rýchleho kontaktu: zavolať, WhatsApp, rezervovať hovor. */ ?>
<div class="contact-fab" data-contact-fab>
  <div class="contact-fab__menu" id="contact-fab-menu" hidden>
    <a href="tel:<?= h(CONTACT_PHONE) ?>" class="contact-fab__item"><?= icon('phone') ?> <span><?= h(t('fab.call')) ?></span></a>
    <a href="https://wa.me/<?= h(CONTACT_WHATSAPP) ?>" target="_blank" rel="noopener" class="contact-fab__item"><?= icon('whatsapp') ?> <span>WhatsApp</span></a>
    <a href="/konzultacia" class="contact-fab__item"><?= icon('calendar') ?> <span><?= h(t('fab.book')) ?></span></a>
  </div>
  <button type="button" class="contact-fab__btn" aria-expanded="false" aria-controls="contact-fab-menu" aria-label="<?= h(t('fab.label')) ?>">
    <?= icon('phone') ?><span class="contact-fab__text"><?= h(t('fab.label')) ?></span>
  </button>
</div>
