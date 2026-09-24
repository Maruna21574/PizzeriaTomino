<?php
/**
 * Spoločná hlavička (head + navigácia) pre všetky stránky.
 * Očakáva premenné $pageTitle, $pageDescription (voliteľné $bodyClass,
 * $noIndex) - po slovensky, prekladajú sa cez t().
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';

$moreNavPages = ['o-nas.php', 'doprava-a-platba.php'];
$moreNavActive = in_array(basename($_SERVER['SCRIPT_NAME'] ?? ''), $moreNavPages, true);

$pageTitle = t($pageTitle ?? SITE_NAME);
$pageDescription = t($pageDescription ?? 'Neapolská pizza pečená v peci na drevo z talianskej múky a cesta fermentovaného 48 hodín. Rozvoz Novosad, Trebišov a okolie - objednávky telefonicky, platba hotovosťou alebo kartou.');
$bodyClass = $bodyClass ?? '';
$currentScript = $_SERVER['SCRIPT_NAME'] ?? 'index.php';

if (SITE_NOINDEX && !headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow'); // testovacia verzia webu
}
?><!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> | <?= e(SITE_NAME) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<meta name="theme-color" content="#c1272d">
<?php if (empty($noIndex) && !SITE_NOINDEX): ?>
<link rel="canonical" href="<?= e(canonicalUrl()) ?>">
<?php foreach (LANGUAGES as $code => $label): ?>
<link rel="alternate" hreflang="<?= e($code) ?>" href="<?= e(canonicalUrl($code)) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e(canonicalUrl('sk')) ?>">
<?php else: ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>

<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($pageTitle) ?> | <?= e(SITE_NAME) ?>">
<meta property="og:description" content="<?= e($pageDescription) ?>">
<meta property="og:url" content="<?= e(canonicalUrl()) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/img/og-image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= h('Neapolská pizza Margherita pred pecou na drevo') ?>">
<meta name="twitter:card" content="summary_large_image">
<meta property="og:locale" content="<?= lang() === 'hu' ? 'hu_HU' : 'sk_SK' ?>">

<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><path d=%22M12 2 2 20h20L12 2z%22 fill=%22%23c1272d%22/><circle cx=%2210%22 cy=%2212%22 r=%221%22 fill=%22%23fff%22/><circle cx=%2214%22 cy=%2215%22 r=%221%22 fill=%22%23fff%22/><circle cx=%2212%22 cy=%229%22 r=%221%22 fill=%22%23fff%22/></svg>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=Caveat:wght@600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">

<script type="application/ld+json"><?= restaurantSchema() ?></script>
</head>
<body class="<?= e($bodyClass) ?>">

<div class="topbar">
  <div class="container topbar__inner">
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="topbar__item"><?= icon('phone', 'icon icon--sm') ?> <?= e(SITE_PHONE) ?></a>
    <span class="topbar__item topbar__item--address"><?= icon('pin', 'icon icon--sm') ?> <?= e(SITE_ADDRESS_FULL) ?></span>
    <span class="topbar__item topbar__item--hours"><?php if (isOpenNow()): ?><span class="dot dot--open"></span><?= h('Otvorené teraz · %s', e(formatHours(hoursForDay((int) date('N'))))) ?><?php else: ?><span class="dot dot--closed"></span><?= h('Zatvorené · Otvárame %s', e(nextOpeningLabel())) ?><?php endif; ?></span>
  </div>
</div>

<header class="site-header">
  <div class="container site-header__inner">
    <a href="<?= e(url('/')) ?>" class="logo">
      <img src="/assets/img/logo_tomino_b.png" alt="<?= e(SITE_NAME) ?>" class="logo__img">
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="<?= e(url('/')) ?>" class="<?= isActivePage('index.php') ?>"><?= h('Domov') ?></a></li>
        <li><a href="<?= e(url('/menu')) ?>" class="<?= isActivePage('menu.php') ?>"><?= h('Menu') ?></a></li>
        <li><a href="<?= e(url('/galeria')) ?>" class="<?= isActivePage('galeria.php') ?>"><?= h('Galéria') ?></a></li>
        <li><a href="<?= e(url('/oslavy-a-akcie')) ?>" class="<?= isActivePage('oslavy-a-akcie.php') ?>"><?= h('Oslavy a akcie') ?></a></li>
        <li class="nav-dropdown" id="navMoreDropdown">
          <button type="button" class="nav-dropdown__toggle<?= $moreNavActive ? ' active' : '' ?>" id="navMoreToggle" aria-haspopup="true" aria-expanded="false">
            <?= h('Viac') ?>
            <?= icon('chevron-down', 'icon icon--sm nav-dropdown__chevron') ?>
          </button>
          <ul class="nav-dropdown__menu">
            <li><a href="<?= e(url('/o-nas')) ?>" class="<?= isActivePage('o-nas.php') ?>"><?= h('O nás') ?></a></li>
            <li><a href="<?= e(url('/doprava-a-platba')) ?>" class="<?= isActivePage('doprava-a-platba.php') ?>"><?= h('Rozvoz a platba') ?></a></li>
          </ul>
        </li>
        <li><a href="<?= e(url('/kontakt')) ?>" class="<?= isActivePage('kontakt.php') ?>"><?= h('Kontakt') ?></a></li>
      </ul>
    </nav>

    <div class="site-header__actions">
      <nav class="lang-switch" aria-label="<?= h('Jazyk') ?>">
        <?php foreach (LANGUAGES as $code => $label): ?>
        <a href="<?= e(pageUrl($currentScript, $code)) ?>" hreflang="<?= e($code) ?>" lang="<?= e($code) ?>" title="<?= e($label) ?>"<?= $code === lang() ? ' class="active" aria-current="true"' : '' ?>><?= e(strtoupper($code)) ?></a>
        <?php endforeach; ?>
      </nav>
      <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--header-call"><?= icon('phone', 'icon icon--sm') ?> <?= e(SITE_PHONE) ?></a>
      <button class="nav-toggle" id="navToggle" aria-label="<?= h('Otvoriť menu') ?>" aria-expanded="false" aria-controls="mainNav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
<?php if (($notice = siteNotice()) !== ''): ?>
<div class="site-notice" role="status">
  <div class="container site-notice__inner"><?= icon('info') ?><p><?= nl2br(e($notice)) ?></p></div>
</div>
<?php endif; ?>
