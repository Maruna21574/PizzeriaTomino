<?php
$pageTitle = 'O nás';
$pageDescription = 'Spoznajte príbeh Pizzeria Tominno v Novosade - pravú talianskú pizzu pripravovanú s láskou a poctivými surovinami.';
$bodyClass = 'page-about';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--about">
  <div class="container">
    <p class="eyebrow">O nás</p>
    <h1>Náš príbeh</h1>
    <p class="page-hero__lead">Pizzeria s dušou v obci Novosad - pripravujeme pizzu tak, ako sa na to patrí.</p>
  </div>
</section>

<section class="section about-story">
  <div class="container about-story__grid">
    <div class="about-story__img" style="background-image:url('https://images.unsplash.com/photo-1595295333158-4742f28fbd85?auto=format&fit=crop&w=1200&q=80')"></div>
    <div class="about-story__text">
      <p class="eyebrow">Ako sme začali</p>
      <h2>Láska k pizze, ktorá sa stala remeslom</h2>
      <p>Pizzeria Tominno vznikla z jednoduchej myšlienky - priniesť do Novosadu a okolia poctivú taliansku pizzu, akú poznáme z dovoleniek v Taliansku. Cesto necháme dostatočne dlho kysnúť, aby bolo ľahké a nadýchané, a na prípravu používame len kvalitné suroviny.</p>
      <p>Každý deň pripravujeme cesto čerstvé, syry a zeleninu vyberáme od overených dodávateľov a pizzu pečieme tak, aby mala tú správnu chrumkavú kôrku. Náš tím sa snaží, aby ste sa pri každom soste cítili, akoby ste sedeli v malej reštaurácii kdesi v Neapole.</p>
    </div>
  </div>
</section>

<section class="section values">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow">Naše hodnoty</p>
      <h2>Na čom nám záleží</h2>
    </div>
    <div class="usp__grid">
      <div class="usp__item">
        <span class="usp__icon"><?= icon('leaf') ?></span>
        <h3>Kvalitné suroviny</h3>
        <p>Vyberáme si overených dodávateľov a čerstvé, sezónne suroviny.</p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('flame') ?></span>
        <h3>Tradičná príprava</h3>
        <p>Pizzu pečieme podľa overených talianskych postupov a receptúr.</p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('heart') ?></span>
        <h3>Vzťah k zákazníkom</h3>
        <p>Vážime si každého zákazníka a snažíme sa o rýchly a príjemný servis.</p>
      </div>
      <div class="usp__item">
        <span class="usp__icon"><?= icon('home') ?></span>
        <h3>Sme tu pre komunitu</h3>
        <p>Sme hrdí, že môžeme obsluhovať Novosad, Michalovce a okolité obce.</p>
      </div>
    </div>
  </div>
</section>

<section class="section team-photo">
  <div class="container team-photo__grid">
    <div class="team-photo__img" style="background-image:url('https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=1200&q=80')"></div>
    <div class="team-photo__img" style="background-image:url('https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?auto=format&fit=crop&w=1200&q=80')"></div>
    <div class="team-photo__img" style="background-image:url('https://images.unsplash.com/photo-1607013251379-e6eecfffe234?auto=format&fit=crop&w=1200&q=80')"></div>
  </div>
</section>

<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2>Ochutnajte náš príbeh</h2>
      <p>Objednajte si pizzu z pece na drevo priamo k vám domov.</p>
    </div>
    <a href="/objednavka.php" class="btn btn--primary btn--lg">Objednať teraz</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
