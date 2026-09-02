<?php
/**
 * Centrálna konfigurácia webu Pizzeria Tominno.
 * Zmeň tu údaje o prevádzke, kontakty a nastavenia doručenia.
 */

define('SITE_NAME', 'Pizzeria Tominno');
define('SITE_CLAIM', 'Pravá talianska pizza z pece na drevo, dovezená priamo k vám');
define('SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'pizzeriatominno.sk'));

define('SITE_PHONE', '0910 777 336');
define('SITE_PHONE_TEL', '+421910777336');
define('SITE_EMAIL', 'objednavky@pizzeriatominno.sk');

define('SITE_ADDRESS_STREET', 'Hlavná 26');
define('SITE_ADDRESS_CITY', '076 02 Novosad');
define('SITE_ADDRESS_FULL', 'Hlavná 26, 076 02 Novosad');

// Súradnice obce Novosad (okres Michalovce) - použité pre mapu na kontakte.
define('SITE_MAP_LAT', 48.7716);
define('SITE_MAP_LNG', 21.9721);

define('OPENING_HOURS', [
    'Pondelok' => '10:00 – 22:00',
    'Utorok'   => '10:00 – 22:00',
    'Streda'   => '10:00 – 22:00',
    'Štvrtok'  => '10:00 – 22:00',
    'Piatok'   => '10:00 – 22:00',
    'Sobota'   => '10:00 – 22:00',
    'Nedeľa'   => '10:00 – 22:00',
]);

// Doručenie
define('DELIVERY_FEE', 1.50);
define('DELIVERY_FREE_FROM', 15.00);
define('DELIVERY_MIN_ORDER', 8.00);
define('DELIVERY_AREA', 'Novosad, Michalovce a blízke okolité obce');

// Platba - iba na dobierku (bez platobnej brány)
define('PAYMENT_METHODS', [
    'hotovost' => 'Hotovosť kuriérovi pri prevzatí',
    'karta'    => 'Platobná karta kuriérovi (platobný terminál)',
]);

define('CURRENCY', '€');

// Sociálne siete (voliteľné - vyplň, alebo nechaj prázdne pre skrytie)
define('SOCIAL_FACEBOOK', '');
define('SOCIAL_INSTAGRAM', '');
