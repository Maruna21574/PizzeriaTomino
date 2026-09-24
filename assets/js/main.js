/**
 * Všeobecné UI správanie: mobilná navigácia, sticky hlavička,
 * filtre kategórií na stránke menu.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    // Mobilná navigácia
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if (toggle && nav) {
      toggle.addEventListener('click', function () {
        var isOpen = nav.classList.toggle('main-nav--open');
        toggle.classList.toggle('nav-toggle--open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        document.body.classList.toggle('nav-open', isOpen);
      });

      nav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          nav.classList.remove('main-nav--open');
          toggle.classList.remove('nav-toggle--open');
          toggle.setAttribute('aria-expanded', 'false');
          document.body.classList.remove('nav-open');
        });
      });
    }

    // Dropdown "Viac" v hlavnej navigácii
    var moreToggle = document.getElementById('navMoreToggle');
    var moreDropdown = document.getElementById('navMoreDropdown');
    if (moreToggle && moreDropdown) {
      var closeMoreDropdown = function () {
        moreDropdown.classList.remove('nav-dropdown--open');
        moreToggle.setAttribute('aria-expanded', 'false');
      };

      moreToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = moreDropdown.classList.toggle('nav-dropdown--open');
        moreToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      document.addEventListener('click', function (e) {
        if (!moreDropdown.contains(e.target)) closeMoreDropdown();
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMoreDropdown();
      });
    }

    // Tieň hlavičky pri scrollovaní
    var header = document.querySelector('.site-header');
    if (header) {
      var onScroll = function () {
        header.classList.toggle('site-header--scrolled', window.scrollY > 12);
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    // Filtre kategórií na stránke menu.php - scroll na sekciu + scroll-spy
    var filterButtons = document.querySelectorAll('.menu-filter');
    var groups = document.querySelectorAll('.menu-group');
    if (filterButtons.length && groups.length) {

      filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var key = btn.getAttribute('data-filter');
          var target = document.getElementById('cat-' + key);
          if (target) {
            var offset = (header ? header.offsetHeight : 0) + document.querySelector('.topbar').offsetHeight + 16;
            var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top: top, behavior: 'smooth' });
          }
        });
      });

      var setActive = function (key) {
        filterButtons.forEach(function (b) {
          var active = b.getAttribute('data-filter') === key;
          b.classList.toggle('active', active);
          b.setAttribute('aria-selected', active ? 'true' : 'false');
          // Na mobile sú filtre v posúvateľnom riadku - aktívny držíme v zábere.
          if (active && b.parentNode.scrollWidth > b.parentNode.clientWidth) {
            b.parentNode.scrollTo({ left: b.offsetLeft - 20, behavior: 'smooth' });
          }
        });
      };

      if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              setActive(entry.target.getAttribute('data-group'));
            }
          });
        }, { rootMargin: '-40% 0px -50% 0px', threshold: 0 });

        groups.forEach(function (group) { observer.observe(group); });
      }
    }

  });
})();
