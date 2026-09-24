<?php
$pageTitle = 'Oslavy a firemné akcie';
$pageDescription = 'Oslava narodenín, rodinná oslava, firemná akcia či detská oslava v Pizzeria Tominno v Novosade - neapolská pizza z pece na drevo a párty misy na objednávku.';
$bodyClass = 'page-events';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/forms.php';

$partyItem = getMenu()['naobjednavku']['items'][0];

$eventPhotos = [
    'oslava-1'                => 'Oslava v pizzerii',
    'party-misa-1'            => 'Párty misa',
    'oslava-2'                => 'Oslava v pizzerii',
    'party-misa-4'            => 'Párty misa',
    'detsky-den-3'            => 'Detský deň',
    'kuracie-stripsy-etazer'  => 'Kuracie stripsy na etažére',
    'prevadzka-interier'      => 'Interiér pizzerie',
    'detsky-den-cukrova-vata' => 'Cukrová vata na detskom dni',
    'party-misa-2'            => 'Párty misa',
    'terasa'                  => 'Terasa',
    'mini-burgre'             => 'Mini burgery',
    'mikulas-v-pizzerii'      => 'Mikuláš v pizzerii',
];

$lightboxPhotos = [];
?>

<section class="page-hero page-hero--events">
  <div class="container">
    <p class="eyebrow"><?= h('Oslavy a akcie') ?></p>
    <h1><?= h('Oslávte to u nás') ?></h1>
    <p class="page-hero__lead"><?= h('Narodeniny, rodinné oslavy, firemné posedenia či detské oslavy - s neapolskou pizzou z pece na drevo a párty misami na objednávku.') ?></p>
    <div class="page-hero__actions">
      <a href="#formular" class="btn btn--primary btn--lg"><?= h('Nezáväzný dopyt') ?></a>
      <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--ghost btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
    </div>
  </div>
</section>

