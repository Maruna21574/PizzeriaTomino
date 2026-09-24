<?php
/**
 * Pomocné funkcie zdieľané naprieč webom.
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string
{
    return number_format($price, 2, ',', ' ') . ' ' . CURRENCY;
}

function getMenu(): array
{
    static $menu = null;
    if ($menu === null) {
        $menu = require __DIR__ . '/../data/menu.php';
    }
    return $menu;
}

function getAllergens(): array
{
    static $allergens = null;
    if ($allergens === null) {
        $allergens = require __DIR__ . '/../data/allergens.php';
    }
    return $allergens;
}

/**
 * Cesta k fotke z assets/img/foto. Fotky existujú vo dvoch veľkostiach:
 * plná (max 1400 px, pre lightbox a veľké plochy) a náhľad (max 640 px).
 */
function photo(string $name, bool $thumb = false): string
{
    return '/assets/img/foto/' . ($thumb ? 'thumb/' : '') . $name . '.jpg';
}

/**
 * Cena položky jedálneho lístka - jedna cena, alebo viac variantov
 * (napr. "Malý 1700 g / Veľký 2200 g").
 */
function menuItemPrices(array $item): array
{
    if (!empty($item['variants'])) {
        return $item['variants'];
    }
    return [['label' => $item['weight'] ?? '', 'price' => $item['price']]];
}

function isActivePage(string $page): string
{
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    return $current === $page ? ' active' : '';
}

/**
 * Pekná URL stránky bez prípony .php (napr. "/menu"), domov je "/".
 * Zodpovedá prepisovacím pravidlám v .htaccess.
 */
function pageUrl(string $script, ?string $forLang = null): string
{
    $name = basename($script, '.php');
    return url($name === 'index' ? '/' : '/' . $name, $forLang);
}

/** Kanonická (absolútna) adresa aktuálnej stránky v danom jazyku - bez parametrov. */
function canonicalUrl(?string $forLang = null): string
{
    return SITE_URL . pageUrl($_SERVER['SCRIPT_NAME'] ?? 'index.php', $forLang);
}

/** Skratky dní v poradí pondelok - nedeľa (kľúče OPENING_HOURS). */
function dayAbbreviations(): array
{
    return ['Po', 'Ut', 'St', 'Št', 'Pi', 'So', 'Ne'];
}

function formatHours(?array $hours): string
{
    return $hours ? $hours[0] . ' – ' . $hours[1] : t('Zatvorené');
}

/** Otváracie hodiny pre deň v týždni (1 = pondelok ... 7 = nedeľa). */
function hoursForDay(int $isoDay): ?array
{
    return array_values(OPENING_HOURS)[$isoDay - 1] ?? null;
}

function isOpenNow(): bool
{
    $hours = hoursForDay((int) date('N'));
    if (!$hours) {
        return false;
    }
    $now = date('H:i');
    return $now >= $hours[0] && $now < $hours[1];
}

/** Kedy najbližšie otvárame, napr. "o 10:00" alebo "v utorok o 10:00". */
function nextOpeningLabel(): string
{
    $today = (int) date('N');
    $hours = hoursForDay($today);
    if ($hours && date('H:i') < $hours[0]) {
        return sprintf(t('o %s'), $hours[0]);
    }
    $onDay = ['v pondelok', 'v utorok', 'v stredu', 'vo štvrtok', 'v piatok', 'v sobotu', 'v nedeľu'];
    for ($i = 1; $i <= 7; $i++) {
        $day = ($today + $i - 1) % 7 + 1;
        $hours = hoursForDay($day);
        if ($hours) {
            return $i === 1
                ? sprintf(t('zajtra o %s'), $hours[0])
                : sprintf(t('%s o %s'), t($onDay[$day - 1]), $hours[0]);
        }
    }
    return '';
}

/**
 * Súhrn otváracích hodín - dni s rovnakými hodinami sa zlúčia,
 * napr. "Denne 10:00 – 22:00" alebo "Po – Pi 10:00 – 22:00, So – Ne 11:00 – 23:00".
 */
function openingHoursSummary(): string
{
    $values = array_values(OPENING_HOURS);
    if (count(array_unique(array_map('serialize', $values))) === 1) {
        return $values[0] ? sprintf(t('Denne %s'), formatHours($values[0])) : t('Zatvorené');
    }

    $abbr = array_map('t', dayAbbreviations());
    $parts = [];
    $start = 0;
    for ($i = 1; $i <= count($values); $i++) {
        if ($i === count($values) || $values[$i] !== $values[$start]) {
            $range = $start === $i - 1 ? $abbr[$start] : $abbr[$start] . ' – ' . $abbr[$i - 1];
            $parts[] = $range . ' ' . formatHours($values[$start]);
            $start = $i;
        }
    }
    return implode(', ', $parts);
}

/**
 * Štruktúrované dáta Schema.org (JSON-LD) o reštaurácii pre Google -
 * adresa, telefón, otváracie hodiny, menu a oblasť rozvozu.
 */
