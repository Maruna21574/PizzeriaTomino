<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
?>
<footer class="site-footer">
  <div class="container footer-grid">

    <div class="footer-col">
      <a href="<?= e(url('/')) ?>" class="logo logo--footer">
        <img src="/assets/img/logo_tomino_w.png" alt="<?= e(SITE_NAME) ?>" class="logo__img">
      </a>
      <p><?= h(SITE_CLAIM) ?></p>
      <?php if (SOCIAL_FACEBOOK || SOCIAL_INSTAGRAM): ?>
      <div class="footer-social">
        <?php if (SOCIAL_FACEBOOK): ?><a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
        <?php if (SOCIAL_INSTAGRAM): ?><a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h3><?= h('Navigácia') ?></h3>
      <ul class="footer-links">
        <li><a href="<?= e(url('/')) ?>"><?= h('Domov') ?></a></li>
        <li><a href="<?= e(url('/menu')) ?>"><?= h('Jedálny lístok') ?></a></li>
        <li><a href="<?= e(url('/o-nas')) ?>"><?= h('O nás') ?></a></li>
        <li><a href="<?= e(url('/galeria')) ?>"><?= h('Galéria') ?></a></li>
        <li><a href="<?= e(url('/oslavy-a-akcie')) ?>"><?= h('Oslavy a firemné akcie') ?></a></li>
        <li><a href="<?= e(url('/doprava-a-platba')) ?>"><?= h('Rozvoz a platba') ?></a></li>
        <li><a href="<?= e(url('/kontakt')) ?>"><?= h('Kontakt') ?></a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h3><?= h('Kontakt') ?></h3>
      <ul class="footer-contact">
        <li><?= icon('pin', 'icon icon--sm') ?> <?= e(SITE_ADDRESS_FULL) ?></li>
        <li><?= icon('phone', 'icon icon--sm') ?> <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a></li>
        <li><?= icon('mail', 'icon icon--sm') ?> <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></li>
        <li><?= icon('clock', 'icon icon--sm') ?> <?= e(openingHoursSummary()) ?></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom__inner">
      <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. <?= h('Všetky práva vyhradené.') ?> · <a href="<?= e(url('/ochrana-osobnych-udajov')) ?>"><?= h('Ochrana osobných údajov') ?></a></p>
      <p><?= h('Rozvoz: %s · Objednávky telefonicky · Platba hotovosť / karta', e(deliveryArea())) ?></p>
    </div>
  </div>
</footer>

<a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="call-fab" aria-label="<?= h('Zavolať pizzeriu') ?>"><?= icon('phone') ?></a>

<script src="/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
</body>
</html>
