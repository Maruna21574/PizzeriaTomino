<?php
/**
 * Spracovanie fotiek nahraných v administrácii.
 *
 * Každá fotka sa overí (skutočne ide o obrázok JPG/PNG/WebP), otočí podľa
 * EXIF (fotky z mobilu), zmenší a znovu uloží ako JPG v dvoch veľkostiach:
 *   assets/img/foto/<názov>.jpg        - max 1400 px (lightbox, veľké plochy)
 *   assets/img/foto/thumb/<názov>.jpg  - max 640 px (náhľady, karty)
 * Znovu uloženie odstráni metadáta (GPS z mobilu) aj prípadný škodlivý obsah.
 * Nahrané fotky majú názov s predponou "u-" - len tie administrácia maže.
 */

const IMAGE_MAX_UPLOAD_MB = 20;
const IMAGE_FULL_SIZE = 1400;
const IMAGE_THUMB_SIZE = 640;

function photoDir(bool $thumb = false): string
{
    return __DIR__ . '/../../assets/img/foto' . ($thumb ? '/thumb' : '');
}

/**
 * Premení $_FILES['x'] (aj pri multiple) na zoznam jednotlivých súborov
 * a vynechá prázdne polia (nič nevybrané).
 */
function uploadedFiles(string $field): array
{
    if (empty($_FILES[$field])) {
        return [];
    }
    $f = $_FILES[$field];
    if (!is_array($f['name'])) {
        return $f['error'] === UPLOAD_ERR_NO_FILE ? [] : [$f];
    }
    $files = [];
    foreach ($f['name'] as $i => $name) {
        if ($f['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        $files[] = [
            'name' => $name,
            'tmp_name' => $f['tmp_name'][$i],
            'error' => $f['error'][$i],
            'size' => $f['size'][$i],
        ];
    }
    return $files;
}

/**
 * Spracuje jednu nahranú fotku a vráti jej názov (bez prípony).
 * Pri chybe vyhodí RuntimeException so zrozumiteľnou správou pre klienta.
 */
function processUploadedPhoto(array $file): string
{
    $label = '„' . ($file['name'] ?? 'fotka') . '“';

    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        throw new RuntimeException("Fotka $label je príliš veľká pre server (limit " . ini_get('upload_max_filesize') . ').');
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException("Fotku $label sa nepodarilo nahrať (chyba " . (int) $file['error'] . ').');
    }
    if ($file['size'] > IMAGE_MAX_UPLOAD_MB * 1024 * 1024) {
        throw new RuntimeException("Fotka $label má viac ako " . IMAGE_MAX_UPLOAD_MB . ' MB.');
    }

    $info = @getimagesize($file['tmp_name']);
    $type = $info[2] ?? 0;
    $loaders = [
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG  => 'imagecreatefrompng',
        IMAGETYPE_WEBP => 'imagecreatefromwebp',
    ];
    if (!isset($loaders[$type]) || !function_exists($loaders[$type])) {
        throw new RuntimeException("Súbor $label nie je fotka vo formáte JPG, PNG alebo WebP. (Fotky z iPhonu vo formáte HEIC najprv uložte ako JPG.)");
    }
    if ($info[0] * $info[1] > 40000000) {
        throw new RuntimeException("Fotka $label má príliš veľké rozlíšenie (max. 40 megapixelov).");
    }

    @ini_set('memory_limit', '512M');
    $image = @$loaders[$type]($file['tmp_name']);
    if (!$image) {
        throw new RuntimeException("Fotku $label sa nepodarilo otvoriť - súbor je zrejme poškodený.");
    }

    // Fotky z mobilu majú otočenie uložené v EXIF - otočíme ich natrvalo.
    if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($file['tmp_name']);
        $rotate = [3 => 180, 6 => -90, 8 => 90][$exif['Orientation'] ?? 1] ?? 0;
        if ($rotate) {
            $rotated = imagerotate($image, $rotate, 0);
            if ($rotated) {
                imagedestroy($image);
                $image = $rotated;
            }
        }
    }

    $name = 'u-' . date('ymd') . '-' . bin2hex(random_bytes(4));
    foreach ([false => IMAGE_FULL_SIZE, true => IMAGE_THUMB_SIZE] as $thumb => $maxSize) {
        $dir = photoDir((bool) $thumb);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $resized = resizeImage($image, $maxSize);
        $ok = imagejpeg($resized, $dir . '/' . $name . '.jpg', $thumb ? 78 : 82);
        imagedestroy($resized);
        if (!$ok) {
            imagedestroy($image);
            throw new RuntimeException('Fotku sa nepodarilo uložiť - priečinok assets/img/foto nie je zapisovateľný.');
        }
    }
    imagedestroy($image);

    return $name;
}

/** Zmenší obrázok tak, aby dlhšia strana mala najviac $maxSize px (na bielom pozadí). */
function resizeImage($image, int $maxSize)
{
    $w = imagesx($image);
    $h = imagesy($image);
    $scale = min(1, $maxSize / max($w, $h));
    $nw = max(1, (int) round($w * $scale));
    $nh = max(1, (int) round($h * $scale));

    $out = imagecreatetruecolor($nw, $nh);
    imagefill($out, 0, 0, imagecolorallocate($out, 255, 255, 255)); // priehľadné PNG -> biele pozadie
    imagecopyresampled($out, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
    return $out;
}

/** Spracuje všetky fotky z poľa formulára; chyby pridá do $errors. */
function processUploadedPhotos(string $field, array &$errors): array
{
    $names = [];
    foreach (uploadedFiles($field) as $file) {
        try {
            $names[] = processUploadedPhoto($file);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    return $names;
}

/** Je fotka ešte niekde použitá (menu, kategória, galéria)? */
function photoInUse(string $name): bool
{
    foreach (menuContent() as $category) {
        if (($category['photo'] ?? '') === $name) {
            return true;
        }
        foreach ($category['items'] as $item) {
            if (($item['photo'] ?? '') === $name) {
                return true;
            }
        }
    }
    foreach (galleryContent() as $section) {
        foreach ($section['photos'] as $photo) {
            if ($photo['file'] === $name) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Zmaže súbory fotky nahranej cez administráciu, ak už nie je nikde použitá.
 * Volať AŽ PO uložení obsahu, z ktorého bola fotka odstránená.
 */
function deletePhotoIfUnused(string $name): void
{
    if ($name === '' || strpos($name, 'u-') !== 0 || !preg_match('/^u-[a-z0-9-]+$/', $name) || photoInUse($name)) {
        return;
    }
    @unlink(photoDir() . '/' . $name . '.jpg');
    @unlink(photoDir(true) . '/' . $name . '.jpg');
}