<section class="section about-story">
  <div class="container about-story__grid">
    <div class="about-story__img" style="background-image:url('<?= e(photo('party-misa-3')) ?>')"></div>
    <div class="about-story__text">
      <p class="eyebrow"><?= h('Pre vás a vašich hostí') ?></p>
      <h2><?= h('Postaráme sa o jedlo, vy o dobrú náladu') ?></h2>
      <p><?= h('Chystáte oslavu narodenín, stretnutie rodiny alebo posedenie s kolegami? Príďte k nám do Novosadu - posedieť si môžete v interiéri aj na terase.') ?></p>
      <p><?= h('Pripravíme neapolskú pizzu z pece na drevo, burgery, kebab aj bohaté párty misy. Ponuku jedál, termín a počet hostí si dohodneme vopred, aby bolo všetko pripravené presne podľa vašich predstáv.') ?></p>
      <a href="#formular" class="btn btn--outline"><?= h('Poslať dopyt') ?></a>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Čo u nás oslávite') ?></p>
      <h2><?= h('Akcie, ktoré radi pripravíme') ?></h2>
    </div>
    <div class="usp__grid">
      <div class="usp__item">
        <span class="usp__icon"><?= icon('heart') ?></span>
        <h3><?= h('Narodeniny a rodinné oslavy') ?></h3>
        <p><?= h('Narodeniny, meniny, výročia či stretnutie celej rodiny pri dobrom jedle.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('cup') ?></span>
        <h3><?= h('Firemné akcie') ?></h3>
        <p><?= h('Posedenie s kolegami, firemný večierok alebo obed pre partnerov.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('cake') ?></span>
        <h3><?= h('Detské oslavy') ?></h3>
        <p><?= h('Pizza a mini burgery, ktoré deťom chutia. Organizujeme aj detské dni.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('fries') ?></span>
        <h3><?= h('Párty misy') ?></h3>
        <p><?= h('Bohaté misy na objednávku - k nám na oslavu aj so sebou.') ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section party-offer">
  <div class="container party-offer__grid">
    <div>
      <p class="eyebrow"><?= h('Na objednávku') ?></p>
      <h2><?= h('Párty misa %s', e(t($partyItem['name']))) ?></h2>
      <p class="party-offer__price"><?= formatPrice($partyItem['price']) ?> <small>/ <?= h('osoba') ?></small></p>
      <p><?= e(tList($partyItem['desc'])) ?>.</p>
      <p class="note"><?= h('Párty misy pripravujeme na objednávku - dajte nám prosím vedieť vopred. Celé menu nájdete v %s.', '<a href="' . e(url('/menu')) . '#cat-naobjednavku">' . h('jedálnom lístku') . '</a>') ?></p>
    </div>
    <div class="party-offer__photos">
      <img src="<?= e(photo('party-misa-4', true)) ?>" alt="<?= h('Párty misa Pizzeria Tominno') ?>" loading="lazy">
      <img src="<?= e(photo('party-misa-1', true)) ?>" alt="<?= h('Párty misa s kuracími stripsami a hranolkami') ?>" loading="lazy">
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Fotogaléria') ?></p>
      <h2><?= h('Takto to u nás vyzerá') ?></h2>
    </div>
    <div class="gallery-grid">
      <?php foreach ($eventPhotos as $name => $label): ?>
      <?= galleryFigure($name, $label, $lightboxPhotos) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section event-inquiry" id="formular">
  <div class="container contact-grid">

    <div class="event-inquiry__info">
      <p class="eyebrow"><?= h('Nezáväzný dopyt') ?></p>
      <h2><?= h('Napíšte nám, čo plánujete') ?></h2>
      <p><?= h('Vyplňte krátky formulár a ozveme sa vám, aby sme doladili podrobnosti. Dopyt je nezáväzný.') ?></p>
      <ul class="check-list">
        <li><?= h('Pošlite nám dopyt alebo zavolajte') ?></li>
        <li><?= h('Ozveme sa vám a dohodneme termín, počet hostí a jedlá') ?></li>
        <li><?= h('Všetko pripravíme - vy si užívate oslavu') ?></li>
      </ul>
      <div class="event-inquiry__call">
        <span><?= icon('phone') ?></span>
        <div>
          <strong><?= h('Radšej telefonicky?') ?></strong>
          <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a>
          <small><?= e(openingHoursSummary()) ?></small>
        </div>
      </div>
    </div>

    <div class="contact-form-wrap">
      <h2><?= h('Dopyt na oslavu / akciu') ?></h2>

      <?= formAlert('Vyplňte prosím meno, platné telefónne číslo a typ akcie.') ?>

      <form action="/process_contact.php" method="post" class="contact-form">
        <?= antiSpamFields('akcie') ?>
        <div class="form-grid">
          <div class="form-row form-row--full">
            <label for="event_type"><?= h('Typ akcie *') ?></label>
            <select id="event_type" name="event_type" required>
              <option value=""><?= h('Vyberte…') ?></option>
              <?php foreach (eventTypes() as $value => $label): ?>
              <option value="<?= e($value) ?>"><?= h($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-row">
            <label for="date"><?= h('Predbežný termín') ?></label>
            <input type="date" id="date" name="date" min="<?= e(date('Y-m-d')) ?>">
          </div>
          <div class="form-row">
            <label for="guests"><?= h('Počet osôb') ?></label>
            <input type="number" id="guests" name="guests" min="1" max="500" inputmode="numeric">
          </div>
          <div class="form-row">
            <label for="name"><?= h('Meno a priezvisko *') ?></label>
            <input type="text" id="name" name="name" required maxlength="100" autocomplete="name">
          </div>
          <div class="form-row">
            <label for="phone"><?= h('Telefón *') ?></label>
            <input type="tel" id="phone" name="phone" required maxlength="30" autocomplete="tel" placeholder="09XX XXX XXX">
          </div>
          <div class="form-row form-row--full">
            <label for="email"><?= h('E-mail (nepovinné)') ?></label>
            <input type="email" id="email" name="email" maxlength="150" autocomplete="email">
          </div>
          <div class="form-row form-row--full">
            <label for="message"><?= h('Poznámka') ?></label>
            <textarea id="message" name="message" rows="4" maxlength="3000" placeholder="<?= h('Napr. čas začiatku, jedlá, ktoré by ste chceli, alergie...') ?>"></textarea>
          </div>
        </div>
        <p class="note"><?= h('Údaje z formulára použijeme iba na vybavenie vášho dopytu. Viac v časti %s.', '<a href="' . e(url('/ochrana-osobnych-udajov')) . '">' . h('Ochrana osobných údajov') . '</a>') ?></p>
        <button type="submit" class="btn btn--primary btn--lg"><?= h('Odoslať dopyt') ?></button>
      </form>
    </div>

  </div>
</section>

<?php require __DIR__ . '/includes/lightbox.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
