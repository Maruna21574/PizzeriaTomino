<?php
/**
 * Jedálny lístok - prehľad kategórií a položiek. Poradie (hore/dole),
 * skrytie (dočasne nedostupné) a zmazanie. Úprava položky: ?p=item,
 * úprava kategórie: ?p=category.
 */

$menu = menuContent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$action, $id] = array_pad(explode(':', (string) ($_POST['action'] ?? ''), 2), 2, '');
    $removedPhotos = [];
    $message = '';

    foreach ($menu as $ci => $category) {
        // Akcie s kategóriou
        if ($category['id'] === $id) {
            switch ($action) {
                case 'cat_up':
                case 'cat_down':
                    $menu = moveById($menu, $id, $action === 'cat_up' ? -1 : 1);
                    $message = 'Poradie kategórií bolo zmenené.';
                    break;
                case 'cat_toggle':
                    $menu[$ci]['hidden'] = empty($category['hidden']);
                    $message = $menu[$ci]['hidden'] ? 'Kategória „' . $category['label'] . '“ je skrytá.' : 'Kategória „' . $category['label'] . '“ je opäť zobrazená.';
                    break;
                case 'cat_delete':
                    if ($category['items']) {
                        flash('Kategóriu „' . $category['label'] . '“ nemožno zmazať, kým obsahuje položky. Najprv ich presuňte alebo zmažte.', 'error');
                        adminRedirect('menu');
                    }
                    $removedPhotos[] = $category['photo'] ?? '';
                    array_splice($menu, $ci, 1);
                    $message = 'Kategória „' . $category['label'] . '“ bola zmazaná.';
                    break;
            }
            break;
        }

        // Akcie s položkou
        foreach ($category['items'] as $ii => $item) {
            if ($item['id'] !== $id) {
                continue;
            }
            switch ($action) {
                case 'item_up':
                case 'item_down':
                    $menu[$ci]['items'] = moveById($category['items'], $id, $action === 'item_up' ? -1 : 1);
                    $message = 'Poradie položiek bolo zmenené.';
                    break;
                case 'item_toggle':
                    $menu[$ci]['items'][$ii]['hidden'] = empty($item['hidden']);
                    $message = $menu[$ci]['items'][$ii]['hidden']
                        ? '„' . $item['name'] . '“ je skrytá (dočasne nedostupná).'
                        : '„' . $item['name'] . '“ je opäť v ponuke.';
                    break;
                case 'item_delete':
                    $removedPhotos[] = $item['photo'] ?? '';
                    array_splice($menu[$ci]['items'], $ii, 1);
                    $message = 'Položka „' . $item['name'] . '“ bola zmazaná.';
                    break;
            }
            break 2;
        }
    }

    if ($message !== '' && adminSave('menu', $menu, $message)) {
        foreach ($removedPhotos as $photoName) {
            deletePhotoIfUnused((string) $photoName);
        }
    }
    adminRedirect('menu', [], $id !== '' ? 'r-' . $id : '');
}

adminHeader('Jedálny lístok', 'menu');
?>
<div class="admin-titlebar">
  <h1>Jedálny lístok</h1>
  <a class="admin-btn admin-btn--primary" href="<?= e(adminUrl('category', ['id' => 'new'])) ?>"><?= icon('plus', 'icon icon--sm') ?> Nová kategória</a>
</div>
<p class="admin-lead">Kliknite na položku, ak chcete zmeniť cenu, zloženie alebo fotku. Ikonou oka položku dočasne skryjete (napr. keď sa minie) - na webe sa nezobrazí, ale zostane uložená.</p>

