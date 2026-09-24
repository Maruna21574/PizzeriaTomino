<?php
$pageTitle = 'Neapolská pizza z pece na drevo';
$pageDescription = 'Pizzeria Tominno v Novosade - neapolská pizza pečená v peci na drevo, talianska múka a cesto fermentované 48 hodín. Rozvoz Novosad, Trebišov a okolie, platba hotovosťou alebo kartou.';
$bodyClass = 'page-home';
require_once __DIR__ . '/includes/header.php';

$menu = getMenu();
$featured = featuredMenuItems();
$pizzaCount = count($menu['pizza']['items'] ?? []);

$categoryTiles = [
    ['url' => '/menu#cat-burgery', 'photo' => 'burger-tekuty-cheddar', 'title' => 'Burgery',        'text' => 'Domáce hovädzie burgery, kurací strips aj vegaburger.'],
    ['url' => '/menu#cat-kebab',   'photo' => 'kebab-tanier',          'title' => 'Kebab a grill',  'text' => 'Kebab v pizza chlebe, tortilly, stripsy a boxy.'],
    ['url' => '/menu#cat-salaty',  'photo' => 'bufala-salat',          'title' => 'Šaláty',         'text' => 'Bufala, caprese, s kozím syrom či granátovým jablkom.'],
    ['url' => '/oslavy-a-akcie',   'photo' => 'party-misa-1',          'title' => 'Oslavy a akcie', 'text' => 'Oslavy, firemné akcie a párty misy na objednávku.'],
];

$galleryTeaser = galleryPhotos('home', 6);
$phoneLink = '<a href="tel:' . e(SITE_PHONE_TEL) . '">' . e(SITE_PHONE) . '</a>';
?>

<section class="hero">
  <div class="hero__bg" style="background-image:url('<?= e(photo('pizza-margherita-pec')) ?>')"></div>
  <div class="hero__overlay"></div>
  <div class="container hero__content">
    <p class="eyebrow"><?= h('Novosad · Trebišov a okolie') ?></p>
    <h1><?= h('Neapolská pizza') ?><br><?= h('pečená v %s', '<span>' . h('peci na drevo') . '</span>') ?></h1>
    <p class="hero__lead"><?= h('Talianska múka, cesto fermentované 48 hodín a poctivé talianske suroviny. Pizzu vám radi dovezieme - stačí zavolať.') ?></p>
    <div class="hero__actions">
      <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= h('Zavolať a objednať') ?></a>
      <a href="<?= e(url('/menu')) ?>" class="btn btn--ghost btn--lg"><?= h('Pozrieť menu') ?></a>
    </div>
    <ul class="hero__badges">
      <li><?= icon('clock', 'icon icon--sm') ?> <?= h('Cesto fermentované 48 hodín') ?></li>
      <li><?= icon('wheat', 'icon icon--sm') ?> <?= h('Talianska múka') ?></li>
      <li><?= icon('flame', 'icon icon--sm') ?> <?= h('Pec na drevo') ?></li>
      <li><?= icon('truck', 'icon icon--sm') ?> <?= h('Rozvoz · hotovosť / karta') ?></li>
    </ul>
  </div>
</section>

<section class="section story">
  <div class="container">

    <div class="story-row">
      <div class="story-row__media">
        <video class="story-video" src="/assets/video/priprava-cesta.mp4" poster="/assets/video/priprava-cesta.jpg" autoplay muted loop playsinline preload="metadata" aria-label="<?= h('Video: takto u nás pripravujeme cesto na pizzu') ?>"></video>
      </div>
      <div class="story-row__text">
        <p class="eyebrow"><?= h('Tajomstvo je v ceste') ?></p>
        <h2><?= h('Cesto fermentované 48 hodín') ?></h2>
        <p><?= h('Takto u nás vzniká cesto na pizzu. Miesime ho z talianskej múky, ručne tvarujeme bochníky a potom ho necháme v pokoji dozrieť celých 48 hodín.') ?></p>
        <p><?= h('Dlhá fermentácia robí cesto ľahké, vzdušné a lepšie stráviteľné. Okraj pizze sa v peci krásne nafúkne a dostane typické opečené bodky - presne ako v Neapole.') ?></p>
        <ul class="check-list">
          <li><?= h('Talianska múka na neapolskú pizzu') ?></li>
          <li><?= h('48 hodín pomalej fermentácie') ?></li>
          <li><?= h('Každý bochník tvarujeme ručne') ?></li>
        </ul>
      </div>
    </div>

    <div class="story-row story-row--reverse">
      <div class="story-row__media">
        <video class="story-video" src="/assets/video/pec-na-drevo.mp4" poster="/assets/video/pec-na-drevo.jpg" autoplay muted loop playsinline preload="metadata" aria-label="<?= h('Video: pizza sa pečie v našej peci na drevo') ?>"></video>
      </div>
      <div class="story-row__text">
        <p class="eyebrow"><?= h('Oheň a drevo') ?></p>
        <h2><?= h('Pečieme v peci na drevo') ?></h2>
        <p><?= h('Naša neapolská pizza sa pečie v rozpálenej peci na drevo. Vysoká teplota ju upečie za krátky čas - spodok je chrumkavý, okraj nadýchaný a suroviny zostanú šťavnaté.') ?></p>
        <p><?= h('Tú pravú chuť ohňa nenahradí žiadna elektrická pec. Najlepšia je pizza priamo u nás, vybratá z rozpálenej pece - no radi vám ju dovezieme aj domov.') ?></p>
        <a href="<?= e(url('/menu')) ?>" class="btn btn--outline"><?= h('Pozrieť ponuku pizze') ?></a>
      </div>
    </div>

  </div>
</section>

