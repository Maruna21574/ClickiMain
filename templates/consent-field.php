<?php
/** Povinný súhlas so spracovaním osobných údajov. Očakáva $consentChecked (bool) a $consentError (bool). */
$consentChecked = $consentChecked ?? false;
$consentError = $consentError ?? false;
?>
<div class="field consent-field<?= $consentError ? ' has-error' : '' ?>">
  <label class="consent-field__label">
    <input type="checkbox" name="consent" value="1" <?= $consentChecked ? 'checked' : '' ?> required>
    <span><?= h(t('consent.text')) ?> <a href="/gdpr.php" target="_blank" rel="noopener"><?= h(t('consent.link')) ?></a>. *</span>
  </label>
  <?php if ($consentError): ?><p class="field-error"><?= h(t('consent.error')) ?></p><?php endif; ?>
</div>
