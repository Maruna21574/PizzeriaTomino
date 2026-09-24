<?php
$pageTitle = 'O nás';
$pageDescription = 'Pizzeria Tominno v Novosade - neapolská pizza pečená v peci na drevo, talianska múka, talianske suroviny a cesto fermentované 48 hodín.';
$bodyClass = 'page-about';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--about">
  <div class="container">
    <p class="eyebrow"><?= h('O nás') ?></p>
    <h1><?= h('Neapolská pizza v Novosade') ?></h1>
    <p class="page-hero__lead"><?= h('Pečieme neapolskú pizzu v peci na drevo - z talianskej múky, talianskych surovín a cesta, ktoré necháme dozrieť 48 hodín.') ?></p>
  </div>
</section>

<section class="section about-story">
  <div class="container about-story__grid">
    <div class="about-story__img" style="background-image:url('<?= e(photo('pizza-v-peci')) ?>')"></div>
    <div class="about-story__text">
      <p class="eyebrow"><?= h('Naša pizza') ?></p>
      <h2><?= h('Robíme ju tak, ako sa robí v Neapole') ?></h2>
      <p><?= h('V Pizzerii Tominno pečieme pravú neapolskú pizzu. Základom je cesto z talianskej múky, ktoré fermentujeme 48 hodín. Vďaka tomu je ľahké, vzdušné a má výraznú chuť.') ?></p>
      <p><?= h('Na pizzu používame taliansky tovar - lúpané paradajky Pomodoro pelato Rosso Gargano, mozzarellu Fiordilatte Taglio Napoli, prosciutto crudo, burratu či gorgonzolu. Pečieme ju v rozpálenej peci na drevo, ktorá jej dá chrumkavý spodok, nadýchaný okraj a nezameniteľnú chuť ohňa.') ?></p>
      <p><?= h('Okrem pizze u nás nájdete aj domáce burgery, kebab, šaláty a na objednávku pripravíme párty misy na oslavy.') ?></p>
    </div>
  </div>
</section>

<section class="section section--alt values">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Na čom nám záleží') ?></p>
      <h2><?= h('Štyri veci, na ktorých nešetríme') ?></h2>
    </div>
    <div class="usp__grid">
      <div class="usp__item">
        <span class="usp__icon"><?= icon('wheat') ?></span>
        <h3><?= h('Talianska múka') ?></h3>
        <p><?= h('Neapolské cesto pripravujeme z kvalitnej talianskej múky.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('clock') ?></span>
        <h3><?= h('48 hodín fermentácie') ?></h3>
        <p><?= h('Cesto necháme pomaly dozrieť - je ľahké a dobre stráviteľné.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('flame') ?></span>
        <h3><?= h('Pec na drevo') ?></h3>
        <p><?= h('Pizzu pečieme pri vysokej teplote v peci na drevo.') ?></p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('leaf') ?></span>
        <h3><?= h('Talianske suroviny') ?></h3>
        <p><?= h('Paradajky, mozzarella, prosciutto či syry z Talianska.') ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section story">
  <div class="container">
    <div class="story-row">
      <div class="story-row__media">
        <video class="story-video" src="/assets/video/priprava-cesta.mp4" poster="/assets/video/priprava-cesta.jpg" autoplay muted loop playsinline preload="metadata" aria-label="<?= h('Video: takto u nás pripravujeme cesto na pizzu') ?>"></video>
      </div>
      <div class="story-row__text">
        <p class="eyebrow"><?= h('Pozrite sa do kuchyne') ?></p>
        <h2><?= h('Takto u nás vzniká cesto') ?></h2>
        <p><?= h('Cesto miesime z talianskej múky, necháme ho odpočinúť a potom ručne tvarujeme jednotlivé bochníky. Tie potom 48 hodín fermentujú, kým z nich v peci na drevo nevznikne neapolská pizza.') ?></p>
        <a href="<?= e(url('/galeria')) ?>" class="btn btn--outline"><?= h('Viac fotiek v galérii') ?></a>
      </div>
    </div>
  </div>
</section>

<section class="section section--alt about-place">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Príďte k nám') ?></p>
      <h2><?= h('Naša prevádzka a tím') ?></h2>
      <p class="section__lead"><?= h('Posedieť si môžete v interiéri aj na terase. Organizujeme aj akcie pre deti a rodiny, oslavy či posedenia.') ?></p>
    </div>
    <div class="team-photo__grid">
      <div class="team-photo__img" style="background-image:url('<?= e(photo('prevadzka-interier', true)) ?>')"></div>
      <div class="team-photo__img" style="background-image:url('<?= e(photo('nas-tim', true)) ?>')"></div>
      <div class="team-photo__img" style="background-image:url('<?= e(photo('terasa', true)) ?>')"></div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= h('Ochutnajte neapolskú pizzu') ?></h2>
      <p><?= h('Príďte k nám, alebo si ju objednajte telefonicky s rozvozom domov.') ?></p>
    </div>
    <a href="tel:<?= e(SITE_PHONE_TEL) ?>" class="btn btn--primary btn--lg"><?= icon('phone') ?> <?= e(SITE_PHONE) ?></a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
