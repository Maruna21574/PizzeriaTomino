<?php
/**
 * Cookie lišta (súhlas s cookies) - banner, okno s nastaveniami a tlačidlo
 * na opätovné otvorenie. Logika je v assets/js/cookies.js, výber návštevníka
 * sa ukladá do cookie "pt_consent" na 12 mesiacov.
 *
 * Web sám nepoužíva sledovacie ani reklamné cookies. Súhlas sa týka:
 *  - nevyhnutných cookies (uloženie samotného výberu) - vždy zapnuté,
 *  - externého obsahu (Mapy Google na stránke Kontakt) - načíta sa až po súhlase.
 * Nový externý obsah sa pridáva cez consentEmbed() a novú kategóriu doplníte
 * do cookiePreferences() nižšie.
 */

/**
 * Vloženie externého obsahu (iframe) až po súhlase. Bez súhlasu (alebo bez
 * JavaScriptu) sa zobrazí výzva s tlačidlom; odkaz na pôvodný zdroj dáva stránka sama.
 */
function consentEmbed(string $category, string $src, string $title, string $notice): string
{
    return '<div class="consent-embed" data-consent-embed="' . e($category) . '" data-src="' . e($src) . '" data-title="' . e($title) . '">'
        . '<div class="consent-embed__inner">' . icon('info', 'icon icon--lg')
        . '<p>' . e($notice) . '</p>'
        . '<button type="button" class="btn btn--primary btn--sm" data-consent-allow="' . e($category) . '">' . h('Povoliť a zobraziť mapu') . '</button>'
        . '</div></div>';
}

/** Kategórie cookies v okne nastavení (povinné sú vždy zapnuté). */
function cookiePreferences(): array
{
    return [
        [
            'id' => 'necessary',
            'required' => true,
            'title' => 'Nevyhnutné',
            'text' => 'Tieto cookies sú potrebné na základné fungovanie webu. Ukladajú napríklad váš výber v tejto lište, aby sme sa vás nepýtali pri každej návšteve. Nedajú sa vypnúť a neidentifikujú vás.',
            'cookies' => [['pt_consent', '12 mesiacov', 'Uloženie vášho výberu cookies']],
        ],
        [
            'id' => 'external',
            'required' => false,
            'title' => 'Externý obsah (Mapy Google)',
            'text' => 'Mapa na stránke Kontakt sa načíta zo služby Google Maps. Google pritom môže spracovať vašu IP adresu a ukladať vlastné cookies. Bez súhlasu sa mapa nezobrazí - ostane odkaz na otvorenie v Mapách Google.',
            'cookies' => [['Google (tretia strana)', 'podľa Google', 'Zobrazenie vloženej mapy']],
        ],
    ];
}

/** Cookie banner + okno nastavení + tlačidlo na opätovné otvorenie. */
function cookieBanner(): string
{
    $privacy = url('/ochrana-osobnych-udajov');

    ob_start();
    ?>
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-labelledby="cookieBannerTitle" aria-describedby="cookieBannerText" hidden>
  <h2 class="cookie-banner__title" id="cookieBannerTitle"><?= h('Používame cookies') ?></h2>
  <p class="cookie-banner__text" id="cookieBannerText"><?= h('Na zobrazenie mapy používame službu tretej strany (Mapy Google), ktorá môže ukladať cookies. Sledovacie ani reklamné cookies nepoužívame. Môžete ich povoliť, odmietnuť alebo si výber prispôsobiť.') ?> <a href="<?= e($privacy) ?>"><?= h('Ochrana osobných údajov') ?></a></p>
  <div class="cookie-banner__actions">
    <button type="button" class="btn btn--outline btn--sm" data-cookie-customize><?= h('Prispôsobiť') ?></button>
    <button type="button" class="btn btn--outline btn--sm" data-cookie-reject><?= h('Odmietnuť všetko') ?></button>
    <button type="button" class="btn btn--primary btn--sm" data-cookie-accept><?= h('Prijať všetko') ?></button>
  </div>
</div>

<div class="cookie-modal" id="cookieModal" role="dialog" aria-modal="true" aria-labelledby="cookieModalTitle" hidden>
  <div class="cookie-modal__backdrop" data-cookie-close></div>
  <div class="cookie-modal__panel">
    <div class="cookie-modal__head">
      <h2 id="cookieModalTitle"><?= h('Nastavenia cookies') ?></h2>
      <button type="button" class="cookie-modal__close" data-cookie-close aria-label="<?= h('Zavrieť') ?>"><?= icon('x') ?></button>
    </div>
    <div class="cookie-modal__body">
      <p><?= h('Tu si môžete vybrať, ktoré cookies a služby tretích strán povolíte. Výber môžete kedykoľvek zmeniť cez tlačidlo s cookie v rohu stránky alebo odkaz v pätičke.') ?></p>
      <?php foreach (cookiePreferences() as $cat): ?>
      <div class="cookie-cat">
        <div class="cookie-cat__head">
          <h3><?= h($cat['title']) ?></h3>
          <?php if ($cat['required']): ?>
            <span class="cookie-cat__always"><?= h('Vždy aktívne') ?></span>
          <?php else: ?>
            <label class="cookie-switch">
              <input type="checkbox" data-cookie-category="<?= e($cat['id']) ?>" aria-label="<?= h($cat['title']) ?>">
              <span class="cookie-switch__track"></span>
            </label>
          <?php endif; ?>
        </div>
        <p><?= h($cat['text']) ?></p>
        <table class="cookie-cat__table">
          <thead><tr><th><?= h('Cookie') ?></th><th><?= h('Platnosť') ?></th><th><?= h('Účel') ?></th></tr></thead>
          <tbody>
          <?php foreach ($cat['cookies'] as [$name, $duration, $purpose]): ?>
            <tr><td><?= h($name) ?></td><td><?= h($duration) ?></td><td><?= h($purpose) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endforeach; ?>
      <p class="cookie-modal__note"><?= h('Analytické ani reklamné cookies na tomto webe nepoužívame.') ?></p>
    </div>
    <div class="cookie-modal__foot">
      <button type="button" class="btn btn--outline btn--sm" data-cookie-reject><?= h('Odmietnuť všetko') ?></button>
      <button type="button" class="btn btn--outline btn--sm" data-cookie-save><?= h('Uložiť moje preferencie') ?></button>
      <button type="button" class="btn btn--primary btn--sm" data-cookie-accept><?= h('Prijať všetko') ?></button>
    </div>
  </div>
</div>

<button type="button" class="cookie-revisit" id="cookieRevisit" data-cookie-settings aria-label="<?= h('Nastavenia cookies') ?>" title="<?= h('Nastavenia cookies') ?>" hidden><?= icon('cookie') ?></button>
    <?php
    return (string) ob_get_clean();
}
