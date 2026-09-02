<?php
$pageTitle = 'Domov';
$pageDescription = 'Pizzeria Tominno v Novosade - pravá talianska pizza z pece na drevo. Objednajte si online s rozvozom kuriérom, platba na dobierku (hotovosť alebo karta).';
$bodyClass = 'page-home';
require_once __DIR__ . '/includes/header.php';

$menu = getMenu();
$featured = array_slice($menu['pizza']['items'], 0, 6);
?>

<section class="hero">
  <div class="hero__bg" style="background-image:url('https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=1900&q=80')"></div>
  <div class="hero__overlay"></div>
  <div class="container hero__content">
    <p class="eyebrow">Novosad · Michalovce a okolie</p>
    <h1>Pravá talianska pizza,<br>rovno z <span>pece na drevo</span></h1>
    <p class="hero__lead">Čerstvé cesto, kvalitné suroviny a chuť, na ktorú si spomeniete. Doručíme ju priamo k vám kuriérom - stačí zavolať alebo objednať online.</p>
    <div class="hero__actions">
      <a href="/objednavka.php" class="btn btn--primary btn--lg">Objednať online</a>
      <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--ghost btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
    </div>
    <ul class="hero__badges">
      <li><?= icon('flame', 'icon icon--sm') ?> Pečené na dreve</li>
      <li><?= icon('truck', 'icon icon--sm') ?> Rozvoz kuriérom</li>
      <li><?= icon('cash', 'icon icon--sm') ?> Platba na dobierku</li>
    </ul>
  </div>
</section>

<section class="section featured">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow">Naša ponuka</p>
      <h2>Najobľúbenejšie pizze</h2>
      <p class="section__lead">Výber z nášho jedálneho lístka - kompletné menu nájdete na samostatnej stránke.</p>
    </div>

    <div class="card-grid">
      <?php foreach ($featured as $item): ?>
      <article class="food-card">
        <div class="food-card__img" style="background-image:url('<?= e($item['img']) ?>')">
          <?php if (!empty($item['tags'])): ?>
          <span class="food-card__tag"><?= e(ucfirst($item['tags'][0])) ?></span>
          <?php endif; ?>
          <?= allergenTriggerButton($item) ?>
        </div>
        <div class="food-card__body">
          <div class="food-card__top">
            <div>
              <h3><?= e($item['name']) ?></h3>
              <?php if (!empty($item['weight'])): ?>
              <span class="food-card__weight"><?= e($item['weight']) ?></span>
              <?php endif; ?>
            </div>
            <span class="food-card__price"><?= formatPrice($item['price']) ?></span>
          </div>
          <p><?= e($item['desc']) ?></p>
          <button class="btn btn--add" data-add-to-cart data-id="<?= e($item['id']) ?>" data-name="<?= e($item['name']) ?>" data-price="<?= e($item['price']) ?>">
            + Pridať do košíka
          </button>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="section__cta">
      <a href="/menu.php" class="btn btn--outline btn--lg">Zobraziť celé menu</a>
    </div>
  </div>
</section>

<section class="section how-it-works how-it-works--light">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow">Ako to funguje</p>
      <h2>Objednávka v troch krokoch</h2>
    </div>
    <div class="steps">
      <div class="step">
        <span class="step__num">1</span>
        <h3>Vyberte si jedlo</h3>
        <p>Prejdite si menu a pridajte obľúbené pizze či prílohy do košíka.</p>
      </div>
      <div class="step">
        <span class="step__num">2</span>
        <h3>Vyplňte objednávku</h3>
        <p>Zadajte adresu doručenia a vyberte spôsob platby - hotovosť alebo karta u kuriéra.</p>
      </div>
      <div class="step">
        <span class="step__num">3</span>
        <h3>Kuriér doručí</h3>
        <p>Objednávku pripravíme a doručíme priamo k vašim dverám v Novosade a okolí.</p>
      </div>
    </div>
  </div>
</section>

<section class="section about-teaser">
  <div class="container about-teaser__grid">
    <div class="about-teaser__img" style="background-image:url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80')"></div>
    <div class="about-teaser__text">
      <p class="eyebrow">O nás</p>
      <h2>Pizzeria s dušou v srdci Novosadu</h2>
      <p>V Pizzerii Tominno pripravujeme pizzu s láskou k talianskej kuchyni a poctivým surovinám. Naším cieľom je, aby si každý zákazník pochutnal ako v Taliansku - bez ohľadu na to, či prídete k nám, alebo si objednáte rozvoz domov.</p>
      <a href="/o-nas.php" class="btn btn--outline">Viac o nás</a>
    </div>
  </div>
</section>

<section class="section testimonials">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow">Referencie</p>
      <h2>Čo hovoria naši zákazníci</h2>
    </div>
    <div class="testimonial-grid">
      <blockquote class="testimonial">
        <p>„Najlepšia pizza v okolí Michaloviec. Cesto je nadýchané a doviezli nám ju ešte teplú.“</p>
        <cite>— Jana, Novosad</cite>
      </blockquote>
      <blockquote class="testimonial">
        <p>„Objednávanie bolo jednoduché a rýchle, platba kuriérovi kartou bola super výhoda.“</p>
        <cite>— Peter, Michalovce</cite>
      </blockquote>
      <blockquote class="testimonial">
        <p>„Pravidelne si objednávame Tominno Špeciál, chuťou pripomína dovolenku v Taliansku.“</p>
        <cite>— Miroslava, Kusín</cite>
      </blockquote>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Chutí vám to už teraz?</h2>
      <p>Objednajte si online, alebo nám jednoducho zavolajte na <?= e(SITE_PHONE) ?>.</p>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--lg">Objednať pizzu</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
