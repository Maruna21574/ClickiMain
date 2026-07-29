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
<meta property="og:type" content="website">
<meta property="og:image" content="/assets/img/favicon_clicki.png">
<meta name="theme-color" content="#0B0B0D">
<link rel="icon" href="/assets/img/clicki_favicon_small.png" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/favicon_clicki.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/animations.css">
<link rel="stylesheet" href="/assets/css/pages.css">
</head>
<body class="<?= h($bodyClass) ?>">
<a href="#main" class="skip-link">Preskočiť na obsah</a>

<header class="site-header">
  <div class="container site-header__inner">
    <a href="/" class="brand" aria-label="Clicki — domov">
      <img src="/assets/img/clicki_logo_white.png" alt="Clicki">
    </a>

    <nav class="main-nav" id="main-nav">
      <ul class="main-nav__list">
        <li><a class="main-nav__link<?= $activeNav === 'home' ? ' is-active' : '' ?>" href="/index.php"><?= h(t('nav.home')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'services' ? ' is-active' : '' ?>" href="/sluzby.php"><?= h(t('nav.services')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'portfolio' ? ' is-active' : '' ?>" href="/portfolio.php"><?= h(t('nav.portfolio')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'about' ? ' is-active' : '' ?>" href="/o-nas.php"><?= h(t('nav.about')) ?></a></li>
        <li><a class="main-nav__link<?= $activeNav === 'contact' ? ' is-active' : '' ?>" href="/kontakt.php"><?= h(t('nav.contact')) ?></a></li>
        <li class="lang-switch" aria-label="Jazyk">
          <a href="<?= h(lang_url('sk')) ?>" class="<?= $lang === 'sk' ? 'is-active' : '' ?>">SK</a>
          <span>/</span>
          <a href="<?= h(lang_url('en')) ?>" class="<?= $lang === 'en' ? 'is-active' : '' ?>">EN</a>
        </li>
      </ul>
    </nav>

    <div class="header-actions">
      <a href="/kontakt.php" class="btn btn--primary btn--sm nav-cta-desktop"><?= h(t('nav.cta')) ?></a>
      <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="main-nav" aria-label="Menu">
        <?= icon('menu') ?>
      </button>
    </div>
  </div>
</header>

<main id="main">
