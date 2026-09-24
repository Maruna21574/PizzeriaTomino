<?php
$pageTitle = 'Galéria';
$pageDescription = 'Fotky a videá z Pizzeria Tominno v Novosade - neapolská pizza z pece na drevo, burgery, kebab, párty misy, naša prevádzka a akcie.';
$bodyClass = 'page-gallery';
require_once __DIR__ . '/includes/header.php';

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

    <?php foreach (getGallery() as $section): ?>
    <div class="gallery-block">
      <h2 class="gallery-block__title"><?= e(tf($section, 'title')) ?></h2>
      <div class="gallery-grid">
        <?php foreach ($section['photos'] as $photo): ?>
        <?= galleryFigure($photo, $lightboxPhotos) ?>
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
