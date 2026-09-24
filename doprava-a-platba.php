<?php
$pageTitle = 'Rozvoz a platba';
$pageDescription = 'Rozvoz Pizzeria Tominno - Novosad, Trebišov a okolie. Objednávky telefonicky, platba hotovosťou alebo kartou pri prevzatí.';
$bodyClass = 'page-delivery';
require_once __DIR__ . '/includes/header.php';

$phoneLink = '<a href="tel:' . e(SITE_PHONE_TEL) . '">' . e(SITE_PHONE) . '</a>';
?>

<section class="page-hero page-hero--delivery">
  <div class="container">
    <p class="eyebrow"><?= h('Rozvoz a platba') ?></p>
    <h1><?= h('Dovezieme vám ju domov') ?></h1>
    <p class="page-hero__lead"><?= h('Rozvoz po obciach %s. Objednávky prijímame telefonicky, platíte hotovosťou alebo kartou.', e(deliveryArea())) ?></p>
  </div>
</section>

<section class="section info-section">
  <div class="container info-grid">

    <div class="info-card">
      <span class="info-card__icon"><?= icon('phone') ?></span>
      <h2><?= h('Ako objednať') ?></h2>
      <p><?= h('Objednávky prijímame telefonicky na čísle %s.', $phoneLink) ?></p>
      <ul class="check-list">
        <li><?= h('Vyberte si z nášho %s', '<a href="' . e(url('/menu')) . '">' . h('jedálneho lístka') . '</a>') ?></li>
        <li><?= h('Zavolajte nám a nahláste objednávku a adresu') ?></li>
        <li><?= h('Povieme vám, kedy objednávku dovezieme') ?></li>
      </ul>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('truck') ?></span>
      <h2><?= h('Kam rozvážame') ?></h2>
      <p><?= h('Rozvážame vlastným autom do oblasti: %s.', '<strong>' . e(deliveryArea()) . '</strong>') ?></p>
      <ul class="check-list">
        <li><?= h('Minimálna hodnota objednávky: %s', '<strong>' . formatPrice(DELIVERY_MIN_ORDER) . '</strong>') ?></li>
        <li><?= h('Poplatok za dopravu: %s', '<strong>' . formatPrice(DELIVERY_FEE) . '</strong>') ?></li>
        <li><?= h('Doprava zdarma pri objednávke nad %s', '<strong>' . formatPrice(DELIVERY_FREE_FROM) . '</strong>') ?></li>
      </ul>
      <p class="note"><?= h('Ak si nie ste istí, či rozvážame aj k vám, pokojne nám zavolajte.') ?></p>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('card') ?></span>
      <h2><?= h('Platba') ?></h2>
      <p><?= h('Platíte až pri prevzatí objednávky:') ?></p>
      <ul class="check-list">
        <li><strong><?= h('Hotovosť') ?></strong></li>
        <li><strong><?= h('Platobná karta') ?></strong> - <?= h('máme so sebou platobný terminál') ?></li>
      </ul>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('clock') ?></span>
      <h2><?= h('Otváracie hodiny') ?></h2>
      <ul class="footer-hours footer-hours--card">
        <?php foreach (OPENING_HOURS as $day => $hours): ?>
        <li><span><?= h($day) ?></span><span><?= e(formatHours($hours)) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= h('Pripravení objednať?') ?></h2>
      <p><?= h('Zavolajte nám - neapolskú pizzu z pece na drevo vám dovezieme.') ?></p>
    </div>
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
