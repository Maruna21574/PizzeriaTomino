<?php
/**
 * Spoločná hlavička (head + navigácia) pre všetky stránky.
 * Očakáva premenné $pageTitle, $pageDescription (voliteľné $bodyClass).
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';

$moreNavPages = ['o-nas.php', 'galeria.php', 'doprava-a-platba.php'];
$moreNavActive = in_array(basename($_SERVER['SCRIPT_NAME'] ?? ''), $moreNavPages, true);

$pageTitle = $pageTitle ?? SITE_NAME;
$pageDescription = $pageDescription ?? 'Pravá talianska pizza z pece na drevo priamo k vám domov. Rozvoz v Novosade, Michalovciach a okolí. Platba na dobierku - hotovosť alebo karta.';
$bodyClass = $bodyClass ?? '';
?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> | <?= e(SITE_NAME) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<meta name="theme-color" content="#c1272d">
<link rel="canonical" href="<?= e(SITE_URL . $_SERVER['REQUEST_URI']) ?>">

<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($pageTitle) ?> | <?= e(SITE_NAME) ?>">
<meta property="og:description" content="<?= e($pageDescription) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/img/tomino-og.jpg">
<meta property="og:locale" content="sk_SK">

<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><path d=%22M12 2 2 20h20L12 2z%22 fill=%22%23c1272d%22/><circle cx=%2210%22 cy=%2212%22 r=%221%22 fill=%22%23fff%22/><circle cx=%2214%22 cy=%2215%22 r=%221%22 fill=%22%23fff%22/><circle cx=%2212%22 cy=%229%22 r=%221%22 fill=%22%23fff%22/></svg>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=Caveat:wght@600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="<?= e($bodyClass) ?>">

<div class="topbar">
  <div class="container topbar__inner">
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="topbar__item"><?= icon('phone', 'icon icon--sm') ?> <?= e(SITE_PHONE) ?></a>
    <span class="topbar__item topbar__item--address"><?= icon('pin', 'icon icon--sm') ?> <?= e(SITE_ADDRESS_FULL) ?></span>
    <span class="topbar__item topbar__item--hours"><?= isOpenNow() ? '<span class="dot dot--open"></span>Otvorené teraz · 10:00 – 22:00' : '<span class="dot dot--closed"></span>Zatvorené · Otvárame o 10:00' ?></span>
  </div>
</div>

<header class="site-header">
  <div class="container site-header__inner">
    <a href="/index.php" class="logo">
      <img src="/assets/img/logo_tomino_b.png" alt="<?= e(SITE_NAME) ?>" class="logo__img">
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="/index.php" class="<?= isActivePage('index.php') ?>">Domov</a></li>
        <li><a href="/menu.php" class="<?= isActivePage('menu.php') ?>">Menu</a></li>
        <li><a href="/vlastna-pizza.php" class="<?= isActivePage('vlastna-pizza.php') ?>">Vlastná pizza</a></li>
        <li class="nav-dropdown" id="navMoreDropdown">
          <button type="button" class="nav-dropdown__toggle<?= $moreNavActive ? ' active' : '' ?>" id="navMoreToggle" aria-haspopup="true" aria-expanded="false">
            Viac
            <?= icon('chevron-down', 'icon icon--sm nav-dropdown__chevron') ?>
          </button>
          <ul class="nav-dropdown__menu">
            <li><a href="/o-nas.php" class="<?= isActivePage('o-nas.php') ?>">O nás</a></li>
            <li><a href="/galeria.php" class="<?= isActivePage('galeria.php') ?>">Galéria</a></li>
            <li><a href="/doprava-a-platba.php" class="<?= isActivePage('doprava-a-platba.php') ?>">Doprava a platba</a></li>
          </ul>
        </li>
        <li><a href="/kontakt.php" class="<?= isActivePage('kontakt.php') ?>">Kontakt</a></li>
      </ul>
    </nav>

    <div class="site-header__actions">
      <a href="/objednavka.php" class="btn btn--cart" id="cartLink">
        <span class="btn__icon"><?= icon('cart') ?></span>
        <span>Košík</span>
        <span class="cart-badge" id="cartBadge" hidden>0</span>
      </a>
      <a href="/objednavka.php" class="btn btn--primary btn--order-now">Objednať</a>
      <button class="nav-toggle" id="navToggle" aria-label="Otvoriť menu" aria-expanded="false" aria-controls="mainNav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
