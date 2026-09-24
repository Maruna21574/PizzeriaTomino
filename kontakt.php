<?php
$pageTitle = 'Kontakt';
$pageDescription = 'Kontaktujte Pizzeria Tominno - Hlavná 26, 076 02 Novosad. Telefón 0910 777 336.';
$bodyClass = 'page-contact';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/forms.php';

$mapsSearchUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode(SITE_NAME . ', ' . SITE_ADDRESS_FULL);
?>

<section class="page-hero page-hero--contact">
  <div class="container">
    <p class="eyebrow"><?= h('Kontakt') ?></p>
    <h1><?= h('Sme tu pre vás') ?></h1>
    <p class="page-hero__lead"><?= h('Objednávky prijímame telefonicky. S otázkami či objednávkou párty misy sa nám ozvite telefonicky alebo cez formulár.') ?></p>
  </div>
</section>

<section class="section contact-section">
  <div class="container contact-grid">

    <div class="contact-info">
      <div class="contact-info__item">
        <span><?= icon('pin') ?></span>
        <div>
          <h3><?= h('Adresa') ?></h3>
          <p><?= e(SITE_ADDRESS_STREET) ?><br><?= e(SITE_ADDRESS_CITY) ?></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('phone') ?></span>
        <div>
          <h3><?= h('Telefón') ?></h3>
          <p><a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('mail') ?></span>
        <div>
          <h3><?= h('E-mail') ?></h3>
          <p><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('clock') ?></span>
        <div>
          <h3><?= h('Otváracie hodiny') ?></h3>
          <p><?= e(openingHoursSummary()) ?></p>
        </div>
      </div>

      <div class="contact-map">
        <iframe
          title="<?= h('Mapa') ?> - Pizzeria Tominno"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5460.324055378588!2d21.740868100000004!3d48.5250816!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4738d500190a7d89%3A0xd9fb219463137a79!2sPizzeriaTominno!5e1!3m2!1ssk!2ssk!4v1787941230517!5m2!1ssk!2ssk"
          style="border:0;"
          allowfullscreen
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin">
        </iframe>
        <a class="map-link" href="<?= e($mapsSearchUrl) ?>" target="_blank" rel="noopener"><?= h('Otvoriť veľkú mapu') ?> <?= icon('external-link', 'icon icon--sm') ?></a>
      </div>
    </div>

    <div class="contact-form-wrap" id="formular">
      <h2><?= h('Napíšte nám') ?></h2>
      <p class="contact-form-note"><?= h('Objednávky jedla s rozvozom prijímame iba telefonicky na %s. Formulár slúži na otázky a správy.', '<a href="tel:' . e(SITE_PHONE_TEL) . '">' . e(SITE_PHONE) . '</a>') ?></p>

      <?= formAlert('Vyplňte prosím meno, platný e-mail a text správy.') ?>

      <form action="/process_contact.php" method="post" class="contact-form">
        <?= antiSpamFields('kontakt') ?>
        <div class="form-row">
          <label for="name"><?= h('Meno a priezvisko') ?></label>
          <input type="text" id="name" name="name" required maxlength="100" autocomplete="name">
        </div>
        <div class="form-row">
          <label for="email"><?= h('E-mail') ?></label>
          <input type="email" id="email" name="email" required maxlength="150" autocomplete="email">
        </div>
        <div class="form-row">
          <label for="phone"><?= h('Telefón (nepovinné)') ?></label>
          <input type="tel" id="phone" name="phone" maxlength="30" autocomplete="tel">
        </div>
        <div class="form-row">
          <label for="message"><?= h('Správa') ?></label>
          <textarea id="message" name="message" rows="5" required maxlength="3000"></textarea>
        </div>
        <p class="note"><?= h('Údaje z formulára použijeme iba na odpoveď na vašu správu. Viac v časti %s.', '<a href="' . e(url('/ochrana-osobnych-udajov')) . '">' . h('Ochrana osobných údajov') . '</a>') ?></p>
        <button type="submit" class="btn btn--primary btn--lg"><?= h('Odoslať správu') ?></button>
      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
