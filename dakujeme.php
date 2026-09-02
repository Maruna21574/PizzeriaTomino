<?php
$pageTitle = 'Ďakujeme za objednávku';
$pageDescription = 'Vaša objednávka bola úspešne prijatá. Ďakujeme, že ste si vybrali Pizzeria Tominno.';
$bodyClass = 'page-thanks';
require_once __DIR__ . '/includes/header.php';

$orderId = isset($_GET['id']) ? preg_replace('/[^A-Z0-9\-]/', '', strtoupper($_GET['id'])) : '';
$total = isset($_GET['total']) ? (float) $_GET['total'] : null;
?>

<section class="section thanks-section">
  <div class="container thanks-box">
    <span class="thanks-icon"><?= icon('check-circle') ?></span>
    <h1>Ďakujeme za objednávku!</h1>
    <?php if ($orderId): ?>
    <p class="thanks-order-id">Číslo objednávky: <strong><?= e($orderId) ?></strong></p>
    <?php endif; ?>
    <?php if ($total !== null): ?>
    <p>Suma na úhradu kuriérovi: <strong><?= formatPrice($total) ?></strong></p>
    <?php endif; ?>
    <p>Vašu objednávku sme prijali a čoskoro ju začneme pripravovať. Kuriér ju doručí na uvedenú adresu - platbu vybavíte priamo pri prevzatí, hotovosťou alebo kartou.</p>
    <p>V prípade akýchkoľvek otázok nás kontaktujte na <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a>.</p>
    <div class="thanks-actions">
      <a href="/menu.php" class="btn btn--outline">Späť do menu</a>
      <a href="/index.php" class="btn btn--primary">Domov</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
