<?php
/**
 * Jadro administrácie (/admin): session, prihlásenie, CSRF ochrana,
 * hlásenia po uložení a pomocné funkcie pre formuláre.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../icons.php';
require_once __DIR__ . '/images.php';

const ADMIN_SESSION_TIMEOUT = 12 * 3600;
const ADMIN_LOGIN_MAX_FAILS = 8;
const ADMIN_LOGIN_WINDOW = 15 * 60;
const ADMIN_PASSWORD_MIN_LENGTH = 10;

// Administrácia sa nikdy neindexuje a neukladá do cache.
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');

function adminStartSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('tominno_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/admin',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();

    if (!empty($_SESSION['admin']) && time() - ($_SESSION['last_activity'] ?? 0) > ADMIN_SESSION_TIMEOUT) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    $_SESSION['last_activity'] = time();
}

function adminIsLoggedIn(): bool
{
    return !empty($_SESSION['admin']);
}

/* ------------------------------------------------------------------ Heslo */

function adminPasswordFile(): string
{
    return __DIR__ . '/../../storage/admin.json';
}

/** Hash hesla - z storage/admin.json (po zmene hesla), inak počiatočné z configu. */
function adminPasswordHash(): string
{
    $file = adminPasswordFile();
    if (is_file($file)) {
        $data = json_decode((string) file_get_contents($file), true);
        if (!empty($data['password_hash'])) {
            return $data['password_hash'];
        }
    }
    return ADMIN_PASSWORD_HASH;
}

function adminUsesInitialPassword(): bool
{
    return !is_file(adminPasswordFile());
}

function adminSetPassword(string $password): bool
{
    $dir = dirname(adminPasswordFile());
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $json = json_encode(['password_hash' => password_hash($password, PASSWORD_DEFAULT), 'changed' => date('c')]);
    return file_put_contents(adminPasswordFile(), $json, LOCK_EX) !== false;
}

/* ------------------------------------------------------------------ Ochrana prihlásenia */

function adminLoginAttemptsFile(): string
{
    $dir = __DIR__ . '/../../storage/ratelimit';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir . '/login-' . hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . 'admin') . '.json';
}

/** Neúspešné pokusy o prihlásenie z tejto IP za posledných 15 minút. */
function adminLoginFailures(): array
{
    $file = adminLoginAttemptsFile();
    $hits = is_file($file) ? (array) json_decode((string) file_get_contents($file), true) : [];
    return array_values(array_filter($hits, function ($t) {
        return is_int($t) && $t > time() - ADMIN_LOGIN_WINDOW;
    }));
}

function adminLoginBlocked(): bool
{
    return count(adminLoginFailures()) >= ADMIN_LOGIN_MAX_FAILS;
}

function adminRecordLoginFailure(): void
{
    $hits = adminLoginFailures();
    $hits[] = time();
    @file_put_contents(adminLoginAttemptsFile(), json_encode($hits), LOCK_EX);
}

function adminLogin(string $password): bool
{
    if (!password_verify($password, adminPasswordHash())) {
        adminRecordLoginFailure();
        return false;
    }
    @unlink(adminLoginAttemptsFile());
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return true;
}

function adminLogout(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
    session_destroy();
}

/* ------------------------------------------------------------------ CSRF */

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

/** Každý POST v administrácii musí mať platný CSRF token. */
function adminVerifyCsrf(): void
{
    if (!hash_equals(csrfToken(), (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(400);
        exit('Neplatný bezpečnostný token formulára. Obnovte stránku a skúste to znova.');
    }
}

/* ------------------------------------------------------------------ Hlásenia a presmerovanie */

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function takeFlashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/** Presmerovanie v rámci administrácie (vzor Post/Redirect/Get). */
function adminRedirect(string $page, array $params = [], string $anchor = ''): void
{
    $query = http_build_query(['p' => $page] + $params);
    header('Location: /admin/?' . $query . ($anchor !== '' ? '#' . $anchor : ''));
    exit;
}

function adminUrl(string $page, array $params = []): string
{
    return '/admin/?' . http_build_query(['p' => $page] + $params);
}

/* ------------------------------------------------------------------ Vstupy z formulárov */

/** Text z POST - orezaný, bez riadiacich znakov, s maximálnou dĺžkou. */
function postText(string $key, int $maxLength = 500, bool $multiline = false, ?array $source = null): string
{
    $source = $source ?? $_POST;
    $value = trim((string) ($source[$key] ?? ''));
    $value = str_replace("\r\n", "\n", $value);
    $value = preg_replace($multiline ? '/[^\P{C}\n]/u' : '/\p{C}/u', '', $value) ?? '';
    return mb_substr($value, 0, $maxLength, 'UTF-8');
}

/** Cena z formulára - akceptuje "7,90" aj "7.90". Vráti null, ak nie je platná. */
function parsePrice(string $value): ?float
{
    $value = str_replace([' ', '€'], '', str_replace(',', '.', trim($value)));
    if ($value === '' || !preg_match('/^\d{1,4}(\.\d{1,2})?$/', $value)) {
        return null;
    }
    return round((float) $value, 2);
}

/** Posunie prvok poľa s daným id o jedno miesto hore (-1) alebo dole (+1). */
function moveById(array $list, string $id, int $direction): array
{
    $ids = array_column($list, 'id');
    $index = array_search($id, $ids, true);
    $target = $index === false ? false : $index + $direction;
    if ($index === false || $target < 0 || $target >= count($list)) {
        return $list;
    }
    [$list[$index], $list[$target]] = [$list[$target], $list[$index]];
    return array_values($list);
}

/** Uloží obsah a pri chybe zobrazí hlásenie (napr. nezapisovateľný priečinok storage/). */
function adminSave(string $name, array $data, string $successMessage): bool
{
    if (!contentSave($name, $data)) {
        flash('Zmeny sa nepodarilo uložiť - priečinok storage/ nie je zapisovateľný. Kontaktujte správcu webu.', 'error');
        return false;
    }
    flash($successMessage);
    return true;
}