<form method="post" action="<?= e(adminUrl('menu')) ?>">
  <?= csrfField() ?>

  <?php foreach ($menu as $ci => $category): ?>
  <section class="admin-card admin-menu-cat<?= !empty($category['hidden']) ? ' is-hidden' : '' ?>" id="r-<?= e($category['id']) ?>">
    <header class="admin-menu-cat__head">
      <?= adminThumb($category['photo'] ?? '', 'admin-thumb admin-thumb--wide') ?>
      <div class="admin-menu-cat__title">
        <h2><?= e($category['label']) ?> <?php if (!empty($category['hidden'])): ?><span class="admin-badge">skrytá</span><?php endif; ?></h2>
        <span class="admin-muted"><?= count($category['items']) ?> položiek</span>
      </div>
      <div class="admin-actions">
        <?= adminActionButton('cat_up:' . $category['id'], 'Posunúť hore', 'arrow-up', 'icon-only') ?>
        <?= adminActionButton('cat_down:' . $category['id'], 'Posunúť dole', 'arrow-down', 'icon-only') ?>
        <?= adminActionButton('cat_toggle:' . $category['id'], !empty($category['hidden']) ? 'Zobraziť na webe' : 'Skryť z webu', !empty($category['hidden']) ? 'eye' : 'eye-off', 'icon-only') ?>
        <a class="admin-btn admin-btn--sm" href="<?= e(adminUrl('category', ['id' => $category['id']])) ?>"><?= icon('edit', 'icon icon--sm') ?> Upraviť kategóriu</a>
        <?= adminActionButton('cat_delete:' . $category['id'], 'Zmazať kategóriu', 'trash', 'icon-only admin-btn--danger', 'Naozaj zmazať kategóriu „' . $category['label'] . '“?') ?>
      </div>
    </header>

    <ul class="admin-menu-items">
      <?php foreach ($category['items'] as $item): ?>
      <li class="admin-menu-item<?= !empty($item['hidden']) ? ' is-hidden' : '' ?>" id="r-<?= e($item['id']) ?>">
        <a class="admin-menu-item__main" href="<?= e(adminUrl('item', ['id' => $item['id']])) ?>">
          <?= adminThumb($item['photo'] ?? '') ?>
          <span class="admin-menu-item__text">
            <strong><?php if (!empty($item['num'])): ?><?= (int) $item['num'] ?>. <?php endif; ?><?= e($item['name']) ?></strong>
            <span class="admin-muted"><?= e(mb_strimwidth($item['desc'] ?? '', 0, 90, '…', 'UTF-8')) ?></span>
          </span>
          <span class="admin-menu-item__price">
            <?php foreach (menuItemPrices($item) as $variant): ?>
            <span><?php if ($variant['label'] !== ''): ?><small><?= e($variant['label']) ?></small> <?php endif; ?><?= formatPrice((float) $variant['price']) ?></span>
            <?php endforeach; ?>
          </span>
          <?php if (!empty($item['hidden'])): ?><span class="admin-badge">skrytá</span><?php endif; ?>
          <?php if (!empty($item['featured'])): ?><span class="admin-badge admin-badge--gold" title="Zobrazuje sa na úvodnej stránke">úvod</span><?php endif; ?>
        </a>
        <div class="admin-actions">
          <?= adminActionButton('item_up:' . $item['id'], 'Posunúť hore', 'arrow-up', 'icon-only') ?>
          <?= adminActionButton('item_down:' . $item['id'], 'Posunúť dole', 'arrow-down', 'icon-only') ?>
          <?= adminActionButton('item_toggle:' . $item['id'], !empty($item['hidden']) ? 'Vrátiť do ponuky' : 'Dočasne skryť', !empty($item['hidden']) ? 'eye' : 'eye-off', 'icon-only') ?>
          <?= adminActionButton('item_delete:' . $item['id'], 'Zmazať', 'trash', 'icon-only admin-btn--danger', 'Naozaj zmazať položku „' . $item['name'] . '“? Ak ju chcete len dočasne stiahnuť z ponuky, použite radšej skrytie.') ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>

    <a class="admin-btn admin-btn--sm admin-btn--ghost" href="<?= e(adminUrl('item', ['id' => 'new', 'cat' => $category['id']])) ?>"><?= icon('plus', 'icon icon--sm') ?> Pridať položku do „<?= e($category['label']) ?>“</a>
  </section>
  <?php endforeach; ?>
</form>
<?php
adminFooter();
