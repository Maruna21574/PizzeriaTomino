/**
 * Cookie lišta - súhlas s cookies a načítanie externého obsahu (Mapy Google).
 * HTML lišty je v includes/cookies.php. Výber sa ukladá do cookie "pt_consent"
 * na 12 mesiacov: {"v":1,"external":true|false}. Pri zmene štruktúry súhlasu
 * zvýšte VERSION - návštevníkov sa lišta opýta znova.
 */
(function () {
  'use strict';

  var COOKIE = 'pt_consent';
  var VERSION = 1;
  var CATEGORIES = ['external'];   // voliteľné kategórie (nevyhnutné sú vždy zapnuté)

  function readConsent() {
    var match = document.cookie.match(new RegExp('(?:^|; )' + COOKIE + '=([^;]*)'));
    if (!match) {
      return null;
    }
    try {
      var data = JSON.parse(decodeURIComponent(match[1]));
      return data && data.v === VERSION ? data : null;
    } catch (e) {
      return null;
    }
  }

  function writeConsent(choice) {
    var data = { v: VERSION };
    CATEGORIES.forEach(function (c) { data[c] = !!choice[c]; });
    document.cookie = COOKIE + '=' + encodeURIComponent(JSON.stringify(data)) +
      '; max-age=' + 365 * 24 * 3600 + '; path=/; SameSite=Lax' +
      (location.protocol === 'https:' ? '; Secure' : '');
    return data;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var banner = document.getElementById('cookieBanner');
    var modal = document.getElementById('cookieModal');
    var revisit = document.getElementById('cookieRevisit');
    if (!banner || !modal || !revisit) {
      return;
    }
    var lastFocus = null;

    /* ---- externý obsah: iframe sa vloží až po súhlasu ---- */
    function applyConsent(consent) {
      document.querySelectorAll('[data-consent-embed]').forEach(function (box) {
        if (!consent || !consent[box.getAttribute('data-consent-embed')] || box.querySelector('iframe')) {
          return;
        }
        var frame = document.createElement('iframe');
        frame.src = box.getAttribute('data-src');
        frame.title = box.getAttribute('data-title') || '';
        frame.loading = 'lazy';
        frame.allowFullscreen = true;
        frame.referrerPolicy = 'strict-origin-when-cross-origin';
        box.classList.add('consent-embed--loaded');
        box.appendChild(frame);
      });
    }

    function save(choice) {
      var before = readConsent();
      var consent = writeConsent(choice);
      // Odvolaný súhlas - stránka sa načíta znova, aby sa externý obsah odstránil.
      var revoked = before && CATEGORIES.some(function (c) { return before[c] && !consent[c]; });
      hide(banner);
      closeModal();
      revisit.hidden = false;
      if (revoked) {
        location.reload();
        return;
      }
      applyConsent(consent);
    }

    function setAll(value) {
      var choice = {};
      CATEGORIES.forEach(function (c) { choice[c] = value; });
      save(choice);
    }

    /* ---- zobrazenie banneru a okna nastavení ---- */
    function show(el) { el.hidden = false; requestAnimationFrame(function () { el.classList.add('is-visible'); }); }
    function hide(el) { el.classList.remove('is-visible'); el.hidden = true; }

    function openModal() {
      var consent = readConsent();
      modal.querySelectorAll('[data-cookie-category]').forEach(function (box) {
        box.checked = !!(consent && consent[box.getAttribute('data-cookie-category')]);
      });
      lastFocus = document.activeElement;
      hide(banner);
      show(modal);
      document.body.classList.add('cookie-modal-open');
      var first = modal.querySelector('.cookie-modal__close');
      if (first) { first.focus(); }
    }

    function closeModal() {
      if (modal.hidden) { return; }
      hide(modal);
      document.body.classList.remove('cookie-modal-open');
      if (!readConsent()) {
        show(banner);          // bez uloženého výberu sa vrátime k banneru
      } else if (lastFocus && lastFocus.focus) {
        lastFocus.focus();
      }
    }

    document.addEventListener('click', function (event) {
      var target = event.target.closest('button, [data-cookie-close]');
      if (!target) { return; }
      if (target.hasAttribute('data-cookie-accept')) { setAll(true); }
      else if (target.hasAttribute('data-cookie-reject')) { setAll(false); }
      else if (target.hasAttribute('data-cookie-customize') || target.hasAttribute('data-cookie-settings')) { openModal(); }
      else if (target.hasAttribute('data-cookie-close')) { closeModal(); }
      else if (target.hasAttribute('data-cookie-save')) {
        var choice = {};
        modal.querySelectorAll('[data-cookie-category]').forEach(function (box) {
          choice[box.getAttribute('data-cookie-category')] = box.checked;
        });
        save(choice);
      } else if (target.hasAttribute('data-consent-allow')) {
        // Tlačidlo priamo pri mape: povolí iba túto kategóriu, ostatné nechá bez zmeny.
        var current = readConsent() || {};
        var next = {};
        CATEGORIES.forEach(function (c) { next[c] = !!current[c]; });
        next[target.getAttribute('data-consent-allow')] = true;
        save(next);
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modal.hidden) {
        closeModal();
      }
    });

    /* ---- štart ---- */
    var consent = readConsent();
    if (consent) {
      applyConsent(consent);
      revisit.hidden = false;
    } else {
      show(banner);
    }
  });
})();
