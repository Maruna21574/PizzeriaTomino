<?php
$pageTitle = 'Doprava a platba';
$pageDescription = 'Informácie o rozvoze a platbe Pizzeria Tominno - doručenie kuriérom, platba na dobierku hotovosťou alebo kartou.';
$bodyClass = 'page-delivery';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--delivery">
  <div class="container">
    <p class="eyebrow">Doprava a platba</p>
    <h1>Ako prebieha doručenie</h1>
    <p class="page-hero__lead">Objednávku pripravíme čerstvú a doručíme priamo k vám. Bez platobnej brány, bez registrácie - jednoducho na dobierku.</p>
  </div>
</section>

<section class="section info-section">
  <div class="container info-grid">

    <div class="info-card">
      <span class="info-card__icon"><?= icon('truck') ?></span>
      <h2>Rozvoz kuriérom</h2>
      <p>Objednávky rozvážame vlastným kuriérom do oblasti: <strong><?= e(DELIVERY_AREA) ?></strong>.</p>
      <ul class="check-list">
        <li>Doručenie zvyčajne do 30-45 minút od potvrdenia objednávky</li>
        <li>Presný čas doručenia vám môžeme potvrdiť telefonicky</li>
        <li>Mimo bežnej rozvozovej oblasti nás prosím kontaktujte telefonicky</li>
      </ul>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('cash') ?></span>
      <h2>Poplatky za dopravu</h2>
      <ul class="check-list">
        <li>Minimálna hodnota objednávky: <strong><?= formatPrice(DELIVERY_MIN_ORDER) ?></strong></li>
        <li>Poplatok za dopravu: <strong><?= formatPrice(DELIVERY_FEE) ?></strong></li>
        <li>Doprava zdarma pri objednávke nad <strong><?= formatPrice(DELIVERY_FREE_FROM) ?></strong></li>
      </ul>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('card') ?></span>
      <h2>Spôsoby platby</h2>
      <p>Neplatíte vopred online - platíte až pri prevzatí objednávky:</p>
      <ul class="check-list">
        <li><strong>Hotovosť</strong> - zaplatíte kuriérovi pri odovzdaní objednávky</li>
        <li><strong>Platobná karta</strong> - kuriér má k dispozícii prenosný platobný terminál</li>
      </ul>
      <p class="note">Platba vopred online (platobná brána) momentálne nie je k dispozícii.</p>
    </div>

    <div class="info-card">
      <span class="info-card__icon"><?= icon('clock') ?></span>
      <h2>Otváracie hodiny</h2>
      <ul class="footer-hours footer-hours--card">
        <?php foreach (OPENING_HOURS as $day => $hours): ?>
        <li><span><?= e($day) ?></span><span><?= e($hours) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Pripravení objednať?</h2>
      <p>Vyberte si z menu a nechajte si pizzu doviezť priamo k vám.</p>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--lg">Objednať teraz</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
