<?php
/**
 * Jedálny lístok na tlač / do PDF (A4). Z tejto stránky generuje
 * tools/menu-pdf.ps1 súbory assets/menu/pizzeria-tominno-menu-sk.pdf a -hu.pdf.
 * Údaje berie z data/menu.php - po zmene menu stačí PDF pregenerovať.
 * Stránka sa neindexuje (nie je určená návštevníkom webu).
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$menu = getMenu();
$allergens = getAllergens();
?><!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex">
<title><?= h('Jedálny lístok') ?> | <?= e(SITE_NAME) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  @page { size: A4; margin: 12mm 12mm 14mm; }
  * { box-sizing: border-box; }
  body { margin: 0; font-family: 'Inter', sans-serif; font-size: 8.3pt; color: #2b211d; line-height: 1.3; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  h1, h2, h3 { font-family: 'Poppins', sans-serif; margin: 0; }

  .cover { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-bottom: 10px; border-bottom: 2px solid #c1272d; margin-bottom: 12px; }
  .cover img { height: 46px; }
  .cover__text { text-align: right; }
  .cover__text h1 { font-size: 17pt; color: #c1272d; letter-spacing: 0.02em; }
  .cover__text p { margin: 2px 0 0; color: #7a6c63; font-size: 8.5pt; }

  .columns { column-count: 2; column-gap: 9mm; }
  .cat { break-inside: auto; margin-bottom: 8px; }
  .cat__head { break-after: avoid; break-inside: avoid; background: #241a15; color: #fff; border-radius: 5px; padding: 5px 9px; margin-bottom: 5px; }
  .cat__head h2 { font-size: 11pt; color: #fff; }
  .cat__head p { margin: 1px 0 0; color: #e3a018; font-size: 7.5pt; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }
  .cat__note { color: #7a6c63; font-size: 7.6pt; margin: 0 0 4px; }

  .item { break-inside: avoid; padding: 3px 0; border-bottom: 1px dotted #ddd0c4; }
  .item__top { display: flex; align-items: baseline; gap: 6px; }
  .item__name { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 9pt; }
  .item__num { color: #c1272d; }
  .item__dots { flex: 1; }
  .item__price { font-family: 'Poppins', sans-serif; font-weight: 700; color: #c1272d; white-space: nowrap; }
  .item__price small { font-family: 'Inter', sans-serif; font-weight: 500; color: #7a6c63; font-size: 7.4pt; margin-right: 3px; }
  .item__prices { display: flex; flex-direction: column; align-items: flex-end; }
  .item__desc { margin: 1px 0 0; color: #5c4f47; font-size: 7.8pt; }
  .item__allergens { color: #9a8b81; font-size: 7pt; }

  .footer { break-inside: avoid; margin-top: 4px; padding: 7px 9px; border: 1.5px solid #c1272d; border-radius: 5px; }
  .footer + .footer { margin-top: 6px; }
  .footer h3 { font-size: 9pt; color: #c1272d; margin-bottom: 3px; }
  .footer p { margin: 0 0 2px; }
  .allergens { margin: 0; font-size: 7pt; color: #5c4f47; }
  .allergens b { color: #2b211d; }
  .contact strong { font-family: 'Poppins', sans-serif; font-size: 12pt; color: #c1272d; }
  .note { color: #7a6c63; font-size: 7pt; margin-top: 4px; }
</style>
</head>
<body>

<header class="cover">
  <img src="/assets/img/logo_tomino_b.png" alt="<?= e(SITE_NAME) ?>">
  <div class="cover__text">
    <h1><?= h('Jedálny lístok') ?></h1>
    <p><?= h(SITE_CLAIM) ?></p>
  </div>
</header>

<main class="columns">
  <?php foreach ($menu as $category): ?>
  <section class="cat">
    <div class="cat__head">
      <h2><?= h($category['label']) ?></h2>
      <?php if (!empty($category['subtitle'])): ?><p><?= h($category['subtitle']) ?></p><?php endif; ?>
    </div>
    <?php if (!empty($category['note'])): ?><p class="cat__note"><?= h($category['note']) ?></p><?php endif; ?>
    <?php foreach ($category['items'] as $item): ?>
    <div class="item">
      <div class="item__top">
        <span class="item__name"><?php if (!empty($item['num'])): ?><span class="item__num"><?= (int) $item['num'] ?>.</span> <?php endif; ?><?= h($item['name']) ?></span>
        <span class="item__dots"></span>
        <span class="item__prices">
          <?php foreach (menuItemPrices($item) as $variant): ?>
          <span class="item__price"><?php if ($variant['label'] !== ''): ?><small><?= h($variant['label']) ?></small><?php endif; ?><?= formatPrice($variant['price']) ?></span>
          <?php endforeach; ?>
        </span>
      </div>
      <?php if (!empty($item['desc']) || !empty($item['allergens'])): ?>
      <p class="item__desc"><?= e(tList($item['desc'])) ?><?php if (!empty($item['allergens'])): ?> <span class="item__allergens">(<?= e(implode(', ', $item['allergens'])) ?>)</span><?php endif; ?></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </section>
  <?php endforeach; ?>

  <footer class="footer contact">
    <h3><?= h('Objednávky telefonicky') ?></h3>
    <p><strong><?= e(SITE_PHONE) ?></strong></p>
    <p><?= e(SITE_ADDRESS_FULL) ?> · <?= e(openingHoursSummary()) ?></p>
    <p><?= h('Rozvoz: %s · Platba hotovosť / karta', h(DELIVERY_AREA)) ?></p>
    <p><?= e(preg_replace('~^https?://~', '', SITE_URL)) ?></p>
  </footer>

  <footer class="footer">
    <h3><?= h('Zoznam alergénov') ?></h3>
    <p class="allergens"><?php $i = 0; foreach ($allergens as $code => $label): ?><?= $i++ ? ' · ' : '' ?><b><?= (int) $code ?></b> <?= h($label) ?><?php endforeach; ?></p>
    <p class="note"><?= h('Zloženie a alergény sa môžu meniť podľa aktuálnej dostupnosti surovín. V prípade alergie alebo neznášanlivosti nám prosím povedzte pri objednávke.') ?></p>
  </footer>
</main>

</body>
</html>
