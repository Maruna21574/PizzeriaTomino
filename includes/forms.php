<?php
/**
 * Spoločná logika webových formulárov (kontakt, dopyt na oslavu/akciu):
 * ochrana proti spamu, hlásenia pre návštevníka a odoslanie e-mailu.
 *
 * Ochrana proti spamu (bez captchy, návštevník nič nevypĺňa navyše):
 *  1. Honeypot - skryté pole "website", ktoré vyplnia iba roboty.
 *  2. Časový token - podpísaný čas zobrazenia formulára. Formulár odoslaný
 *     rýchlejšie než FORM_MIN_SECONDS (robot), s neplatným podpisom
 *     (podvrhnutý formulár) alebo po FORM_MAX_AGE (starý) sa odmietne.
 *  3. Limit odoslaní - najviac FORM_RATE_LIMIT správ za hodinu z jednej IP.
 *  4. Kontrola obsahu - priveľa odkazov, odkaz v mene, príliš dlhé texty.
 */

const FORM_MIN_SECONDS = 3;
const FORM_MAX_AGE = 4 * 3600;
const FORM_RATE_LIMIT = 5;
const FORM_MAX_LINKS = 2;

/** Formuláre webu: kam sa vracia návštevník a predmet e-mailu. */
function formDefinitions(): array
{
    return [
        'kontakt' => ['page' => '/kontakt', 'subject' => 'Nová správa z kontaktného formulára'],
        'akcie'   => ['page' => '/oslavy-a-akcie', 'subject' => 'Dopyt na oslavu / firemnú akciu'],
    ];
}

/** Typy akcií vo formulári dopytu (oslavy-a-akcie.php). */
function eventTypes(): array
{
    return [
        'narodeniny' => 'Narodeninová oslava',
        'rodinna'    => 'Rodinná oslava',
        'firemna'    => 'Firemná akcia / večierok',
        'detska'     => 'Detská oslava',
        'party-misy' => 'Párty misy na objednávku',
        'ine'        => 'Iné',
    ];
}

/**
 * Priečinok storage/ (mimo dosahu z webu vďaka .htaccess) - tajný kľúč
 * na podpis tokenov a záznamy pre limit odoslaní.
 */
function storagePath(string $file = ''): string
{
    return __DIR__ . '/../storage' . ($file !== '' ? '/' . $file : '');
}

function formSecret(): string
{
    static $secret = null;
    if ($secret !== null) {
        return $secret;
    }
    $path = storagePath('secret.key');
    $secret = is_file($path) ? trim((string) file_get_contents($path)) : '';
    if (strlen($secret) < 32) {
        $secret = bin2hex(random_bytes(32));
        @file_put_contents($path, $secret, LOCK_EX);
    }
    return $secret;
}

function formToken(string $formId, int $time): string
{
    return $time . '.' . hash_hmac('sha256', $formId . '|' . $time, formSecret());
}

/** Skryté polia ochrany proti spamu - vložiť do každého <form>. */
function antiSpamFields(string $formId): string
{
    return sprintf(
        '<input type="hidden" name="form" value="%s">' .
        '<input type="hidden" name="form_token" value="%s">' .
        '<input type="hidden" name="lang" value="%s">' .
        '<div class="hp-field" aria-hidden="true"><label for="website-%1$s">' . e(t('Nevypĺňajte')) . '</label>' .
        '<input type="text" id="website-%1$s" name="website" tabindex="-1" autocomplete="off"></div>',
        e($formId),
        e(formToken($formId, time())),
        e(lang())
    );
}

/**
 * Overí odoslaný formulár. Vráti null, ak je v poriadku, inak kód:
 * 'spam' (tichý odchod - robot sa nedozvie, že ho sme odhalili),
 * 'expired' (formulár bol otvorený pridlho) alebo 'ratelimit'.
 */
