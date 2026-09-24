<?php
$pageTitle = 'Galéria';
$pageDescription = 'Fotky a videá z Pizzeria Tominno v Novosade - neapolská pizza z pece na drevo, burgery, kebab, párty misy, naša prevádzka a akcie.';
$bodyClass = 'page-gallery';
require_once __DIR__ . '/includes/header.php';

// Sekcie galérie - názvy súborov z assets/img/foto (bez prípony) => popis.
$gallerySections = [
    'Neapolská pizza' => [
        'pizza-margherita-pec'     => 'Margherita z pece na drevo',
        'pizza-v-peci'             => 'Pizza v peci na drevo',
        'pizza-margherita'         => 'Margherita',
        'pizza-gorgonzola'         => 'Gorgonzola s hruškami a orechmi',
        'pizza-kukuricova'         => 'Kukuricová',
        'pizza-prosciutto-burrata' => 'Pizza s prosciuttom a burratou',
        'pizza-prosciutto-rukola'  => 'Pizza s prosciuttom a rukolou',
        'pizza-rukola-parmezan'    => 'Pizza s rukolou a parmezánom',
        'pizza-neapolska'          => 'Neapolská pizza',
    ],
    'Burgery' => [
        'burger-tekuty-cheddar'       => 'Burger s tekutým cheddarom',
        'burger-cierny-s-hranolkami'  => 'Špeciál hovädzí burger',
        'burgre-cierny-a-cerveny'     => 'Burgery pred pecou',
        'burgre-pred-pecou'           => 'Burgery z našej kuchyne',
        'burger-cerveny'              => 'Burger v červenej žemli',
        'mini-burgre'                 => 'Mini burgery',
        'burger-1'                    => 'Domáci hovädzí burger',
        'burger-2'                    => 'Burger s tekvicovými semienkami',
        'burger-3'                    => 'Domáci burger',
    ],
    'Kebab, grill a šaláty' => [
        'kebab-tanier'                  => 'Kebab tanier',
        'kebab-v-pizza-chlebe'          => 'Kebab v pizza chlebe',
        'tortilla-a-stripsy'            => 'Tortilla a kuracie stripsy',
        'kuracie-stripsy-box'           => 'Kuracie stripsy s hranolkami',
        'kuraci-gril'                   => 'Kurací gril',
        'bufala-salat'                  => 'Bufala šalát',
        'kuraci-salat-granatove-jablko' => 'Kurací šalát s granátovým jablkom',
        'salaty-v-miske'                => 'Šaláty',
        'bruschetta'                    => 'Bruschetta',
        'specialita-cheddar-bbq'        => 'S cheddarom a BBQ omáčkou',
        'sladke-pizzove-vankusiky'      => 'Sladké pizzové vankúšiky',
    ],
    'Párty misy na objednávku' => [
        'party-misa-1'            => 'Párty misa',
        'party-misa-2'            => 'Párty misa',
        'party-misa-3'            => 'Mix Denko',
        'party-misa-4'            => 'Párty misa',
        'kuracie-stripsy-etazer'  => 'Kuracie stripsy na etažére',
    ],
    'Naše suroviny' => [
        'talianska-muka'      => 'Talianska múka na neapolskú pizzu',
        'talianske-suroviny'  => 'Talianske suroviny',
    ],
    'Prevádzka' => [
        'prevadzka-interier' => 'Interiér pizzerie',
        'terasa'             => 'Terasa',
        'prevadzka-1'        => 'Interiér',
        'prevadzka-5'        => 'Interiér',
        'prevadzka-2'        => 'Vstup',
        'prevadzka-3'        => 'Sedenie',
        'prevadzka-4'        => 'Interiér',
        'prevadzka-vianoce'  => 'Vianoce v pizzerii',
        'kuchyna'            => 'Kuchyňa',
        'rozvoz-auto'        => 'Naše rozvozové auto',
    ],
    'Akcie a oslavy' => [
        'nas-tim'                  => 'Náš tím',
        'detsky-den-1'             => 'Detský deň',
        'detsky-den-2'             => 'Detský deň',
        'detsky-den-3'             => 'Detský deň',
        'detsky-den-cukrova-vata'  => 'Cukrová vata',
        'detsky-den-4'             => 'Detský deň',
        'detsky-den-5'             => 'Detský deň',
        'oslava-1'                 => 'Oslava v pizzerii',
        'oslava-2'                 => 'Oslava v pizzerii',
        'mikulas-v-pizzerii'       => 'Mikuláš v pizzerii',
    ],
];

$lightboxPhotos = [];
?>

<section class="page-hero page-hero--gallery">
  <div class="container">
    <p class="eyebrow"><?= h('Galéria') ?></p>
    <h1><?= h('Pozrite sa k nám') ?></h1>
    <p class="page-hero__lead"><?= h('Pizza z pece na drevo, burgery, párty misy, naša prevádzka aj akcie, ktoré u nás organizujeme. Kliknutím na fotku si ju zobrazíte na celú obrazovku.') ?></p>
  </div>
</section>

<section class="section gallery-section">
  <div class="container">

    <div class="gallery-block">
      <h2 class="gallery-block__title"><?= h('Videá') ?></h2>
      <div class="gallery-videos">
        <figure>
          <video class="story-video" src="/assets/video/priprava-cesta.mp4" poster="/assets/video/priprava-cesta.jpg" controls muted loop playsinline preload="none"></video>
          <figcaption><?= h('Takto pripravujeme cesto - fermentované 48 hodín') ?></figcaption>
        </figure>
        <figure>
          <video class="story-video" src="/assets/video/pec-na-drevo.mp4" poster="/assets/video/pec-na-drevo.jpg" controls muted loop playsinline preload="none"></video>
          <figcaption><?= h('Neapolská pizza v peci na drevo') ?></figcaption>
        </figure>
      </div>
    </div>

    <?php foreach ($gallerySections as $sectionTitle => $photos): ?>
    <div class="gallery-block">
      <h2 class="gallery-block__title"><?= h($sectionTitle) ?></h2>
      <div class="gallery-grid">
        <?php foreach ($photos as $name => $label): ?>
        <?= galleryFigure($name, $label, $lightboxPhotos) ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= h('Chuť je ešte lepšia ako fotky') ?></h2>
      <p><?= h('Presvedčte sa sami - zavolajte nám a objednajte si.') ?></p>
    </div>
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
  </div>
</section>

<?php require __DIR__ . '/includes/lightbox.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
