<?php
http_response_code(404);
$pageTitle = 'Stránka nenájdená';
$pageDescription = 'Požadovaná stránka nebola nájdená.';
$bodyClass = 'page-404';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section error-section">
  <div class="container error-box">
    <span class="error-code">404</span>
    <h1>Stránku sa nepodarilo nájsť</h1>
    <p>Zdá sa, že táto stránka neexistuje alebo bola presunutá. Skúste sa vrátiť na domovskú stránku alebo si pozrite naše menu.</p>
    <div class="thanks-actions">
      <a href="/menu.php" class="btn btn--outline">Zobraziť menu</a>
      <a href="/index.php" class="btn btn--primary">Domov</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
