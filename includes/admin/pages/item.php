<?php
/**
 * Úprava / pridanie položky jedálneho lístka (?p=item&id=<id> alebo id=new&cat=<kategória>).
 */

const ITEM_VARIANT_ROWS = 4;

$menu = menuContent();
$id = (string) ($_GET['id'] ?? '');
$isNew = $id === 'new';

// Nájdi položku a jej kategóriu
$catIndex = null;
$itemIndex = null;
foreach ($menu as $ci => $category) {
    if ($isNew && $category['id'] === ($_GET['cat'] ?? '')) {
        $catIndex = $ci;
    }
    foreach ($category['items'] as $ii => $row) {
        if (!$isNew && $row['id'] === $id) {
            $catIndex = $ci;
            $itemIndex = $ii;
        }
    }
}
if ($catIndex === null) {
    if (!$isNew || !$menu) {
        flash('Položka sa nenašla.', 'error');
        adminRedirect('menu');
    }
    $catIndex = 0;
}

$item = $isNew ? ['id' => newContentId(), 'name' => '', 'desc' => '', 'price' => 0, 'weight' => '', 'allergens' => []]
               : $menu[$catIndex]['items'][$itemIndex];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPhoto = $item['photo'] ?? '';
    $warnings = [];

    $item['name'] = postText('name', 120);
    $item['name_hu'] = postText('name_hu', 120);
    $item['desc'] = postText('desc', 1000);
    $item['desc_hu'] = postText('desc_hu', 1000);
    $item['num'] = (int) ($_POST['num'] ?? 0);
    $item['weight'] = postText('weight', 40);
    $item['allergens'] = array_values(array_intersect(array_keys(getAllergens()), array_map('intval', (array) ($_POST['allergens'] ?? []))));
    $item['hidden'] = !empty($_POST['hidden']);
    $item['featured'] = !empty($_POST['featured']);

    if ($item['name'] === '') {
        $errors[] = 'Vyplňte názov položky.';
    }

    // Varianty (napr. Malý / Veľký) - ak je vyplnený aspoň jeden, nahrádzajú cenu a gramáž.
    $variants = [];
    foreach ((array) ($_POST['variants'] ?? []) as $row) {
        $label = postText('label', 40, false, (array) $row);
        $priceText = postText('price', 20, false, (array) $row);
        if ($label === '' && $priceText === '') {
            continue;
        }
        $price = parsePrice($priceText);
        if ($price === null) {
            $errors[] = 'Cena veľkosti „' . $label . '“ nie je platné číslo (napr. 7,90).';
        }
        $variants[] = ['label' => $label, 'price' => $price];
    }
    if ($variants) {
        $item['variants'] = $variants;
        unset($item['price']);
    } else {
        unset($item['variants']);
        $item['price'] = parsePrice(postText('price', 20));
        if ($item['price'] === null) {
            $errors[] = 'Zadajte platnú cenu (napr. 7,90).';
        }
    }

    if (!$errors) {
        // Fotka - odstránenie alebo nová nahraná (chyba fotky neblokuje uloženie)
        if (!empty($_POST['remove_photo'])) {
            unset($item['photo']);
        }
        $uploaded = processUploadedPhotos('photo', $warnings);
        if ($uploaded) {
            $item['photo'] = $uploaded[0];
        }
        if ($item['featured'] && empty($item['photo'])) {
            $item['featured'] = false;
            $warnings[] = 'Na úvodnej stránke sa zobrazujú len položky s fotkou - najprv nahrajte fotku.';
        }

        // Prázdne voliteľné polia neukladáme
        foreach (['name_hu', 'desc_hu', 'weight', 'num'] as $optional) {
            if (empty($item[$optional])) {
                unset($item[$optional]);
            }
        }

        // Uloženie na pôvodné miesto, alebo na koniec zvolenej kategórie
        $targetIndex = $catIndex;
        foreach ($menu as $ci => $category) {
            if ($category['id'] === ($_POST['category'] ?? '')) {
                $targetIndex = $ci;
            }
        }
        if (!$isNew && $targetIndex === $catIndex) {
            $menu[$catIndex]['items'][$itemIndex] = $item;
        } else {
            if (!$isNew) {
                array_splice($menu[$catIndex]['items'], $itemIndex, 1);
            }
            $menu[$targetIndex]['items'][] = $item;
        }

        $message = $isNew ? 'Položka „' . $item['name'] . '“ bola pridaná.' : 'Zmeny v položke „' . $item['name'] . '“ boli uložené.';
        if (adminSave('menu', $menu, $message) && $oldPhoto !== ($item['photo'] ?? '')) {
            deletePhotoIfUnused($oldPhoto);
        }
        foreach ($warnings as $warning) {
            flash($warning, 'warning');
        }
        if (!empty($_POST['save_stay'])) {
            adminRedirect('item', ['id' => $item['id']]);
        }
        adminRedirect('menu', [], 'r-' . $item['id']);
    }
}

$variantsForm = array_pad($item['variants'] ?? [], ITEM_VARIANT_ROWS, ['label' => '', 'price' => '']);
$priceValue = isset($item['price']) && empty($item['variants']) ? number_format((float) $item['price'], 2, ',', '') : '';

adminHeader($isNew ? 'Nová položka' : $item['name'], 'menu');
?>
<p><a href="<?= e(adminUrl('menu')) ?>#r-<?= e($item['id']) ?>">&larr; Späť na jedálny lístok</a></p>
<h1><?= $isNew ? 'Nová položka' : e($item['name']) ?></h1>