<section class="section ingredients">
  <div class="container ingredients__grid">
    <div class="ingredients__photos">
      <img src="<?= e(photo('talianske-suroviny', true)) ?>" alt="<?= h('Talianske suroviny, ktoré používame - paradajky Rosso Gargano, prosciutto, syry') ?>" loading="lazy">
      <img src="<?= e(photo('talianska-muka', true)) ?>" alt="<?= h('Talianska múka na neapolskú pizzu') ?>" loading="lazy">
    </div>
    <div class="ingredients__text">
      <p class="eyebrow"><?= h('Talianske suroviny') ?></p>
      <h2><?= h('Chuť Talianska v každom kúsku') ?></h2>
      <p><?= h('Na pizzu používame taliansky tovar a taliansku múku. Neapolská pizza stojí na jednoduchých, ale poctivých surovinách - preto na nich nešetríme.') ?></p>
      <ul class="ingredients__list">
        <li><?= icon('wheat') ?><span><strong><?= h('Talianska múka') ?></strong> <?= h('na neapolské cesto') ?></span></li>
        <li><?= icon('leaf') ?><span><strong>Pomodoro pelato Rosso Gargano</strong> - <?= h('lúpané talianske paradajky') ?></span></li>
        <li><?= icon('heart') ?><span><strong>Mozzarella Fiordilatte Taglio Napoli</strong></span></li>
        <li><?= icon('chef-hat') ?><span><strong>Prosciutto crudo stagionato</strong>, <?= h('burrata, gorgonzola, ventricina') ?></span></li>
      </ul>
    </div>
  </div>
</section>

<section class="section featured">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Z našej pece') ?></p>
      <h2><?= h('Neapolská pizza') ?></h2>
      <p class="section__lead"><?= h('Ochutnávka z nášho jedálneho lístka - v menu nájdete %s druhov pizze.', (string) $pizzaCount) ?></p>
    </div>

    <div class="card-grid">
      <?php foreach ($featured as $item): ?>
      <article class="food-card">
        <div class="food-card__img" style="background-image:url('<?= e(photo($item['photo'], true)) ?>')"></div>
        <div class="food-card__body">
          <div class="food-card__top">
            <div>
              <h3><?php if (!empty($item['num'])): ?><?= (int) $item['num'] ?>. <?php endif; ?><?= e(tf($item, 'name')) ?></h3>
              <?php if (!empty($item['weight'])): ?><span class="food-card__weight"><?= e(t($item['weight'])) ?></span><?php endif; ?>
            </div>
            <span class="food-card__price"><?= formatPrice((float) menuItemPrices($item)[0]['price']) ?></span>
          </div>
          <p><?= e(tf($item, 'desc', true)) ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="section__cta section__cta--row">
      <a href="<?= e(url('/menu')) ?>" class="btn btn--outline btn--lg"><?= h('Zobraziť celé menu') ?></a>
      <a href="<?= e(menuPdfUrl()) ?>" class="btn btn--outline btn--lg" download><?= icon('download') ?> <?= h('Menu na stiahnutie (PDF)') ?></a>
    </div>
  </div>
</section>

<section class="section section--alt categories">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Nielen pizza') ?></p>
      <h2><?= h('Burgery, kebab, šaláty a oslavy') ?></h2>
    </div>
    <div class="cat-tiles">
      <?php foreach ($categoryTiles as $tile): ?>
      <a class="cat-tile" href="<?= e(url($tile['url'])) ?>">
        <span class="cat-tile__img" style="background-image:url('<?= e(photo($tile['photo'], true)) ?>')"></span>
        <span class="cat-tile__body">
          <strong><?= h($tile['title']) ?></strong>
          <span><?= h($tile['text']) ?></span>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section delivery-teaser">
  <div class="container delivery-teaser__grid">
    <img class="delivery-teaser__img" src="<?= e(photo('rozvoz-auto', true)) ?>" alt="<?= h('Rozvozové auto Pizzeria Tominno') ?>" loading="lazy">
    <div>
      <p class="eyebrow"><?= h('Rozvoz') ?></p>
      <h2><?= h('Dovezieme vám ju domov') ?></h2>
      <p><?= h('Rozvážame po obciach %s. Objednávky prijímame telefonicky, platíte až pri prevzatí.', '<strong>' . e(deliveryArea()) . '</strong>') ?></p>
      <ul class="check-list">
        <li><?= h('Objednávka telefonicky na %s', $phoneLink) ?></li>
        <li><?= h('Platba v hotovosti alebo kartou') ?></li>
        <li><?= h('Otváracie hodiny: %s', e(openingHoursSummary())) ?></li>
      </ul>
      <a href="<?= e(url('/doprava-a-platba')) ?>" class="btn btn--outline"><?= h('Rozvoz a platba') ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/reviews.php'; ?>

<section class="section gallery-teaser">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Galéria') ?></p>
      <h2><?= h('Pozrite sa k nám') ?></h2>
    </div>
    <div class="gallery-teaser__grid">
      <?php foreach ($galleryTeaser as $photo): ?>
      <a href="<?= e(url('/galeria')) ?>" class="gallery-teaser__item"><img src="<?= e(photo($photo['file'], true)) ?>" alt="<?= e(tf($photo, 'label')) ?> - Pizzeria Tominno" loading="lazy"></a>
      <?php endforeach; ?>
    </div>
    <div class="section__cta">
      <a href="<?= e(url('/galeria')) ?>" class="btn btn--outline btn--lg"><?= h('Celá galéria') ?></a>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= h('Dostali ste chuť?') ?></h2>
      <p><?= h('Zavolajte nám a objednajte si - rozvoz Novosad, Trebišov a okolie.') ?></p>
    </div>
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
