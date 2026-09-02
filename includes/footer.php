<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
?>
<footer class="site-footer">
  <div class="container footer-grid">

    <div class="footer-col">
      <a href="/index.php" class="logo logo--footer">
        <img src="/assets/img/logo_tomino_w.png" alt="<?= e(SITE_NAME) ?>" class="logo__img">
      </a>
      <p><?= e(SITE_CLAIM) ?></p>
      <?php if (SOCIAL_FACEBOOK || SOCIAL_INSTAGRAM): ?>
      <div class="footer-social">
        <?php if (SOCIAL_FACEBOOK): ?><a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
        <?php if (SOCIAL_INSTAGRAM): ?><a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h3>Navigácia</h3>
      <ul class="footer-links">
        <li><a href="/index.php">Domov</a></li>
        <li><a href="/menu.php">Jedálny lístok</a></li>
        <li><a href="/o-nas.php">O nás</a></li>
        <li><a href="/galeria.php">Galéria</a></li>
        <li><a href="/doprava-a-platba.php">Doprava a platba</a></li>
        <li><a href="/objednavka.php">Objednať online</a></li>
        <li><a href="/kontakt.php">Kontakt</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h3>Kontakt</h3>
      <ul class="footer-contact">
        <li><?= icon('pin', 'icon icon--sm') ?> <?= e(SITE_ADDRESS_FULL) ?></li>
        <li><?= icon('phone', 'icon icon--sm') ?> <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a></li>
        <li><?= icon('mail', 'icon icon--sm') ?> <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></li>
        <li><?= icon('clock', 'icon icon--sm') ?> Po – Ne 10:00 – 22:00</li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom__inner">
      <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. Všetky práva vyhradené.</p>
      <p>Rozvoz: <?= e(DELIVERY_AREA) ?> · Platba len na dobierku (hotovosť / karta kuriérovi)</p>
    </div>
  </div>
</footer>

<a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="call-fab" aria-label="Zavolať pizzeriu"><?= icon('phone') ?></a>

<div class="mini-cart" id="miniCart" hidden>
  <div class="container mini-cart__inner">
    <div class="mini-cart__info">
      <span class="mini-cart__icon"><?= icon('cart') ?></span>
      <span class="mini-cart__text">
        <strong id="miniCartCount">0</strong> <span id="miniCartLabel">položiek</span>
        <span class="mini-cart__dot">·</span>
        <strong id="miniCartTotal">0,00 €</strong>
      </span>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--sm mini-cart__cta">Dokončiť objednávku</a>
  </div>
</div>

<div class="modal" id="allergenModal" hidden>
  <div class="modal__backdrop" data-modal-close></div>
  <div class="modal__panel" role="dialog" aria-modal="true" aria-labelledby="allergenModalTitle">
    <button type="button" class="modal__close" data-modal-close aria-label="Zavrieť"><?= icon('x') ?></button>
    <div class="modal__header">
      <span class="modal__icon"><?= icon('info') ?></span>
      <div>
        <p class="modal__eyebrow">Alergény</p>
        <h3 id="allergenModalTitle"></h3>
      </div>
    </div>
    <ul class="modal__list" id="allergenModalList"></ul>
    <p class="modal__note">Zloženie a alergény sa môžu meniť podľa aktuálnej dostupnosti surovín. V prípade alergie alebo neznášanlivosti nás prosím kontaktujte na <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a> pred objednaním.</p>
  </div>
</div>

<script src="/assets/js/main.js"></script>
<script src="/assets/js/cart.js"></script>
</body>
</html>
