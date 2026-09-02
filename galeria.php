<?php
$pageTitle = 'Galéria';
$pageDescription = 'Pozrite si fotografie našich pizz, cestovín a interiéru Pizzeria Tominno v Novosade.';
$bodyClass = 'page-gallery';
require_once __DIR__ . '/includes/header.php';

$gallery = [
    ['img' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=900&q=80', 'label' => 'Pizza z pece na drevo'],
    ['img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=900&q=80', 'label' => 'Prosciutto e Funghi'],
    ['img' => 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?auto=format&fit=crop&w=900&q=80', 'label' => 'Diavola'],
    ['img' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=900&q=80', 'label' => 'Cestoviny Bolognese'],
    ['img' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=900&q=80', 'label' => 'Tiramisu'],
    ['img' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80', 'label' => 'Šalát s kuracím mäsom'],
    ['img' => 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?auto=format&fit=crop&w=900&q=80', 'label' => 'Príprava cesta'],
    ['img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=900&q=80', 'label' => 'Pizza v peci'],
    ['img' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=900&q=80', 'label' => 'Quattro Stagioni'],
    ['img' => 'https://images.unsplash.com/photo-1601924582970-9238bcb495d9?auto=format&fit=crop&w=900&q=80', 'label' => 'Parmská pizza'],
    ['img' => 'https://images.unsplash.com/photo-1595295333158-4742f28fbd85?auto=format&fit=crop&w=900&q=80', 'label' => 'Naša prevádzka'],
];
?>

<section class="page-hero page-hero--gallery">
  <div class="container">
    <p class="eyebrow">Galéria</p>
    <h1>Pozrite si, čo pripravujeme</h1>
    <p class="page-hero__lead">Výber fotografií z našej kuchyne a ponuky. Kliknutím na fotku si ju zobrazíte na celú obrazovku. Skutočné fotky prevádzky čoskoro doplníme.</p>
  </div>
</section>

<section class="section gallery-section">
  <div class="container">
    <div class="gallery-grid">
      <?php foreach ($gallery as $i => $photo): ?>
      <figure class="gallery-item">
        <button type="button" class="gallery-item__btn" data-gallery-open data-index="<?= (int) $i ?>" aria-label="Zväčšiť: <?= e($photo['label']) ?>">
          <img src="<?= e($photo['img']) ?>" alt="<?= e($photo['label']) ?> - Pizzeria Tominno" loading="lazy">
          <span class="gallery-item__zoom"><?= icon('zoom-in') ?></span>
        </button>
        <figcaption><?= e($photo['label']) ?></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Chuť je ešte lepšia ako fotky</h2>
      <p>Presvedčte sa sami - objednajte si u nás ešte dnes.</p>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--lg">Objednať teraz</a>
  </div>
</section>

<div class="lightbox" id="lightbox" hidden>
  <div class="lightbox__backdrop" data-lightbox-close></div>

  <button type="button" class="lightbox__close" data-lightbox-close aria-label="Zavrieť"><?= icon('x') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--prev" id="lightboxPrev" aria-label="Predchádzajúca fotka"><?= icon('chevron-left') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--next" id="lightboxNext" aria-label="Ďalšia fotka"><?= icon('chevron-right') ?></button>

  <figure class="lightbox__figure">
    <img src="" alt="" id="lightboxImage">
    <figcaption id="lightboxCaption"></figcaption>
  </figure>

  <p class="lightbox__counter" id="lightboxCounter"></p>
</div>

<script>
  window.PT_GALLERY = <?= json_encode(array_map(function ($photo) {
      return [
          'full' => str_replace('w=900', 'w=1800', $photo['img']),
          'label' => $photo['label'],
      ];
  }, $gallery), JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="/assets/js/gallery.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
