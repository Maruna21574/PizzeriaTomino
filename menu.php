<?php
$pageTitle = 'Jedálny lístok';
$pageDescription = 'Jedálny lístok Pizzeria Tominno - neapolská pizza z pece na drevo, burgery, kebab, šaláty, prílohy a sladké. Objednávky telefonicky, rozvoz Novosad, Trebišov a okolie.';
$bodyClass = 'page-menu';
require_once __DIR__ . '/includes/header.php';

$menu = getMenu();
$allergens = getAllergens();
?>

<section class="page-hero page-hero--menu">
  <div class="container">
    <p class="eyebrow"><?= h('Jedálny lístok') ?></p>
    <h1><?= h('Naše menu') ?></h1>
    <p class="page-hero__lead"><?= h('Neapolská pizza pečená v peci na drevo, domáce burgery, kebab, šaláty a ďalšie dobroty. Objednávky prijímame telefonicky na %s.', '<a href="tel:' . e(SITE_PHONE_TEL) . '">' . e(SITE_PHONE) . '</a>') ?></p>
    <div class="page-hero__actions">
      <a href="<?= e(menuPdfUrl()) ?>" class="btn btn--ghost" download><?= icon('download') ?> <?= h('Menu na stiahnutie (PDF)') ?></a>
    </div>
  </div>
</section>

<section class="section menu-section">
  <div class="container">

    <div class="menu-filters" id="menuFilters" role="tablist" aria-label="<?= h('Kategórie menu') ?>">
      <?php $first = true; foreach ($menu as $key => $category): ?>
      <button type="button" class="menu-filter<?= $first ? ' active' : '' ?>" data-filter="<?= e($key) ?>" role="tab" aria-selected="<?= $first ? 'true' : 'false' ?>">
        <span><?= icon($category['icon']) ?></span> <?= e(tf($category, 'label')) ?>
      </button>
      <?php $first = false; endforeach; ?>
    </div>

    <?php foreach ($menu as $key => $category): ?>
    <div class="menu-group" data-group="<?= e($key) ?>" id="cat-<?= e($key) ?>">
      <header class="menu-group__head" style="background-image:url('<?= e(photo($category['photo'] ?? 'pizza-v-peci')) ?>')">
        <div class="menu-group__head-text">
          <h2><?= e(tf($category, 'label')) ?></h2>
          <?php if (!empty($category['subtitle'])): ?>
          <p class="menu-group__subtitle"><?= e(tf($category, 'subtitle')) ?></p>
          <?php endif; ?>
        </div>
      </header>
      <?php if (!empty($category['note'])): ?>
      <p class="menu-group__note"><?= e(tf($category, 'note')) ?>
        <?php if (!empty($category['link'])): ?><a href="<?= e(url($category['link']['url'])) ?>"><?= h($category['link']['label']) ?> &rarr;</a><?php endif; ?>
      </p>
      <?php endif; ?>

      <div class="menu-list">
        <?php foreach ($category['items'] as $item): ?>
        <article class="menu-item<?= !empty($item['photo']) ? ' menu-item--photo' : '' ?>">
          <?php if (!empty($item['photo'])): ?>
          <img class="menu-item__img" src="<?= e(photo($item['photo'], true)) ?>" alt="<?= e(tf($item, 'name')) ?>" loading="lazy">
          <?php endif; ?>
          <div class="menu-item__body">
            <div class="menu-item__top">
              <h3>
                <?php if (!empty($item['num'])): ?><span class="menu-item__num"><?= (int) $item['num'] ?>.</span><?php endif; ?>
                <?= e(tf($item, 'name')) ?>
              </h3>
              <div class="menu-item__prices">
                <?php foreach (menuItemPrices($item) as $variant): ?>
                <span class="menu-item__price">
                  <?php if ($variant['label'] !== ''): ?><small><?= h($variant['label']) ?></small><?php endif; ?>
                  <?= formatPrice($variant['price']) ?>
                </span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php if (!empty($item['desc'])): ?>
            <p class="menu-item__desc"><?= e(tf($item, 'desc', true)) ?></p>
            <?php endif; ?>
            <?php if (!empty($item['allergens'])): ?>
            <p class="menu-item__allergens" title="<?= e(implode(', ', array_map(function ($code) use ($allergens) { return $code . '. ' . t($allergens[$code] ?? ''); }, $item['allergens']))) ?>"><?= h('Alergény: %s', e(implode(', ', $item['allergens']))) ?></p>
            <?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <aside class="allergen-legend">
      <h2><?= icon('info') ?> <?= h('Zoznam alergénov') ?></h2>
      <ol>
        <?php foreach ($allergens as $code => $label): ?>
        <li value="<?= (int) $code ?>"><?= h($label) ?></li>
        <?php endforeach; ?>
      </ol>
      <p class="note"><?= h('Zloženie a alergény sa môžu meniť podľa aktuálnej dostupnosti surovín. V prípade alergie alebo neznášanlivosti nám prosím povedzte pri objednávke.') ?></p>
    </aside>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= h('Vybrali ste si?') ?></h2>
      <p><?= h('Zavolajte nám a objednávku vám dovezieme - platba hotovosťou alebo kartou.') ?></p>
    </div>
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
