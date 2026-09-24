<?php
/**
 * Obsah webu upravovaný v administrácii (menu, galéria, recenzie, nastavenia).
 *
 * Ukladá sa ako JSON do storage/content/<názov>.json. Kým administrácia
 * nič neuloží, použijú sa východiskové dáta z data/*.php. Pri každom
 * uložení sa predchádzajúca verzia odloží do storage/backup/ (posledných 30).
 */

const CONTENT_BACKUPS_KEEP = 30;

function contentFile(string $name): string
{
    return __DIR__ . '/../storage/content/' . $name . '.json';
}

/** Načíta obsah; ak ešte nie je uložený, vráti východiskové dáta zo $seed(). */
function contentLoad(string $name, callable $seed): array
{
    $file = contentFile($name);
    if (is_file($file)) {
        $data = json_decode((string) file_get_contents($file), true);
        if (is_array($data)) {
            return $data;
        }
    }
    return $seed();
}

/** Uloží obsah (atomicky, so zálohou predchádzajúcej verzie). */
function contentSave(string $name, array $data): bool
{
    $file = contentFile($name);
    $dir = dirname($file);
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return false;
    }

    if (is_file($file)) {
        $backupDir = __DIR__ . '/../storage/backup';
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }
        @copy($file, $backupDir . '/' . $name . '-' . date('Ymd-His') . '.json');
        $backups = glob($backupDir . '/' . $name . '-*.json') ?: [];
        sort($backups);
        foreach (array_slice($backups, 0, max(0, count($backups) - CONTENT_BACKUPS_KEEP)) as $old) {
            @unlink($old);
        }
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    if ($json === false || file_put_contents($tmp, $json, LOCK_EX) === false) {
        @unlink($tmp);
        return false;
    }
    return rename($tmp, $file);
}

/** Čas poslednej zmeny obsahu (pre obnovu PDF menu a pod.). */
function contentModified(string $name): int
{
    $file = contentFile($name);
    return is_file($file) ? (int) filemtime($file) : 0;
}

function newContentId(): string
{
    return bin2hex(random_bytes(5));
}

/* -------------------------------------------------------------------------
 * Jedálny lístok
 * ---------------------------------------------------------------------- */

/**
 * Menu ako zoznam kategórií: [key, label, label_hu, subtitle..., items: [...]].
 * Položky majú stabilné 'id' (pre administráciu) a voliteľne 'hidden'
 * (dočasne nedostupné) a 'featured' (zobraziť na úvodnej stránke).
 */
function menuContent(): array
{
    return contentLoad('menu', function () {
        $featured = ['Margherita', 'Kukuricová', 'Gorgonzola'];
        $categories = [];
        foreach (require __DIR__ . '/../data/menu.php' as $key => $category) {
            $category['key'] = $key;
            $category['id'] = $key;
            foreach ($category['items'] as $i => $item) {
                $item['id'] = $key . '-' . ($i + 1);
                $item['featured'] = $key === 'pizza' && in_array($item['name'], $featured, true);
                $category['items'][$i] = $item;
            }
            $categories[] = $category;
        }
        return $categories;
    });
}

/* -------------------------------------------------------------------------
 * Galéria
 * ---------------------------------------------------------------------- */

/**
 * Galéria ako zoznam sekcií: [id, title, title_hu, on_events, photos: [
 *   [id, file, label, label_hu, home]]]. 'file' je názov fotky v
 * assets/img/foto (bez prípony), 'home' = zobraziť na úvodnej stránke,
 * 'on_events' = fotky sekcie sa zobrazia na stránke Oslavy a akcie.
 */
function galleryContent(): array
{
    return contentLoad('gallery', function () {
        $sections = [];
        foreach (require __DIR__ . '/../data/gallery.php' as $section) {
            $section['id'] = newContentId();
            foreach ($section['photos'] as $i => $photo) {
                $section['photos'][$i] = $photo + ['id' => newContentId(), 'label_hu' => '', 'home' => false];
            }
            $sections[] = $section + ['title_hu' => '', 'on_events' => false];
        }
        return $sections;
    });
}

/* -------------------------------------------------------------------------
 * Recenzie
 * ---------------------------------------------------------------------- */

function reviewsContent(): array
{
    return contentLoad('reviews', function () {
        return array_map(function ($review) {
            return $review + ['id' => newContentId()];
        }, require __DIR__ . '/../data/reviews.php');
    });
}
