<?php
/**
 * Úprava / pridanie kategórie jedálneho lístka (?p=category&id=<id> alebo id=new).
 */

$icons = [
    'pizza' => 'Pizza', 'burger' => 'Burger', 'flame' => 'Gril / oheň', 'salad' => 'Šalát',
    'fries' => 'Hranolky', 'garlic' => 'Pečivo', 'cake' => 'Dezert', 'cup' => 'Nápoj',
    'pasta' => 'Cestoviny', 'heart' => 'Srdce', 'leaf' => 'List', 'star' => 'Hviezda',
];

$menu = menuContent();
$id = (string) ($_GET['id'] ?? '');
$isNew = $id === 'new';
$index = null;
foreach ($menu as $ci => $row) {
    if ($row['id'] === $id) {
        $index = $ci;
    }
}
if (!$isNew && $index === null) {
    flash('Kategória sa nenašla.', 'error');
    adminRedirect('menu');
}

$category = $isNew ? ['id' => '', 'key' => '', 'label' => '', 'icon' => 'pizza', 'items' => []] : $menu[$index];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPhoto = $category['photo'] ?? '';
    $warnings = [];

    foreach (['label' => 80, 'label_hu' => 80, 'subtitle' => 80, 'subtitle_hu' => 80] as $field => $max) {
        $category[$field] = postText($field, $max);
    }
    $category['note'] = postText('note', 500);
    $category['note_hu'] = postText('note_hu', 500);
    $category['icon'] = isset($icons[$_POST['icon'] ?? '']) ? $_POST['icon'] : 'pizza';
    $category['hidden'] = !empty($_POST['hidden']);

    if ($category['label'] === '') {
        $errors[] = 'Vyplňte názov kategórie.';
    }

    if (!$errors) {
        if ($isNew) {
            // Kľúč sa použije v adrese (menu#cat-kluc) - po vytvorení sa už nemení.
            $key = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $category['label']) ?: '')), '-') ?: 'kategoria';
            $existing = array_column($menu, 'key');
            $base = $key;
            for ($n = 2; in_array($key, $existing, true); $n++) {
                $key = $base . '-' . $n;
            }
            $category['key'] = $key;
            $category['id'] = $key;
        }

        $uploaded = processUploadedPhotos('photo', $warnings);
        if ($uploaded) {
            $category['photo'] = $uploaded[0];
        }
        foreach (['label_hu', 'subtitle', 'subtitle_hu', 'note', 'note_hu'] as $optional) {
            if ($category[$optional] === '') {
                unset($category[$optional]);
            }
        }

        if ($isNew) {
            $menu[] = $category;
        } else {
            $menu[$index] = $category;
        }
        if (adminSave('menu', $menu, 'Kategória „' . $category['label'] . '“ bola uložená.') && $oldPhoto !== ($category['photo'] ?? '')) {
            deletePhotoIfUnused($oldPhoto);
        }
        foreach ($warnings as $warning) {
            flash($warning, 'warning');
        }
        adminRedirect('menu', [], 'r-' . $category['id']);
    }
}

adminHeader($isNew ? 'Nová kategória' : $category['label'], 'menu');
?>
<p><a href="<?= e(adminUrl('menu')) ?>">&larr; Späť na jedálny lístok</a></p>
<h1><?= $isNew ? 'Nová kategória' : e($category['label']) ?></h1>

<?php foreach ($errors as $error): ?>
<div class="admin-alert admin-alert--error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data" action="<?= e(adminUrl('category', ['id' => $isNew ? 'new' : $category['id']])) ?>" class="admin-form">
  <?= csrfField() ?>

  <section class="admin-card">
    <h2>Názov a texty</h2>
    <div class="admin-grid">
      <div class="admin-field">
        <label for="label">Názov kategórie *</label>
        <input type="text" id="label" name="label" required maxlength="80" value="<?= e($category['label']) ?>">
      </div>
      <div class="admin-field">
        <label for="label_hu">Názov po maďarsky</label>
        <input type="text" id="label_hu" name="label_hu" maxlength="80" value="<?= e($category['label_hu'] ?? '') ?>" placeholder="<?= e(translations()[$category['label']] ?? '') ?>">
      </div>
      <div class="admin-field">
        <label for="subtitle">Podnadpis <span class="admin-muted">(nepovinné, napr. „Pečená v peci na drevo“)</span></label>
        <input type="text" id="subtitle" name="subtitle" maxlength="80" value="<?= e($category['subtitle'] ?? '') ?>">
      </div>
      <div class="admin-field">
        <label for="subtitle_hu">Podnadpis po maďarsky</label>
        <input type="text" id="subtitle_hu" name="subtitle_hu" maxlength="80" value="<?= e($category['subtitle_hu'] ?? '') ?>" placeholder="<?= e(translations()[$category['subtitle'] ?? ''] ?? '') ?>">
      </div>
      <div class="admin-field">
        <label for="note">Poznámka pod nadpisom <span class="admin-muted">(nepovinné)</span></label>
        <textarea id="note" name="note" rows="3" maxlength="500"><?= e($category['note'] ?? '') ?></textarea>
      </div>
      <div class="admin-field">
        <label for="note_hu">Poznámka po maďarsky</label>
        <textarea id="note_hu" name="note_hu" rows="3" maxlength="500" placeholder="<?= e(translations()[$category['note'] ?? ''] ?? '') ?>"><?= e($category['note_hu'] ?? '') ?></textarea>
      </div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Ikona a fotka</h2>
    <div class="admin-field">
      <span class="admin-label">Ikona v zozname kategórií</span>
      <div class="admin-icon-choice">
        <?php foreach ($icons as $value => $label): ?>
        <label title="<?= e($label) ?>"><input type="radio" name="icon" value="<?= e($value) ?>"<?= ($category['icon'] ?? '') === $value ? ' checked' : '' ?>><span><?= icon($value) ?></span></label>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="admin-photo-field">
      <?= adminThumb($category['photo'] ?? '', 'admin-thumb admin-thumb--lg admin-thumb--wide') ?>
      <div>
        <label for="photo">Fotka v hlavičke kategórie</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" data-preview>
        <p class="admin-muted">Najlepšie široká (na šírku) fotka. Zmenší sa automaticky.</p>
      </div>
    </div>
  </section>

  <section class="admin-card">
    <label class="admin-check"><input type="checkbox" name="hidden" value="1"<?= !empty($category['hidden']) ? ' checked' : '' ?>> Skryť celú kategóriu z webu</label>
  </section>

  <div class="admin-savebar">
    <button type="submit" class="admin-btn admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť</button>
    <a class="admin-btn admin-btn--ghost" href="<?= e(adminUrl('menu')) ?>">Zrušiť</a>
  </div>
</form>
<?php
adminFooter();
