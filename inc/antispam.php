<?php
/**
 * Ochrana formulárov proti spamu bez externých služieb (žiadne CAPTCHA, žiadne sledovanie):
 *  1. honeypot — skryté pole, ktoré vyplnia len roboty,
 *  2. časová pasca — človek formulár nevyplní za pár sekúnd; formulár musí byť aj reálne zobrazený (relácia),
 *  3. limit odoslaní na IP adresu (IP sa ukladá len ako hash),
 *  4. obsahový filter — veľa odkazov, typické spamové frázy a BBCode,
 *  5. limit neúspešných prihlásení do administrácie.
 *
 * Použitie vo formulári:   <?= spam_fields('kontakt') ?>
 * Pri spracovaní:          $spam = spam_check('kontakt', [$name, $message]);
 *   - 'silent'  → roboty: tvárime sa, že je všetko v poriadku, nič neukladáme ani neposielame
 *   - 'limit'   → človek, ktorý odosiela príliš často: zobraz chybu
 *   - null      → v poriadku
 */

const SPAM_MIN_SECONDS = 3;
const SPAM_MAX_AGE = 7200;

function spam_ip_hash(): string
{
    return hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? 'cli') . '|' . SITE_ROOT);
}

/** Skryté polia vložené do formulára (honeypot + zaznamenanie času zobrazenia). */
function spam_fields(string $form): string
{
    if (empty($_SESSION['sp_time'][$form]) || time() - $_SESSION['sp_time'][$form] > SPAM_MAX_AGE) {
        $_SESSION['sp_time'][$form] = time();
    }
    return '<div class="hp-field" aria-hidden="true"><label>Nickname <input type="text" name="nickname" tabindex="-1" autocomplete="off"></label></div>';
}

/** Zaznamená pokus (pre limit). */
function spam_hit(string $bucket): void
{
    $pdo = db();
    $pdo->prepare('INSERT INTO rate_limits (bucket, ip_hash, created_at) VALUES (?, ?, ?)')
        ->execute([$bucket, spam_ip_hash(), time()]);
    // údržba: staré záznamy preč
    if (random_int(1, 20) === 1) {
        $pdo->prepare('DELETE FROM rate_limits WHERE created_at < ?')->execute([time() - 86400]);
    }
}

function spam_count(string $bucket, int $withinSeconds): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM rate_limits WHERE bucket = ? AND ip_hash = ? AND created_at > ?');
    $stmt->execute([$bucket, spam_ip_hash(), time() - $withinSeconds]);
    return (int)$stmt->fetchColumn();
}

/** Podozrivý obsah: veľa odkazov, BBCode, typické spamové frázy. */
function spam_looks_like_spam(array $texts): bool
{
    $joined = implode("\n", array_map('strval', $texts));
    $links = preg_match_all('~https?://|www\.~i', $joined);
    if ($links > 2) {
        return true;
    }
    if (preg_match('~\[/?(url|link|img)\b|<a\s|<script|<iframe~i', $joined)) {
        return true;
    }
    $needles = ['viagra', 'cialis', 'casino', 'poker', 'bitcoin', 'crypto invest', 'forex', 'loan offer', 'seo services', 'backlinks', 'earn money', 'make money fast', 'adult dating', 'porn', 'escort'];
    $lower = mb_strtolower($joined);
    foreach ($needles as $n) {
        if (str_contains($lower, $n)) {
            return true;
        }
    }
    // text bez písmen (samé znaky/čísla) alebo cyrilika/čínština v inak slovenskom formulári
    if (preg_match('~[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}]{6,}~u', $joined)) {
        return true;
    }
    return false;
}

/**
 * Vyhodnotí odoslanie formulára. Vracia null (OK), 'silent' (zahodiť potichu) alebo 'limit' (zobraziť chybu).
 * Volaj až po csrf_verify(); pri úspešnom spracovaní zavolaj spam_done().
 */
function spam_check(string $form, array $texts = []): ?string
{
    // 1. honeypot
    if (!empty($_POST['nickname'])) {
        return 'silent';
    }
    // 2. časová pasca (aj formulár, ktorý nebol nikdy zobrazený, t. j. bez relácie)
    $shown = $_SESSION['sp_time'][$form] ?? 0;
    if (!$shown || time() - $shown < SPAM_MIN_SECONDS) {
        return 'silent';
    }
    // 3. limit odoslaní: max 5 za 10 minút a 15 za deň z jednej IP adresy
    if (spam_count('form:' . $form, 600) >= 5 || spam_count('form:' . $form, 86400) >= 15) {
        return 'limit';
    }
    // 4. obsah
    if (spam_looks_like_spam($texts)) {
        spam_hit('form:' . $form); // pokusy o spam sa počítajú do limitu
        return 'silent';
    }
    return null;
}

/** Po úspešnom odoslaní: započítaj do limitu a vynuluj čas formulára. */
function spam_done(string $form): void
{
    spam_hit('form:' . $form);
    unset($_SESSION['sp_time'][$form]);
}

/* ---- prihlásenie do administrácie ---- */

function login_blocked(): bool
{
    return spam_count('login', 900) >= 8;
}

function login_failed(): void
{
    spam_hit('login');
}
