<?php
$pageTitle = 'Objednávka';
$pageDescription = 'Dokončite svoju objednávku v Pizzeria Tominno. Platba iba na dobierku - hotovosť alebo karta u kuriéra.';
$bodyClass = 'page-order';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--order">
  <div class="container">
    <p class="eyebrow">Objednávka</p>
    <h1>Váš košík</h1>
    <p class="page-hero__lead">Skontrolujte si vybrané jedlá a vyplňte údaje pre doručenie. Platíte až pri prevzatí - hotovosťou alebo kartou.</p>
  </div>
</section>

<section class="section order-section">
  <div class="container order-grid">

    <div class="order-main">
      <div class="order-panel">
        <h2>Vybrané položky</h2>
        <div id="cartItems" class="cart-items">
          <!-- Naplní JS z localStorage (assets/js/order.js) -->
        </div>
        <div id="cartEmpty" class="cart-empty" hidden>
          <p>Váš košík je zatiaľ prázdny.</p>
          <a href="/menu.php" class="btn btn--primary">Prejsť do menu</a>
        </div>
        <div class="order-panel__more">
          <a href="/menu.php" class="btn btn--outline btn--sm">+ Pridať ďalšie položky</a>
        </div>
      </div>

      <div class="order-panel">
        <h2>Údaje o doručení</h2>
        <form id="orderForm" class="order-form" novalidate>
          <div class="form-grid">
            <div class="form-row">
              <label for="fullName">Meno a priezvisko *</label>
              <input type="text" id="fullName" name="fullName" required autocomplete="name">
            </div>
            <div class="form-row">
              <label for="phone">Telefónne číslo *</label>
              <input type="tel" id="phone" name="phone" required autocomplete="tel" placeholder="09XX XXX XXX">
            </div>
            <div class="form-row form-row--full">
              <label for="email">E-mail (nepovinné, pre potvrdenie)</label>
              <input type="email" id="email" name="email" autocomplete="email">
            </div>
            <div class="form-row">
              <label for="street">Ulica a číslo *</label>
              <input type="text" id="street" name="street" required autocomplete="street-address">
            </div>
            <div class="form-row">
              <label for="city">Obec / mesto *</label>
              <input type="text" id="city" name="city" required value="Novosad" autocomplete="address-level2">
            </div>
            <div class="form-row form-row--full">
              <label for="note">Poznámka k objednávke (nepovinné)</label>
              <textarea id="note" name="note" rows="3" placeholder="Napr. poschodie, kód od brány, alergie..."></textarea>
            </div>
          </div>

          <h3 class="form-subheading">Spôsob platby</h3>
          <div class="payment-options">
            <label class="payment-option">
              <input type="radio" name="payment" value="hotovost" checked>
              <span class="payment-option__icon"><?= icon('cash') ?></span>
              <span>
                <strong>Hotovosť</strong>
                <small>Zaplatíte kuriérovi pri prevzatí</small>
              </span>
            </label>
            <label class="payment-option">
              <input type="radio" name="payment" value="karta">
              <span class="payment-option__icon"><?= icon('card') ?></span>
              <span>
                <strong>Platobná karta</strong>
                <small>Platobný terminál priamo u kuriéra</small>
              </span>
            </label>
          </div>
          <p class="note">Objednávka je vždy na dobierku - platba vopred online nie je možná.</p>

          <label class="checkbox-row">
            <input type="checkbox" id="agree" name="agree" required>
            <span>Súhlasím so spracovaním osobných údajov za účelom vybavenia objednávky. *</span>
          </label>

          <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div id="orderFormMessage" class="alert" hidden></div>

          <button type="submit" class="btn btn--primary btn--lg btn--full" id="submitOrderBtn">Odoslať objednávku na dobierku</button>
        </form>
      </div>
    </div>

    <aside class="order-summary">
      <div class="order-summary__box">
        <h2>Súhrn objednávky</h2>
        <div class="order-summary__row">
          <span>Medzisúčet</span>
          <span id="sumSubtotal">0,00 €</span>
        </div>
        <div class="order-summary__row">
          <span>Doprava</span>
          <span id="sumDelivery">0,00 €</span>
        </div>
        <p class="order-summary__hint">Doprava zdarma od <?= formatPrice(DELIVERY_FREE_FROM) ?>. Minimálna objednávka <?= formatPrice(DELIVERY_MIN_ORDER) ?>.</p>
        <div class="order-summary__row order-summary__row--total">
          <span>Spolu</span>
          <span id="sumTotal">0,00 €</span>
        </div>
        <div class="order-summary__meta">
          <p><?= icon('pin', 'icon icon--sm') ?> Doručujeme: <?= e(DELIVERY_AREA) ?></p>
          <p><?= icon('clock', 'icon icon--sm') ?> Otvorené denne 10:00 – 22:00</p>
          <p><?= icon('phone', 'icon icon--sm') ?> Otázky k objednávke: <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a></p>
        </div>
      </div>
    </aside>

  </div>
</section>

<script>
  window.PT_CONFIG = {
    deliveryFee: <?= json_encode(DELIVERY_FEE) ?>,
    deliveryFreeFrom: <?= json_encode(DELIVERY_FREE_FROM) ?>,
    minOrder: <?= json_encode(DELIVERY_MIN_ORDER) ?>,
    currency: <?= json_encode(CURRENCY) ?>
  };
</script>
<script src="/assets/js/order.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
