<?php
/**
 * Centrálna konfigurácia webu Pizzeria Tominno.
 * Zmeň tu údaje o prevádzke, kontakty a nastavenia rozvozu.
 */

date_default_timezone_set('Europe/Bratislava');

define('SITE_NAME', 'Pizzeria Tominno');
define('SITE_CLAIM', 'Neapolská pizza pečená v peci na drevo - z talianskej múky a cesta fermentovaného 48 hodín.');

// Oficiálna adresa webu - používa sa v kanonických odkazoch, pri zdieľaní
// a v štruktúrovaných dátach pre Google. Nepreberá sa z hlavičky Host.
define('SITE_URL', 'https://www.pizzeriatominno.sk');
define('SITE_DOMAIN', 'pizzeriatominno.sk');

define('SITE_PHONE', '0910 777 336');
define('SITE_PHONE_TEL', '+421910777336');
define('SITE_EMAIL', 'objednavky@pizzeriatominno.sk');

define('SITE_ADDRESS_STREET', 'Hlavná 26');
define('SITE_ADDRESS_CITY', '076 02 Novosad');
define('SITE_ADDRESS_FULL', 'Hlavná 26, 076 02 Novosad');
define('SITE_ADDRESS_ZIP', '076 02');
define('SITE_ADDRESS_TOWN', 'Novosad');

// Prevádzkovateľ (firma/živnostník) - povinné údaje pre stránku o ochrane
// osobných údajov. DOPLNIŤ pred spustením webu.
define('COMPANY_NAME', '');
define('COMPANY_ADDRESS', '');
define('COMPANY_ICO', '');

// Otváracie hodiny - jediné miesto, kde sa menia. Formát [otvára, zatvára];
// zatvorený deň = null. Z týchto údajov sa počíta "Otvorené teraz",
// súhrn v pätičke/kontakte aj štruktúrované dáta pre Google.
define('OPENING_HOURS', [
    'Pondelok' => ['10:00', '22:00'],
    'Utorok'   => ['10:00', '22:00'],
    'Streda'   => ['10:00', '22:00'],
    'Štvrtok'  => ['10:00', '22:00'],
    'Piatok'   => ['10:00', '22:00'],
    'Sobota'   => ['10:00', '22:00'],
    'Nedeľa'   => ['10:00', '22:00'],
]);

// Rozvoz - objednávky sa prijímajú iba telefonicky (online objednávky sú zrušené).
define('DELIVERY_FEE', 1.50);
define('DELIVERY_FREE_FROM', 15.00);
define('DELIVERY_MIN_ORDER', 8.00);
define('DELIVERY_AREA', 'Novosad, Trebišov a okolie');
define('DELIVERY_TOWNS', ['Novosad', 'Trebišov']); // pre Google (štruktúrované dáta)

define('CURRENCY', '€');

// Google profil firmy (Mapy Google). Odkaz na profil je odvodený z mapy na kontakte.
// GOOGLE_REVIEW_URL: odkaz "Požiadať o recenzie" z Google Business Profile
// (tvar https://g.page/r/.../review) - otvorí rovno okno na napísanie recenzie.
// GOOGLE_RATING / GOOGLE_REVIEW_COUNT: aktuálne hodnotenie z profilu (napr. '4,8' a 120),
// prázdne = nezobrazí sa. Samotné recenzie sú v data/reviews.php.
define('GOOGLE_PROFILE_URL', 'https://maps.google.com/?cid=15707185046585834105');
define('GOOGLE_REVIEW_URL', '');
define('GOOGLE_RATING', '');
define('GOOGLE_REVIEW_COUNT', 0);

// Sociálne siete (voliteľné - vyplň, alebo nechaj prázdne pre skrytie)
define('SOCIAL_FACEBOOK', '');
define('SOCIAL_INSTAGRAM', '');
