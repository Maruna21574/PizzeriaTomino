<?php
$pageTitle = 'Jedálny lístok';
$pageDescription = 'Kompletné menu Pizzeria Tominno - pizza, cestoviny, šaláty, dezerty a nápoje. Objednajte si s rozvozom, platba na dobierku.';
$bodyClass = 'page-menu';
require_once __DIR__ . '/includes/header.php';

$menu = getMenu();
?>

<section class="page-hero page-hero--menu">
  <div class="container">
    <p class="eyebrow">Jedálny lístok</p>
    <h1>Naše menu</h1>
    <p class="page-hero__lead">Vyberte si z pizze, cestovín, šalátov, dezertov a nápojov. Všetko si môžete pridať priamo do košíka a objednať s rozvozom domov.</p>
  </div>
</section>

<section class="section menu-section">
  <div class="container">

    <div class="menu-filters" id="menuFilters" role="tablist" aria-label="Kategórie menu">
      <?php $first = true; foreach ($menu as $key => $category): ?>
      <button type="button" class="menu-filter<?= $first ? ' active' : '' ?>" data-filter="<?= e($key) ?>" role="tab" aria-selected="<?= $first ? 'true' : 'false' ?>">
        <span><?= icon($category['icon']) ?></span> <?= e($category['label']) ?>
      </button>
      <?php $first = false; endforeach; ?>
    </div>

    <?php foreach ($menu as $key => $category): ?>
    <div class="menu-group" data-group="<?= e($key) ?>" id="cat-<?= e($key) ?>">
      <h2 class="menu-group__title"><?= e($category['label']) ?></h2>
      <div class="card-grid">
        <?php foreach ($category['items'] as $item): ?>
        <article class="food-card">
          <div class="food-card__img" style="background-image:url('<?= e($item['img']) ?>')">
            <?php if (!empty($item['tags'])): ?>
            <span class="food-card__tag"><?= e(ucfirst($item['tags'][0])) ?></span>
            <?php endif; ?>
            <?= allergenTriggerButton($item) ?>
          </div>
          <div class="food-card__body">
            <div class="food-card__top">
              <div>
                <h3><?= e($item['name']) ?></h3>
                <?php if (!empty($item['weight'])): ?>
                <span class="food-card__weight"><?= e($item['weight']) ?></span>
                <?php endif; ?>
              </div>
              <span class="food-card__price"><?= formatPrice($item['price']) ?></span>
            </div>
            <?php if (!empty($item['desc'])): ?>
            <p><?= e($item['desc']) ?></p>
            <?php endif; ?>
            <button class="btn btn--add" data-add-to-cart data-id="<?= e($item['id']) ?>" data-name="<?= e($item['name']) ?>" data-price="<?= e($item['price']) ?>">
              + Pridať do košíka
            </button>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Máte plný košík?</h2>
      <p>Prejdite k objednávke a vyberte si spôsob platby na dobierku.</p>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--lg">Prejsť do košíka</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