function restaurantSchema(): string
{
    $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $openingHours = [];
    foreach (array_values(OPENING_HOURS) as $i => $hours) {
        if ($hours) {
            $openingHours[] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $dayNames[$i],
                'opens' => $hours[0],
                'closes' => $hours[1],
            ];
        }
    }

    $sameAs = array_values(array_filter([SOCIAL_FACEBOOK, SOCIAL_INSTAGRAM]));

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Restaurant',
        'name' => SITE_NAME,
        'description' => t(SITE_CLAIM),
        'inLanguage' => lang(),
        'url' => SITE_URL . '/',
        'image' => SITE_URL . '/assets/img/og-image.jpg',
        'logo' => SITE_URL . '/assets/img/logo_tomino_b.png',
        'telephone' => SITE_PHONE_TEL,
        'email' => SITE_EMAIL,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => SITE_ADDRESS_STREET,
            'postalCode' => SITE_ADDRESS_ZIP,
            'addressLocality' => SITE_ADDRESS_TOWN,
            'addressCountry' => 'SK',
        ],
        'servesCuisine' => ['Pizza', 'Neapolská pizza', 'Talianska kuchyňa', 'Burgery', 'Kebab'],
        'menu' => SITE_URL . '/menu',
        'hasMenu' => SITE_URL . '/menu',
        'priceRange' => '€',
        'paymentAccepted' => 'Hotovosť, platobná karta',
        'currenciesAccepted' => 'EUR',
        'areaServed' => DELIVERY_TOWNS,
        'openingHoursSpecification' => $openingHours,
    ];
    if ($sameAs) {
        $schema['sameAs'] = $sameAs;
    }

    return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
}

/** Zakóduje hlavičku e-mailu s diakritikou (RFC 2047), aby sa nerozsypala. */
function encodeMailHeader(string $value): string
{
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

/**
 * Fotka v mriežke galérie, ktorá sa po kliknutí otvorí v lightboxe
 * (includes/lightbox.php). Fotku zároveň pridá do $lightboxPhotos.
 */
function galleryFigure(string $name, string $label, array &$lightboxPhotos): string
{
    $label = t($label);
    $index = count($lightboxPhotos);
    $lightboxPhotos[] = ['full' => photo($name), 'label' => $label];

    return sprintf(
        '<figure class="gallery-item"><button type="button" class="gallery-item__btn" data-gallery-open data-index="%d" aria-label="' . e(t('Zväčšiť')) . ': %s">' .
        '<img src="%s" alt="%2$s - Pizzeria Tominno" loading="lazy"><span class="gallery-item__zoom">%s</span></button>' .
        '<figcaption>%2$s</figcaption></figure>',
        $index,
        e($label),
        e(photo($name, true)),
        icon('zoom-in')
    );
}

/* -------------------------------------------------------------------------
 * Jazykové verzie - slovenčina (predvolená, bez prefixu) a maďarčina (/hu/).
 * Texty v šablónach sú po slovensky a prekladajú sa cez t()/h() podľa
 * slovníka v data/lang-hu.php (kľúč = slovenský text). Chýbajúci preklad
 * sa zobrazí po slovensky.
 * ---------------------------------------------------------------------- */

const LANGUAGES = ['sk' => 'Slovensky', 'hu' => 'Magyarul'];

/** Aktuálny jazyk - podľa prefixu adresy (/hu/...), pri formulároch podľa POST. */
function lang(?string $set = null): string
{
    static $lang = null;
    if ($set !== null) {
        $lang = isset(LANGUAGES[$set]) ? $set : 'sk';
    }
    if ($lang === null) {
        $path = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $lang = preg_match('#^/hu(/|$)#', $path) ? 'hu' : 'sk';
    }
    return $lang;
}

function translations(): array
{
    static $dict = null;
    if ($dict === null) {
        $dict = require __DIR__ . '/../data/lang-hu.php';
    }
    return $dict;
}

/** Preložený text (neescapovaný - na výpis do HTML použi h() alebo e(t())). */
function t(string $text): string
{
    if (lang() === 'sk' || $text === '') {
        return $text;
    }
    return translations()[$text] ?? $text;
}

/**
 * Preložený a escapovaný text pre HTML. Voliteľné argumenty sa dosadia za %s
 * a vložia sa bez escapovania - môžu obsahovať HTML (napr. odkaz na telefón).
 */
function h(string $text, string ...$htmlArgs): string
{
    $out = e(t($text));
    return $htmlArgs ? vsprintf($out, $htmlArgs) : $out;
}

/**
 * Preklad zoznamu surovín (napr. "šunka, kukurica, oregano") po položkách,
 * aby sa v slovníku nemuseli opakovať celé popisy jedál.
 */
function tList(string $text): string
{
    if (lang() === 'sk' || $text === '') {
        return $text;
    }
    $parts = preg_split('/(, | \+ )/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$text];
    $out = '';
    foreach ($parts as $i => $part) {
        $out .= $i % 2 ? $part : t($part);
    }
    return $out;
}

/** Interná adresa v aktuálnom (alebo zadanom) jazyku: url('/menu') -> /hu/menu. */
function url(string $path, ?string $forLang = null): string
{
    $forLang = $forLang ?? lang();
    if ($forLang === 'sk') {
        return $path;
    }
    return '/' . $forLang . ($path === '/' ? '/' : $path);
}

/** Jedálny lístok na stiahnutie (PDF) v aktuálnom jazyku - generuje tools/menu-pdf.ps1. */
function menuPdfUrl(): string
{
    return '/assets/menu/pizzeria-tominno-menu-' . lang() . '.pdf';
}
