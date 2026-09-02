/**
 * Lightbox pre galériu (galeria.php) - kliknutím na fotku sa otvorí na celú
 * obrazovku, ďalej sa dá prechádzať šípkami, klávesnicou aj swipe gestom.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var photos = window.PT_GALLERY;
    var lightbox = document.getElementById('lightbox');
    if (!photos || !photos.length || !lightbox) return;

    var imgEl = document.getElementById('lightboxImage');
    var captionEl = document.getElementById('lightboxCaption');
    var counterEl = document.getElementById('lightboxCounter');
    var prevBtn = document.getElementById('lightboxPrev');
    var nextBtn = document.getElementById('lightboxNext');

    var currentIndex = 0;
    var lastFocusedEl = null;
    var touchStartX = null;

    function render() {
      var photo = photos[currentIndex];
      imgEl.src = photo.full;
      imgEl.alt = photo.label;
      captionEl.textContent = photo.label;
      counterEl.textContent = (currentIndex + 1) + ' / ' + photos.length;
    }

    function open(index) {
      currentIndex = index;
      lastFocusedEl = document.activeElement;
      render();
      lightbox.hidden = false;
      document.body.classList.add('modal-open');
      lightbox.querySelector('.lightbox__close').focus();
    }

    function close() {
      lightbox.hidden = true;
      document.body.classList.remove('modal-open');
      if (lastFocusedEl) lastFocusedEl.focus();
    }

    function showPrev() {
      currentIndex = (currentIndex - 1 + photos.length) % photos.length;
      render();
    }

    function showNext() {
      currentIndex = (currentIndex + 1) % photos.length;
      render();
    }

    document.addEventListener('click', function (e) {
      var trigger = e.target.closest('[data-gallery-open]');
      if (trigger) {
        e.preventDefault();
        open(parseInt(trigger.getAttribute('data-index'), 10) || 0);
        return;
      }
      if (e.target.closest('[data-lightbox-close]')) {
        close();
      }
    });

    prevBtn.addEventListener('click', showPrev);
    nextBtn.addEventListener('click', showNext);

    document.addEventListener('keydown', function (e) {
      if (lightbox.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') showPrev();
      if (e.key === 'ArrowRight') showNext();
    });

    // Swipe gestá na dotykových zariadeniach
    lightbox.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches[0].clientX;
    }, { passive: true });

    lightbox.addEventListener('touchend', function (e) {
      if (touchStartX === null) return;
      var delta = e.changedTouches[0].clientX - touchStartX;
      if (Math.abs(delta) > 40) {
        if (delta > 0) showPrev(); else showNext();
      }
      touchStartX = null;
    }, { passive: true });
  });
})();