<?php foreach ($errors as $error): ?>
<div class="admin-alert admin-alert--error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data" action="<?= e(adminUrl('item', $isNew ? ['id' => 'new', 'cat' => $menu[$catIndex]['id']] : ['id' => $item['id']])) ?>" class="admin-form">
  <?= csrfField() ?>

  <section class="admin-card">
    <h2>Základné údaje</h2>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field">
        <label for="category">Kategória</label>
        <select id="category" name="category">
          <?php foreach ($menu as $category): ?>
          <option value="<?= e($category['id']) ?>"<?= $category['id'] === $menu[$catIndex]['id'] ? ' selected' : '' ?>><?= e($category['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="admin-field">
        <label for="num">Číslo v menu <span class="admin-muted">(nepovinné, napr. pri pizzi)</span></label>
        <input type="number" id="num" name="num" min="0" max="999" value="<?= !empty($item['num']) ? (int) $item['num'] : '' ?>">
      </div>
    </div>
    <div class="admin-grid">
      <div class="admin-field">
        <label for="name">Názov *</label>
        <input type="text" id="name" name="name" required maxlength="120" value="<?= e($item['name']) ?>">
      </div>
      <div class="admin-field">
        <label for="name_hu">Názov po maďarsky <span class="admin-muted">(nepovinné)</span></label>
        <input type="text" id="name_hu" name="name_hu" maxlength="120" value="<?= e($item['name_hu'] ?? '') ?>" placeholder="<?= e(translations()[$item['name']] ?? '') ?>">
      </div>
      <div class="admin-field">
        <label for="desc">Zloženie / popis</label>
        <textarea id="desc" name="desc" rows="3" maxlength="1000" placeholder="Suroviny oddeľte čiarkou, napr. šunka, kukurica, oregano"><?= e($item['desc'] ?? '') ?></textarea>
      </div>
      <div class="admin-field">
        <label for="desc_hu">Zloženie po maďarsky <span class="admin-muted">(nepovinné - inak sa preloží automaticky zo slovníka surovín)</span></label>
        <textarea id="desc_hu" name="desc_hu" rows="3" maxlength="1000"><?= e($item['desc_hu'] ?? '') ?></textarea>
      </div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Cena</h2>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field">
        <label for="price">Cena (€)</label>
        <input type="text" id="price" name="price" inputmode="decimal" value="<?= e($priceValue) ?>" placeholder="7,90">
      </div>
      <div class="admin-field">
        <label for="weight">Gramáž / množstvo</label>
        <input type="text" id="weight" name="weight" maxlength="40" value="<?= e($item['weight'] ?? '') ?>" placeholder="700 g">
      </div>
    </div>
    <details class="admin-details"<?= !empty($item['variants']) ? ' open' : '' ?>>
      <summary>Viac veľkostí (napr. malý / veľký)</summary>
      <p class="admin-muted">Ak vyplníte veľkosti, zobrazia sa namiesto ceny a gramáže vyššie.</p>
      <?php foreach ($variantsForm as $i => $variant): ?>
      <div class="admin-grid admin-grid--3">
        <div class="admin-field">
          <label for="v<?= $i ?>l">Veľkosť <?= $i + 1 ?></label>
          <input type="text" id="v<?= $i ?>l" name="variants[<?= $i ?>][label]" maxlength="40" value="<?= e($variant['label']) ?>" placeholder="napr. Veľký 2200 g">
        </div>
        <div class="admin-field">
          <label for="v<?= $i ?>p">Cena (€)</label>
          <input type="text" id="v<?= $i ?>p" name="variants[<?= $i ?>][price]" inputmode="decimal" value="<?= is_numeric($variant['price']) ? e(number_format((float) $variant['price'], 2, ',', '')) : '' ?>">
        </div>
      </div>
      <?php endforeach; ?>
    </details>
  </section>

  <section class="admin-card">
    <h2>Alergény</h2>
    <div class="admin-checks">
      <?php foreach (getAllergens() as $code => $label): ?>
      <label class="admin-check"><input type="checkbox" name="allergens[]" value="<?= (int) $code ?>"<?= in_array($code, $item['allergens'] ?? [], true) ? ' checked' : '' ?>> <b><?= (int) $code ?></b> <?= e($label) ?></label>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="admin-card">
    <h2>Fotka</h2>
    <div class="admin-photo-field">
      <?= adminThumb($item['photo'] ?? '', 'admin-thumb admin-thumb--lg') ?>
      <div>
        <label for="photo">Nahrať novú fotku</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" data-preview>
        <p class="admin-muted">JPG, PNG alebo WebP. Fotka sa automaticky zmenší a otočí.</p>
        <?php if (!empty($item['photo'])): ?>
        <label class="admin-check"><input type="checkbox" name="remove_photo" value="1"> Odstrániť fotku</label>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Zobrazenie</h2>
    <label class="admin-check"><input type="checkbox" name="featured" value="1"<?= !empty($item['featured']) ? ' checked' : '' ?>> Zobraziť na úvodnej stránke <span class="admin-muted">(iba s fotkou, najviac 6 položiek)</span></label>
    <label class="admin-check"><input type="checkbox" name="hidden" value="1"<?= !empty($item['hidden']) ? ' checked' : '' ?>> Dočasne nedostupné - skryť z webu</label>
  </section>

  <div class="admin-savebar">
    <button type="submit" class="admin-btn admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť</button>
    <button type="submit" name="save_stay" value="1" class="admin-btn">Uložiť a pokračovať v úpravách</button>
    <a class="admin-btn admin-btn--ghost" href="<?= e(adminUrl('menu')) ?>">Zrušiť</a>
  </div>
</form>
<?php
adminFooter();
