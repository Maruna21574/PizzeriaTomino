<?php
/**
 * Galéria - sekcie a fotky. Každá sekcia má jeden formulár: názov, popisy
 * fotiek, príznak "na úvodnej stránke", nahrávanie nových fotiek a akcie
 * (posun, zmazanie) cez tlačidlá s name="action".
 */

const HOME_PHOTOS_MAX = 6;

$gallery = galleryContent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$action, $target] = array_pad(explode(':', (string) ($_POST['action'] ?? 'save'), 2), 2, '');
    $sectionId = (string) ($_POST['section'] ?? '');
    $removed = [];
    $warnings = [];
    $message = 'Galéria bola uložená.';

    if ($action === 'new_section') {
        $title = postText('new_title', 80);
        if ($title === '') {
            flash('Zadajte názov novej sekcie.', 'error');
            adminRedirect('gallery');
        }
        $newId = newContentId();
        $gallery[] = ['id' => $newId, 'title' => $title, 'title_hu' => postText('new_title_hu', 80), 'on_events' => false, 'photos' => []];
        adminSave('gallery', $gallery, 'Sekcia „' . $title . '“ bola vytvorená - teraz do nej nahrajte fotky.');
        adminRedirect('gallery', [], 's-' . $newId);
    }

    $si = array_search($sectionId, array_column($gallery, 'id'), true);
    if ($si === false) {
        flash('Sekcia sa nenašla.', 'error');
        adminRedirect('gallery');
    }
    $section = $gallery[$si];

    // 1. Uloženie textov sekcie a popisov fotiek
    $section['title'] = postText('title', 80) ?: $section['title'];
    $section['title_hu'] = postText('title_hu', 80);
    $section['on_events'] = !empty($_POST['on_events']);
    $posted = (array) ($_POST['photos'] ?? []);
    foreach ($section['photos'] as $pi => $photo) {
        $row = (array) ($posted[$photo['id']] ?? []);
        $section['photos'][$pi]['label'] = postText('label', 120, false, $row);
        $section['photos'][$pi]['label_hu'] = postText('label_hu', 120, false, $row);
        $section['photos'][$pi]['home'] = !empty($row['home']);
    }

    // 2. Nové fotky
    $newLabel = postText('new_label', 120);
    $newLabelHu = postText('new_label_hu', 120);
    $uploaded = processUploadedPhotos('new_photos', $warnings);
    foreach ($uploaded as $file) {
        $section['photos'][] = ['id' => newContentId(), 'file' => $file, 'label' => $newLabel, 'label_hu' => $newLabelHu, 'home' => false];
    }
    if ($uploaded) {
        $message = 'Nahraných fotiek: ' . count($uploaded) . '.';
    }

    // 3. Akcie s fotkou alebo sekciou
    switch ($action) {
        case 'up':
        case 'down':
            $section['photos'] = moveById($section['photos'], $target, $action === 'up' ? -1 : 1);
            $message = 'Poradie fotiek bolo zmenené.';
            break;
        case 'delete':
            foreach ($section['photos'] as $pi => $photo) {
                if ($photo['id'] === $target) {
                    $removed[] = $photo['file'];
                    array_splice($section['photos'], $pi, 1);
                    $message = 'Fotka bola zmazaná.';
                    break;
                }
            }
            break;
    }

    $gallery[$si] = $section;

    switch ($action) {
        case 'section_up':
        case 'section_down':
            $gallery = moveById($gallery, $sectionId, $action === 'section_up' ? -1 : 1);
            $message = 'Poradie sekcií bolo zmenené.';
            break;
        case 'section_delete':
            $removed = array_merge($removed, array_column($section['photos'], 'file'));
            array_splice($gallery, $si, 1);
            $message = 'Sekcia „' . $section['title'] . '“ bola zmazaná aj s fotkami.';
            break;
    }

    $homeCount = count(array_filter(array_merge(...array_map(function ($s) { return $s['photos']; }, $gallery ?: [['photos' => []]])), function ($p) {
        return !empty($p['home']);
    }));
    if ($homeCount > HOME_PHOTOS_MAX) {
        $warnings[] = 'Na úvodnej stránke je vybraných ' . $homeCount . ' fotiek - zobrazí sa len prvých ' . HOME_PHOTOS_MAX . '.';
    }

    if (adminSave('gallery', $gallery, $message)) {
        foreach ($removed as $file) {
            deletePhotoIfUnused($file);
        }
    }
    foreach ($warnings as $warning) {
        flash($warning, 'warning');
    }
    adminRedirect('gallery', [], $action === 'section_delete' ? '' : 's-' . $sectionId);
}

adminHeader('Galéria', 'gallery');
?>
<h1>Galéria</h1>
<p class="admin-lead">Fotky sú rozdelené do sekcií. Nové fotky nahráte tlačidlom „Vybrať fotky“ v danej sekcii - môžete vybrať aj viac fotiek naraz. Fotky sa automaticky zmenšia a otočia. Nezabudnite na <b>Uložiť zmeny</b>.</p>
<p class="admin-muted">⭐ = fotka sa zobrazuje v ukážke galérie na úvodnej stránke (najviac <?= HOME_PHOTOS_MAX ?>). Fotky zo sekcií označených „na stránke Oslavy“ sa zobrazujú na stránke Oslavy a akcie.</p>

