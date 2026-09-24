<?php
/**
 * Lightbox - zväčšenie fotky na celú obrazovku (assets/js/gallery.js).
 * Očakáva $lightboxPhotos - zoznam ['full' => url, 'label' => popis],
 * ktorý naplní galleryFigure() pri vykresľovaní fotiek na stránke.
 */
?>
<div class="lightbox" id="lightbox" hidden>
  <div class="lightbox__backdrop" data-lightbox-close></div>

  <button type="button" class="lightbox__close" data-lightbox-close aria-label="<?= h('Zavrieť') ?>"><?= icon('x') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--prev" id="lightboxPrev" aria-label="<?= h('Predchádzajúca fotka') ?>"><?= icon('chevron-left') ?></button>
  <button type="button" class="lightbox__nav lightbox__nav--next" id="lightboxNext" aria-label="<?= h('Ďalšia fotka') ?>"><?= icon('chevron-right') ?></button>

  <figure class="lightbox__figure">
    <img src="" alt="" id="lightboxImage">
    <figcaption id="lightboxCaption"></figcaption>
  </figure>

  <p class="lightbox__counter" id="lightboxCounter"></p>
</div>

<script>
  window.PT_GALLERY = <?= json_encode($lightboxPhotos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="/assets/js/gallery.js?v=<?= filemtime(__DIR__ . '/../assets/js/gallery.js') ?>"></script>
