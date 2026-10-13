<?php
/**
 * Očakáva (voliteľne) premenné nastavené pred include:
 * $pageTitle, $pageDesc, $activeNav ('home'|'services'|'portfolio'|'about'|'contact'), $bodyClass
 */
$lang = current_lang();
$pageTitle = $pageTitle ?? t('meta.site_title_suffix');
$pageDesc = $pageDesc ?? t('meta.home_desc');
$activeNav = $activeNav ?? '';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="<?= h($lang) ?>" class="no-js">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?></title>
<meta name="description" content="<?= h($pageDesc) ?>">
<meta property="og:title" content="<?= h($pageTitle) ?>">
<meta property="og:description" content="<?= h($pageDesc) ?>">
<?php
$canonical = $canonical ?? canonical_url();
$ogImg = $ogImage ?? '/assets/img/og-clicki.jpg';
if (!preg_match('#^https?://#', $ogImg)) { $ogImg = site_url($ogImg); }
?>
<link rel="canonical" href="<?= h($canonical) ?>">
<?php if (!empty($noindex)): ?><meta name="robots" content="noindex, follow">
<?php else: ?><meta name="robots" content="index, follow, max-image-preview:large"><?php endif; ?>

<meta property="og:type" content="<?= h($ogType ?? 'website') ?>">
<meta property="og:url" content="<?= h($canonical) ?>">
<meta property="og:site_name" content="Clicki">
<meta property="og:locale" content="sk_SK">
<meta property="og:image" content="<?= h($ogImg) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= h($pageTitle) ?>">
<meta name="twitter:description" content="<?= h($pageDesc) ?>">
<meta name="twitter:image" content="<?= h($ogImg) ?>">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            '@id' => site_url('/#organization'),
            'name' => 'Clicki',
            'url' => site_url('/'),
            'logo' => site_url('/assets/img/clicki_logo_white.png'),
            'email' => ADMIN_EMAIL,
            'telephone' => CONTACT_PHONE,
            'contactPoint' => [['@type' => 'ContactPoint', 'telephone' => CONTACT_PHONE, 'contactType' => 'customer service', 'availableLanguage' => ['sk', 'en']]],
            'areaServed' => 'SK',
            'description' => t('meta.home_desc'),
        ],
        [
            '@type' => 'WebSite',
            '@id' => site_url('/#website'),
            'url' => site_url('/'),
            'name' => 'Clicki',
            'inLanguage' => 'sk',
            'publisher' => ['@id' => site_url('/#organization')],
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
<?php if (!empty($headExtra)) echo $headExtra; ?>
<meta name="theme-color" content="#0B0B0D">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32.png">
<link rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicon-192.png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">

<?php if (empty($heroHasVideo) && is_file(SITE_ROOT . '/assets/video/services.mp4')): ?>
<link rel="prefetch" href="/assets/video/services.mp4" as="video" type="video/mp4">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap"></noscript>

<link rel="stylesheet" href="<?= asset('/assets/css/tokens.css') ?>">
<link rel="stylesheet" href="<?= asset('/assets/css/base.css') ?>">
<link rel="stylesheet" href="<?= asset('/assets/css/components.css') ?>">
<link rel="stylesheet" href="<?= asset('/assets/css/animations.css') ?>">
<link rel="stylesheet" href="<?= asset('/assets/css/pages.css') ?>">
<link rel="stylesheet" href="<?= asset('/assets/css/fx.css') ?>">
</head>
<body class="<?= h($bodyClass) ?>">
<div class="scroll-progress" aria-hidden="true"><span></span></div>
<a href="#main" class="skip-link">Preskočiť na obsah</a>

<header class="site-header">
  <div class="container site-header__inner">
    <a href="/" class="brand" aria-label="Clicki — domov">
      <img src="/assets/img/clicki_logo_white.png" alt="Clicki">
    </a>

    <nav class="main-nav" id="main-nav">
      <ul class="main-nav__list">
        <li><a class="main-nav__link<?= $activeNav === 'home' ? ' is-active' : '' ?>" href="/"><?= h(t('nav.home')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'services' ? ' is-active' : '' ?>" href="/sluzby"><?= h(t('nav.services')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'portfolio' ? ' is-active' : '' ?>" href="/portfolio"><?= h(t('nav.portfolio')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'about' ? ' is-active' : '' ?>" href="/o-nas"><?= h(t('nav.about')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'contact' ? ' is-active' : '' ?>" href="/kontakt"><?= h(t('nav.contact')) ?></a></li>
        <li class="lang-switch" aria-label="Jazyk">
          <a href="<?= h(lang_url('sk')) ?>" class="<?= $lang === 'sk' ? 'is-active' : '' ?>">SK</a>
          <span>/</span>
          <a href="<?= h(lang_url('en')) ?>" class="<?= $lang === 'en' ? 'is-active' : '' ?>">EN</a>
        </li>
      </ul>
    </nav>

    <div class="header-actions">
      <button type="button" class="cmdk-trigger" data-cmdk-open aria-label="<?= h(t('cmdk.open')) ?> (Ctrl + K)" aria-haspopup="dialog">
        <?= icon('search') ?><span class="cmdk-trigger__label"><?= h(t('cmdk.open')) ?></span><kbd data-cmdk-kbd>Ctrl K</kbd>
      </button>
      <a href="/ponuka" class="btn btn--primary btn--sm nav-cta-desktop"><?= h(t('nav.cta')) ?></a>
      <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="main-nav" aria-label="Menu">
        <?= icon('menu') ?>
      </button>
    </div>
  </div>
</header>

<main id="main">
