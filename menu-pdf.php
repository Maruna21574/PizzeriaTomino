<?php
/**
 * Jedálny lístok na stiahnutie (PDF) - /menu-pdf a /hu/menu-pdf.
 *
 * PDF sa generuje priamo na serveri (knižnica Dompdf) z aktuálneho menu
 * v administrácii a ukladá sa do storage/cache. Nové PDF sa vytvorí
 * automaticky po každej zmene menu, nastavení alebo prekladu.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$cacheDir = __DIR__ . '/storage/cache';
$version = md5(implode('|', [
    lang(),
    contentModified('menu'),
    contentModified('settings'),
    filemtime(__DIR__ . '/data/lang-hu.php'),
    filemtime(__FILE__),
]));
$cacheFile = $cacheDir . '/menu-' . lang() . '-' . $version . '.pdf';
$downloadName = 'pizzeria-tominno-menu-' . lang() . '.pdf';

if (!is_file($cacheFile)) {
    require_once __DIR__ . '/vendor/autoload.php';

    $menu = getMenu();
    $allergens = getAllergens();

    ob_start();
    ?>
<!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 14mm 14mm 16mm; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 8.4pt; color: #2b211d; line-height: 1.3; }
  .head { width: 100%; border-bottom: 2px solid #c1272d; margin-bottom: 8px; }
  .head td { vertical-align: bottom; padding-bottom: 6px; }
  .head img { height: 40px; }
  .head h1 { margin: 0; font-size: 17pt; color: #c1272d; text-align: right; }
  .head p { margin: 2px 0 0; color: #7a6c63; font-size: 8pt; text-align: right; }
  .cat { background: #241a15; color: #fff; padding: 5px 8px; margin: 10px 0 4px; font-weight: bold; font-size: 11pt; }
  .cat span { color: #e3a018; font-size: 7.5pt; font-weight: bold; text-transform: uppercase; margin-left: 6px; }
  .note { color: #7a6c63; font-size: 7.6pt; margin: 0 0 4px; }
  table.items { width: 100%; border-collapse: collapse; }
  table.items td { padding: 3px 0; border-bottom: 1px dotted #d8cbbf; vertical-align: top; }
  table.items tr { page-break-inside: avoid; }
  .name { font-weight: bold; font-size: 9pt; }
  .num { color: #c1272d; }
  .desc { color: #5c4f47; font-size: 7.6pt; }
  .alg { color: #9a8b81; font-size: 6.8pt; }
  .price { text-align: right; white-space: nowrap; width: 34%; }
  .price b { color: #c1272d; font-size: 9pt; }
  .price small { color: #7a6c63; font-size: 7.2pt; }
  .foot { margin-top: 12px; border-top: 2px solid #c1272d; padding-top: 6px; page-break-inside: avoid; }
  .foot table { width: 100%; }
  .foot td { vertical-align: top; }
  .foot h3 { margin: 0 0 3px; font-size: 9pt; color: #c1272d; }
  .phone { font-size: 13pt; font-weight: bold; color: #c1272d; }
  .small { font-size: 7pt; color: #5c4f47; }
</style>
</head>
<body>

<table class="head">
  <tr>
    <td><img src="<?= e(__DIR__ . '/assets/img/logo_tomino_b.png') ?>" alt=""></td>
    <td>
      <h1><?= h('Jedálny lístok') ?></h1>
      <p><?= h(SITE_CLAIM) ?></p>
    </td>
  </tr>
</table>

<?php foreach ($menu as $category): ?>
<div class="cat"><?= e(tf($category, 'label')) ?><?php if (!empty($category['subtitle'])): ?><span><?= e(tf($category, 'subtitle')) ?></span><?php endif; ?></div>
<?php if (!empty($category['note'])): ?><p class="note"><?= e(tf($category, 'note')) ?></p><?php endif; ?>
<table class="items">
  <?php foreach ($category['items'] as $item): ?>
  <tr>
    <td>
      <span class="name"><?php if (!empty($item['num'])): ?><span class="num"><?= (int) $item['num'] ?>.</span> <?php endif; ?><?= e(tf($item, 'name')) ?></span>
      <?php if (!empty($item['desc'])): ?><br><span class="desc"><?= e(tf($item, 'desc', true)) ?></span><?php endif; ?>
      <?php if (!empty($item['allergens'])): ?> <span class="alg">(<?= e(implode(', ', $item['allergens'])) ?>)</span><?php endif; ?>
    </td>
    <td class="price">
      <?php foreach (menuItemPrices($item) as $i => $variant): ?>
      <?= $i ? '<br>' : '' ?><?php if ($variant['label'] !== ''): ?><small><?= e(t($variant['label'])) ?></small> <?php endif; ?><b><?= formatPrice((float) $variant['price']) ?></b>
      <?php endforeach; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endforeach; ?>

<div class="foot">
  <table>
    <tr>
      <td style="width: 45%;">
        <h3><?= h('Objednávky telefonicky') ?></h3>
        <div class="phone"><?= e(SITE_PHONE) ?></div>
        <div><?= e(SITE_ADDRESS_FULL) ?></div>
        <div><?= e(openingHoursSummary()) ?></div>
        <div><?= h('Rozvoz: %s · Platba hotovosť / karta', e(deliveryArea())) ?></div>
        <div><?= e(preg_replace('~^https?://~', '', SITE_URL)) ?></div>
      </td>
      <td>
        <h3><?= h('Zoznam alergénov') ?></h3>
        <div class="small"><?php $i = 0; foreach ($allergens as $code => $label): ?><?= $i++ ? ' · ' : '' ?><b><?= (int) $code ?></b> <?= h($label) ?><?php endforeach; ?></div>
        <div class="small" style="margin-top: 3px;"><?= h('Zloženie a alergény sa môžu meniť podľa aktuálnej dostupnosti surovín. V prípade alergie alebo neznášanlivosti nám prosím povedzte pri objednávke.') ?></div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
    <?php
    $html = ob_get_clean();

    $options = new Dompdf\Options();
    $options->set('isRemoteEnabled', false);
    $options->set('chroot', __DIR__);
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('tempDir', sys_get_temp_dir());

    $dompdf = new Dompdf\Dompdf($options);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4');
    $dompdf->render();

    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    // Staré verzie PDF v danom jazyku zmažeme, uložíme novú.
    foreach (glob($cacheDir . '/menu-' . lang() . '-*.pdf') ?: [] as $old) {
        @unlink($old);
    }
    if (@file_put_contents($cacheFile, $dompdf->output(), LOCK_EX) === false) {
        // Ak sa nedá zapisovať do storage/, pošleme PDF priamo bez uloženia.
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $downloadName . '"');
        echo $dompdf->output();
        exit;
    }
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($cacheFile));
header('Cache-Control: public, max-age=300');
readfile($cacheFile);
