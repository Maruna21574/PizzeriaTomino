<?php
/** Prehľad - rýchle odkazy, štatistiky a upozornenia na chýbajúce údaje. */

$menu = menuContent();
$itemCount = 0;
$hiddenCount = 0;
foreach ($menu as $category) {
    foreach ($category['items'] as $item) {
        $itemCount++;
        $hiddenCount += empty($item['hidden']) && empty($category['hidden']) ? 0 : 1;
    }
}
$photoCount = array_sum(array_map(function ($s) { return count($s['photos']); }, galleryContent()));
$reviewCount = count(reviewsContent());
$settings = siteSettings();

$warnings = [];
if (adminUsesInitialPassword()) {
    $warnings[] = ['Používate počiatočné heslo. Zmeňte si ho.', 'password'];
}
if ($settings['company_name'] === '' || $settings['company_ico'] === '') {
    $warnings[] = ['Chýbajú údaje o prevádzkovateľovi (obchodné meno, IČO) - zobrazujú sa na stránke Ochrana osobných údajov.', 'settings'];
}
if ($settings['google_review_url'] === '') {
    $warnings[] = ['Nie je vyplnený odkaz na napísanie recenzie na Google.', 'reviews'];
}
if (!empty($settings['notice']['active'])) {
    $warnings[] = ['Na webe je zapnutý oznam: „' . $settings['notice']['text'] . '“', 'settings'];
}

$tiles = [
    ['menu', 'Jedálny lístok', 'pizza', $itemCount . ' položiek' . ($hiddenCount ? ', ' . $hiddenCount . ' skrytých' : ''), 'Ceny, zloženie, fotky jedál, nové položky.'],
    ['gallery', 'Galéria', 'zoom-in', $photoCount . ' fotiek', 'Nahrávanie fotiek, sekcie, fotky na úvodnej stránke.'],
    ['reviews', 'Recenzie', 'star', $reviewCount . ' recenzií', 'Recenzie z Google a hodnotenie.'],
    ['settings', 'Nastavenia', 'clock', openingHoursSummary(), 'Otváracie hodiny, zatvorené dni, oznam, kontakty, rozvoz.'],
];

adminHeader('Prehľad', 'dashboard');
?>
<h1>Dobrý deň!</h1>
<p class="admin-lead">Tu upravíte obsah webu <a href="/" target="_blank" rel="noopener"><?= e(preg_replace('~^https?://~', '', SITE_URL)) ?></a>. Zmeny sa na webe prejavia hneď po uložení.</p>

<?php foreach ($warnings as [$message, $page]): ?>
<div class="admin-alert admin-alert--warning"><?= e($message) ?> <a href="<?= e(adminUrl($page)) ?>">Upraviť &rarr;</a></div>
<?php endforeach; ?>

<div class="admin-tiles">
  <?php foreach ($tiles as [$page, $title, $iconName, $stat, $text]): ?>
  <a class="admin-tile" href="<?= e(adminUrl($page)) ?>">
    <span class="admin-tile__icon"><?= icon($iconName) ?></span>
    <strong><?= e($title) ?></strong>
    <span class="admin-tile__stat"><?= e($stat) ?></span>
    <span class="admin-muted"><?= e($text) ?></span>
  </a>
  <?php endforeach; ?>
</div>

<section class="admin-card">
  <h2>Jedálny lístok na stiahnutie (PDF)</h2>
  <p class="admin-muted">PDF sa vytvára automaticky z jedálneho lístka - po každej zmene menu je hneď aktuálne.</p>
  <p>
    <a class="admin-btn" href="/menu-pdf" target="_blank" rel="noopener"><?= icon('download', 'icon icon--sm') ?> PDF slovensky</a>
    <a class="admin-btn" href="/hu/menu-pdf" target="_blank" rel="noopener"><?= icon('download', 'icon icon--sm') ?> PDF maďarsky</a>
  </p>
</section>
<?php
adminFooter();
