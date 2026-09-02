<?php
$pageTitle = 'Kontakt';
$pageDescription = 'Kontaktujte Pizzeria Tominno - Hlavná 26, 076 02 Novosad. Telefón 0910 777 336.';
$bodyClass = 'page-contact';
require_once __DIR__ . '/includes/header.php';

$sent = isset($_GET['sent']) && $_GET['sent'] === '1';
$mapsSearchUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode(SITE_NAME . ', ' . SITE_ADDRESS_FULL);
?>

<section class="page-hero page-hero--contact">
  <div class="container">
    <p class="eyebrow">Kontakt</p>
    <h1>Sme tu pre vás</h1>
    <p class="page-hero__lead">Máte otázku k objednávke alebo chcete niečo doriešiť telefonicky? Ozvite sa nám.</p>
  </div>
</section>

<section class="section contact-section">
  <div class="container contact-grid">

    <div class="contact-info">
      <div class="contact-info__item">
        <span><?= icon('pin') ?></span>
        <div>
          <h3>Adresa</h3>
          <p><?= e(SITE_ADDRESS_STREET) ?><br><?= e(SITE_ADDRESS_CITY) ?></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('phone') ?></span>
        <div>
          <h3>Telefón</h3>
          <p><a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('mail') ?></span>
        <div>
          <h3>E-mail</h3>
          <p><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
        </div>
      </div>
      <div class="contact-info__item">
        <span><?= icon('clock') ?></span>
        <div>
          <h3>Otváracie hodiny</h3>
          <p>Denne 10:00 – 22:00</p>
        </div>
      </div>

      <div class="contact-map">
        <iframe
          title="Mapa - Pizzeria Tominno"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5460.324055378588!2d21.740868100000004!3d48.5250816!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4738d500190a7d89%3A0xd9fb219463137a79!2sPizzeriaTominno!5e1!3m2!1ssk!2ssk!4v1787941230517!5m2!1ssk!2ssk"
          style="border:0;"
          allowfullscreen
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin">
        </iframe>
        <a class="map-link" href="<?= e($mapsSearchUrl) ?>" target="_blank" rel="noopener">Otvoriť veľkú mapu <?= icon('external-link', 'icon icon--sm') ?></a>
      </div>
    </div>

    <div class="contact-form-wrap">
      <h2>Napíšte nám</h2>
      <p class="contact-form-note">Toto je všeobecný kontaktný formulár. Na objednávanie jedla použite <a href="/objednavka.php">stránku objednávky</a>.</p>

      <?php if ($sent): ?>
      <div class="alert alert--success">Ďakujeme, vaša správa bola odoslaná. Ozveme sa vám čo najskôr.</div>
      <?php endif; ?>

      <form action="/process_contact.php" method="post" class="contact-form" novalidate>
        <div class="form-row">
          <label for="name">Meno a priezvisko</label>
          <input type="text" id="name" name="name" required autocomplete="name">
        </div>
        <div class="form-row">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required autocomplete="email">
        </div>
        <div class="form-row">
          <label for="phone">Telefón (nepovinné)</label>
          <input type="tel" id="phone" name="phone" autocomplete="tel">
        </div>
        <div class="form-row">
          <label for="message">Správa</label>
          <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
        <button type="submit" class="btn btn--primary btn--lg">Odoslať správu</button>
      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
