<?php
$pageTitle = 'Poskladaj si pizzu';
$pageDescription = 'Vytvorte si vlastnú pizzu presne podľa chuti - vyberte si prísady a sledujte prehľad a cenu naživo.';
$bodyClass = 'page-builder';
require_once __DIR__ . '/includes/header.php';

$builder = getPizzaBuilder();
$base = $builder['base'];
$groups = $builder['groups'];
?>

<?= renderToppingSprite() ?>

<section class="page-hero page-hero--builder">
  <div class="container">
    <p class="eyebrow">Poskladaj si pizzu</p>
    <h1>Vytvorte si pizzu presne podľa seba</h1>
    <p class="page-hero__lead">Základ tvorí paradajková omáčka a mozzarella. Pridajte si obľúbené prísady a sledujte prehľad aj cenu naživo.</p>
  </div>
</section>

<section class="section builder-section" id="pizzaBuilder">

  <div class="container builder-grid">

    <div class="builder-visual">
      <div class="builder-summary">
        <h2>Vaša pizza</h2>
        <p class="builder-summary__base"><?= icon('pizza', 'icon icon--sm') ?> <?= e($base['name']) ?></p>

        <ul class="builder-summary__list" id="builderSelectedList">
          <li class="builder-summary__empty" id="builderEmptyHint">Zatiaľ bez extra prísad - vyberte niečo z ponuky vpravo.</li>
        </ul>
      </div>

      <div class="builder-price-box">
        <span>Cena vašej pizze</span>
        <strong id="builderPrice"><?= formatPrice($base['price']) ?></strong>
      </div>
      <button type="button" class="btn btn--primary btn--lg btn--full" id="builderAddToCart">+ Pridať do košíka</button>
      <p class="note builder-note">Cenu si pred odoslaním objednávky vždy overíme aj u nás na serveri.</p>
    </div>

    <div class="builder-controls">
      <div class="builder-base-info">
        <h2>Základ pizze</h2>
        <p><?= e($base['name']) ?> - <strong><?= formatPrice($base['price']) ?></strong></p>
      </div>

      <?php foreach ($groups as $groupKey => $group): ?>
      <div class="builder-group">
        <h3><?= e($group['label']) ?></h3>
        <div class="topping-chips">
          <?php foreach ($group['items'] as $item): ?>
          <button type="button" class="topping-chip" data-topping="<?= e($item['id']) ?>">
            <?= toppingIconUse($item['id'], 'topping-chip__icon') ?>
            <span class="topping-chip__name"><?= e($item['name']) ?></span>
            <span class="topping-chip__price">+<?= number_format($item['price'], 2, ',', ' ') ?> €</span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Nechce sa vám skladať vlastnú?</h2>
      <p>Pozrite si aj naše hotové obľúbené kombinácie v menu.</p>
    </div>
    <a href="/menu.php" class="btn btn--primary btn--lg">Zobraziť menu</a>
  </div>
</section>

<script>
  window.PT_BUILDER = <?= json_encode([
      'base' => ['price' => $base['price']],
      'toppings' => array_merge(...array_map(fn($g) => $g['items'], array_values($groups))),
  ], JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="/assets/js/pizza-builder.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
