<?php
$pageTitle = 'Ochrana osobných údajov';
$pageDescription = 'Informácie o spracúvaní osobných údajov na webe Pizzeria Tominno - kontaktný formulár, telefonické objednávky, cookies a vaše práva.';
$bodyClass = 'page-privacy';
require_once __DIR__ . '/includes/header.php';

// Kým nie sú v config.php vyplnené údaje prevádzkovateľa, zobrazí sa
// zvýraznená výzva na doplnenie (aby sa na to pred spustením nezabudlo).
function companyField(string $value, string $placeholder): string
{
    return $value !== '' ? e($value) : '<mark>[' . e($placeholder) . ']</mark>';
}
?>

<section class="page-hero">
  <div class="container">
    <p class="eyebrow">GDPR</p>
    <h1><?= h('Ochrana osobných údajov') ?></h1>
    <p class="page-hero__lead"><?= h('Ako a prečo spracúvame vaše osobné údaje, ak nás kontaktujete alebo si u nás objednáte.') ?></p>
  </div>
</section>

<section class="section legal-section">
  <div class="container legal">

    <?php require __DIR__ . '/includes/privacy/' . lang() . '.php'; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