<?php foreach ($gallery as $section): ?>
<form method="post" enctype="multipart/form-data" action="<?= e(adminUrl('gallery')) ?>" class="admin-card admin-gallery-section" id="s-<?= e($section['id']) ?>">
  <?= csrfField() ?>
  <input type="hidden" name="section" value="<?= e($section['id']) ?>">
  <button type="submit" name="action" value="save" class="admin-default-submit" tabindex="-1" aria-hidden="true">Uložiť</button>

  <header class="admin-gallery-section__head">
    <div class="admin-grid admin-grid--3">
      <div class="admin-field">
        <label>Názov sekcie</label>
        <input type="text" name="title" maxlength="80" value="<?= e($section['title']) ?>">
      </div>
      <div class="admin-field">
        <label>Po maďarsky</label>
        <input type="text" name="title_hu" maxlength="80" value="<?= e($section['title_hu'] ?? '') ?>" placeholder="<?= e(translations()[$section['title']] ?? '') ?>">
      </div>
      <label class="admin-check admin-check--inline"><input type="checkbox" name="on_events" value="1"<?= !empty($section['on_events']) ? ' checked' : '' ?>> Zobraziť na stránke Oslavy</label>
    </div>
    <div class="admin-actions">
      <?= adminActionButton('section_up', 'Sekciu hore', 'arrow-up', 'icon-only') ?>
      <?= adminActionButton('section_down', 'Sekciu dole', 'arrow-down', 'icon-only') ?>
      <?= adminActionButton('section_delete', 'Zmazať sekciu', 'trash', 'icon-only admin-btn--danger', 'Naozaj zmazať celú sekciu „' . $section['title'] . '“ aj so všetkými fotkami (' . count($section['photos']) . ')?') ?>
    </div>
  </header>

  <div class="admin-photos">
    <?php foreach ($section['photos'] as $photo): ?>
    <div class="admin-photo">
      <a href="<?= e(photo($photo['file'])) ?>" target="_blank" rel="noopener"><?= adminThumb($photo['file'], 'admin-photo__img') ?></a>
      <input type="text" name="photos[<?= e($photo['id']) ?>][label]" maxlength="120" value="<?= e($photo['label']) ?>" placeholder="Popis fotky" aria-label="Popis fotky">
      <input type="text" name="photos[<?= e($photo['id']) ?>][label_hu]" maxlength="120" value="<?= e($photo['label_hu'] ?? '') ?>" placeholder="<?= e(translations()[$photo['label']] ?? 'Popis po maďarsky') ?>" aria-label="Popis po maďarsky">
      <div class="admin-photo__bar">
        <label class="admin-check" title="Zobraziť na úvodnej stránke"><input type="checkbox" name="photos[<?= e($photo['id']) ?>][home]" value="1"<?= !empty($photo['home']) ? ' checked' : '' ?>> ⭐ úvod</label>
        <span class="admin-actions">
          <?= adminActionButton('up:' . $photo['id'], 'Posunúť skôr', 'arrow-up', 'icon-only') ?>
          <?= adminActionButton('down:' . $photo['id'], 'Posunúť neskôr', 'arrow-down', 'icon-only') ?>
          <?= adminActionButton('delete:' . $photo['id'], 'Zmazať fotku', 'trash', 'icon-only admin-btn--danger', 'Naozaj zmazať túto fotku?') ?>
        </span>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (!$section['photos']): ?>
    <p class="admin-muted">V sekcii zatiaľ nie sú žiadne fotky. Sekcia bez fotiek sa na webe nezobrazí.</p>
    <?php endif; ?>
  </div>

  <div class="admin-upload">
    <div class="admin-field">
      <label>Pridať fotky do sekcie</label>
      <input type="file" name="new_photos[]" accept="image/jpeg,image/png,image/webp" multiple data-preview>
    </div>
    <div class="admin-field">
      <label>Popis nových fotiek <span class="admin-muted">(nepovinné)</span></label>
      <input type="text" name="new_label" maxlength="120" placeholder="napr. Oslava narodenín">
    </div>
    <div class="admin-field">
      <label>Popis po maďarsky</label>
      <input type="text" name="new_label_hu" maxlength="120">
    </div>
  </div>

  <div class="admin-savebar admin-savebar--inline">
    <button type="submit" name="action" value="save" class="admin-btn admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť zmeny / nahrať fotky</button>
  </div>
</form>
<?php endforeach; ?>

<form method="post" action="<?= e(adminUrl('gallery')) ?>" class="admin-card">
  <?= csrfField() ?>
  <h2>Nová sekcia galérie</h2>
  <div class="admin-grid admin-grid--3">
    <div class="admin-field">
      <label for="new_title">Názov</label>
      <input type="text" id="new_title" name="new_title" maxlength="80" placeholder="napr. Letná terasa">
    </div>
    <div class="admin-field">
      <label for="new_title_hu">Názov po maďarsky</label>
      <input type="text" id="new_title_hu" name="new_title_hu" maxlength="80">
    </div>
  </div>
  <button type="submit" name="action" value="new_section" class="admin-btn"><?= icon('plus', 'icon icon--sm') ?> Vytvoriť sekciu</button>
</form>
<?php
adminFooter();