function antiSpamCheck(string $formId, array $post): ?string
{
    if (!empty($post['website'])) {
        return 'spam';
    }

    $token = (string) ($post['form_token'] ?? '');
    $time = (int) strtok($token, '.');
    if ($time <= 0 || !hash_equals(formToken($formId, $time), $token)) {
        return 'spam';
    }
    $age = time() - $time;
    if ($age < FORM_MIN_SECONDS) {
        return 'spam';
    }
    if ($age > FORM_MAX_AGE) {
        return 'expired';
    }

    $text = implode(' ', array_map('strval', array_filter($post, 'is_string')));
    if (preg_match_all('~https?://|www\.|\[url~i', $text) > FORM_MAX_LINKS) {
        return 'spam';
    }
    if (preg_match('~https?://|www\.|<a\s~i', (string) ($post['name'] ?? ''))) {
        return 'spam';
    }

    if (rateLimitExceeded()) {
        return 'ratelimit';
    }

    return null;
}

/** Zaznamená odoslanie a zistí, či IP neprekročila limit za poslednú hodinu. */
function rateLimitExceeded(): bool
{
    $dir = storagePath('ratelimit');
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $file = $dir . '/' . hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . formSecret()) . '.json';

    $now = time();
    $hits = is_file($file) ? (array) json_decode((string) file_get_contents($file), true) : [];
    $hits = array_values(array_filter($hits, function ($t) use ($now) {
        return is_int($t) && $t > $now - 3600;
    }));

    if (count($hits) >= FORM_RATE_LIMIT) {
        return true;
    }
    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);

    // Občas upraceme staré záznamy iných IP adries.
    if (random_int(1, 50) === 1) {
        foreach (glob($dir . '/*.json') ?: [] as $old) {
            if (filemtime($old) < $now - 86400) {
                @unlink($old);
            }
        }
    }
    return false;
}

/** Text z formulára - orezaný, bez riadiacich znakov, s maximálnou dĺžkou. */
function formText(array $post, string $key, int $maxLength, bool $multiline = false): string
{
    $value = trim((string) ($post[$key] ?? ''));
    $value = preg_replace($multiline ? '/[^\P{C}\n\t]/u' : '/\p{C}/u', '', $value) ?? '';
    return function_exists('mb_substr') ? mb_substr($value, 0, $maxLength, 'UTF-8') : substr($value, 0, $maxLength);
}

/** Odošle e-mail prevádzke (predmet s diakritikou je správne zakódovaný). */
function sendSiteMail(string $subject, string $body, string $replyTo = ''): bool
{
    $headers = [
        'MIME-Version: 1.0',
        'From: ' . SITE_NAME . ' <noreply@' . SITE_DOMAIN . '>',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
    ];
    if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    return mail(SITE_EMAIL, encodeMailHeader($subject . ' - ' . SITE_NAME), $body, implode("\r\n", $headers));
}

/** Hlásenie nad formulárom podľa výsledku odoslania (?sent=1 / ?error=...). */
function formAlert(string $invalidMessage): string
{
    if (($_GET['sent'] ?? '') === '1') {
        return '<div class="alert alert--success" role="status">' . h('Ďakujeme, vaša správa bola odoslaná. Ozveme sa vám čo najskôr.') . '</div>';
    }

    $phone = '<a href="tel:' . e(SITE_PHONE_TEL) . '">' . e(SITE_PHONE) . '</a>';
    $messages = [
        'invalid'   => h($invalidMessage),
        'mail'      => h('Správu sa nepodarilo odoslať. Skúste to prosím neskôr, alebo nám zavolajte na %s.', $phone),
        'expired'   => h('Formulár bol otvorený príliš dlho. Obnovte prosím stránku a odošlite ho znova.'),
        'ratelimit' => h('Z vášho pripojenia sme prijali priveľa správ. Skúste to prosím o hodinu, alebo nám zavolajte na %s.', $phone),
    ];
    $error = (string) ($_GET['error'] ?? '');
    if (!isset($messages[$error])) {
        return '';
    }
    return '<div class="alert alert--error" role="alert">' . $messages[$error] . '</div>';
}
