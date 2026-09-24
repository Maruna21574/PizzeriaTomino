<?php
http_response_code(404);
$pageTitle = 'Stránka nenájdená';
$pageDescription = 'Požadovaná stránka nebola nájdená.';
$bodyClass = 'page-404';
$noIndex = true;
require_once __DIR__ . '/includes/header.php';
?>

<section class="section error-section">
  <div class="container error-box">
    <span class="error-code">404</span>
    <h1><?= h('Stránku sa nepodarilo nájsť') ?></h1>
    <p><?= h('Zdá sa, že táto stránka neexistuje alebo bola presunutá. Skúste sa vrátiť na domovskú stránku alebo si pozrite naše menu.') ?></p>
    <div class="thanks-actions">
      <a href="<?= e(url('/menu')) ?>" class="btn btn--outline"><?= h('Zobraziť menu') ?></a>
      <a href="<?= e(url('/')) ?>" class="btn btn--primary"><?= h('Domov') ?></a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
