<?php
/**
 * Kontrola úplnosti maďarského prekladu (data/lang-hu.php).
 *
 * Zozbiera všetky texty webu, ktoré prechádzajú cez preklad - volania
 * h()/t()/formAlert() v šablónach, titulky stránok, popisky v galérii,
 * jedálny lístok (názvy, kategórie, suroviny), alergény, dni... - a vypíše,
 * ktoré v slovníku chýbajú a ktoré sú v slovníku navyše.
 *
 * Spustenie (z koreňa webu):  php tools/check-translations.php
 */

chdir(__DIR__ . '/..');
require 'includes/config.php';
require 'includes/functions.php';
require 'includes/forms.php';

$keys = [];
$add = function ($text) use (&$keys): void {
    $text = (string) $text;
    // Preskočí prázdne texty, čísla a gramáže ("450 g") - tie sa neprekladajú.
    if ($text !== '' && preg_match('/\p{L}/u', $text) && !preg_match('/^\d+ g$/', $text)) {
        $keys[$text] = true;
    }
};

// 1. Texty v šablónach: h('...'), t('...'), formAlert('...'), $pageTitle/$pageDescription
$files = array_merge(glob('*.php'), glob('includes/*.php'), glob('includes/privacy/*.php'));
foreach ($files as $file) {
    $src = file_get_contents($file);
    preg_match_all('/\b(?:h|t|formAlert)\(\s*\'([^\']*)\'/u', $src, $m);
    array_map($add, $m[1]);
    preg_match_all('/\$(?:pageTitle|pageDescription)\s*=\s*\'([^\']*)\'/u', $src, $m);
    array_map($add, $m[1]);
}

// 2. Popisky v poliach šablón (galéria, oslavy, dlaždice na úvode)
foreach (['galeria.php', 'oslavy-a-akcie.php', 'index.php'] as $file) {
    $src = file_get_contents($file);
    preg_match_all('/\'[a-z0-9-]+\'\s*=>\s*\'([^\']+)\'/u', $src, $m);
    foreach ($m[1] as $text) {
        if (!preg_match('~^[a-z0-9-]+$|^/~', $text)) {
            $add($text);
        }
    }
    preg_match_all('/^\s*\'([^\']+)\'\s*=>\s*\[$/mu', $src, $m);
    array_map($add, $m[1]);
    preg_match_all('/\'(?:title|text)\'\s*=>\s*\'([^\']+)\'/u', $src, $m);
    array_map($add, $m[1]);
}

// 3. Dáta: jedálny lístok, alergény, typy akcií, dni, konštanty
foreach (getMenu() as $category) {
    foreach (['label', 'subtitle', 'note'] as $k) {
        $add($category[$k] ?? '');
    }
    $add($category['link']['label'] ?? '');
    foreach ($category['items'] as $item) {
        $add($item['name']);
        foreach (menuItemPrices($item) as $variant) {
            $add($variant['label']);
        }
        array_map($add, preg_split('/(?:, | \+ )/u', $item['desc']));
    }
}
array_map($add, getAllergens());
array_map($add, eventTypes());
array_map($add, array_keys(OPENING_HOURS));
array_map($add, dayAbbreviations());
array_map($add, ['v pondelok', 'v utorok', 'v stredu', 'vo štvrtok', 'v piatok', 'v sobotu', 'v nedeľu']);
array_map($add, [SITE_CLAIM, DELIVERY_AREA]);

$dict = require 'data/lang-hu.php';
$missing = array_diff(array_keys($keys), array_keys($dict));
$unused = array_diff(array_keys($dict), array_keys($keys));

echo 'Textov na preklad: ' . count($keys) . ', chýba prekladov: ' . count($missing) . ', navyše v slovníku: ' . count($unused) . "\n";
foreach ($missing as $text) {
    echo "  CHÝBA:  $text\n";
}
foreach ($unused as $text) {
    echo "  NAVYŠE: $text\n";
}
exit($missing ? 1 : 0);
