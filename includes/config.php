<?php
/**
 * Centrálna konfigurácia webu Pizzeria Tominno.
 *
 * Údaje o prevádzke (kontakty, otváracie hodiny, rozvoz, Google, firma...)
 * sa upravujú v administrácii (/admin -> Nastavenia) a ukladajú sa do
 * storage/content/settings.json. Hodnoty nižšie v $defaults sú iba
 * východiskové - použijú sa, kým v administrácii nie je nič uložené.
 */

date_default_timezone_set('Europe/Bratislava');

define('SITE_NAME', 'Pizzeria Tominno');
define('SITE_CLAIM', 'Neapolská pizza pečená v peci na drevo - z talianskej múky a cesta fermentovaného 48 hodín.');

// Oficiálna adresa webu - používa sa v kanonických odkazoch, pri zdieľaní
// a v štruktúrovaných dátach pre Google. Nepreberá sa z hlavičky Host.
define('SITE_URL', 'https://www.pizzeriatominno.sk');
define('SITE_DOMAIN', 'pizzeriatominno.sk');

// Počiatočné heslo do administrácie (bcrypt hash). Po prvom prihlásení ho
// klient zmení v administrácii - nové heslo sa uloží do storage/admin.json.
define('ADMIN_PASSWORD_HASH', '$2y$12$vttzBq5hdes0YwL5h8lUKuLEwIkn5.rTd/IPo7T3r66HrOavKhxJy');

/** Východiskové nastavenia prevádzky (prepíše ich storage/content/settings.json). */
function defaultSettings(): array
{
    return [
        'phone'           => '0910 777 336',
        'email'           => 'objednavky@pizzeriatominno.sk',
        'address_street'  => 'Hlavná 26',
        'address_zip'     => '076 02',
        'address_town'    => 'Novosad',

        // Otváracie hodiny: [otvára, zatvára], zatvorený deň = null.
        'opening_hours' => [
            'Pondelok' => ['10:00', '22:00'],
            'Utorok'   => ['10:00', '22:00'],
            'Streda'   => ['10:00', '22:00'],
            'Štvrtok'  => ['10:00', '22:00'],
            'Piatok'   => ['10:00', '22:00'],
            'Sobota'   => ['10:00', '22:00'],
            'Nedeľa'   => ['10:00', '22:00'],
        ],
        // Dni, keď je mimoriadne zatvorené (sviatky, dovolenka) - formát RRRR-MM-DD.
        'closed_dates' => [],

        // Oznam na webe (pás pod hlavičkou), napr. zmena otváracích hodín cez sviatky.
        'notice' => ['active' => false, 'text' => '', 'text_hu' => ''],

        'delivery_area'      => 'Novosad, Trebišov a okolie',
        'delivery_area_hu'   => 'Novosad, Tőketerebes és környéke',
        'delivery_towns'     => ['Novosad', 'Trebišov'],
        'delivery_fee'       => 1.50,
        'delivery_free_from' => 15.00,
        'delivery_min_order' => 8.00,

        // Google profil. Odkaz na profil je odvodený z mapy na kontakte.
        // google_review_url: odkaz "Požiadať o recenzie" z Google Business Profile.
        'google_profile_url'  => 'https://maps.google.com/?cid=15707185046585834105',
        'google_review_url'   => '',
        'google_rating'       => '',
        'google_review_count' => 0,

        'social_facebook'  => '',
        'social_instagram' => '',

        // Prevádzkovateľ - povinné údaje pre stránku o ochrane osobných údajov.
        'company_name'    => '',
        'company_address' => '',
        'company_ico'     => '',
    ];
}

/** Aktuálne nastavenia = východiskové + uložené z administrácie. */
function siteSettings(): array
{
    static $settings = null;
    if ($settings === null) {
        $settings = defaultSettings();
        $file = __DIR__ . '/../storage/content/settings.json';
        if (is_file($file)) {
            $saved = json_decode((string) file_get_contents($file), true);
            if (is_array($saved)) {
                $settings = array_replace($settings, array_intersect_key($saved, $settings));
            }
        }
    }
    return $settings;
}

$settings = siteSettings();

define('SITE_PHONE', $settings['phone']);
// Telefón pre odkaz tel: - medzinárodný tvar bez medzier (0910... -> +421910...).
define('SITE_PHONE_TEL', preg_replace('/^0/', '+421', preg_replace('/[^0-9+]/', '', $settings['phone'])));
define('SITE_EMAIL', $settings['email']);

define('SITE_ADDRESS_STREET', $settings['address_street']);
define('SITE_ADDRESS_ZIP', $settings['address_zip']);
define('SITE_ADDRESS_TOWN', $settings['address_town']);
define('SITE_ADDRESS_CITY', trim($settings['address_zip'] . ' ' . $settings['address_town']));
define('SITE_ADDRESS_FULL', $settings['address_street'] . ', ' . SITE_ADDRESS_CITY);

define('COMPANY_NAME', $settings['company_name']);
define('COMPANY_ADDRESS', $settings['company_address']);
define('COMPANY_ICO', $settings['company_ico']);

define('OPENING_HOURS', $settings['opening_hours']);

// Rozvoz - objednávky sa prijímajú iba telefonicky (online objednávky sú zrušené).
define('DELIVERY_FEE', (float) $settings['delivery_fee']);
define('DELIVERY_FREE_FROM', (float) $settings['delivery_free_from']);
define('DELIVERY_MIN_ORDER', (float) $settings['delivery_min_order']);
define('DELIVERY_AREA', $settings['delivery_area']);
define('DELIVERY_TOWNS', $settings['delivery_towns']);

define('GOOGLE_PROFILE_URL', $settings['google_profile_url']);
define('GOOGLE_REVIEW_URL', $settings['google_review_url']);
define('GOOGLE_RATING', (string) $settings['google_rating']);
define('GOOGLE_REVIEW_COUNT', (int) $settings['google_review_count']);

define('SOCIAL_FACEBOOK', $settings['social_facebook']);
define('SOCIAL_INSTAGRAM', $settings['social_instagram']);

define('CURRENCY', '€');

unset($settings);
