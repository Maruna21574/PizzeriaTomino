<?php
/**
 * Nastavenia prevádzky - kontakty, otváracie hodiny, zatvorené dni, oznam
 * na webe, rozvoz, sociálne siete a údaje o prevádzkovateľovi.
 * Ukladá sa do storage/content/settings.json (čítané v includes/config.php).
 */

$settings = siteSettings();
$errors = [];

/** Dátum z formulára: "24.12.2026", "24. 12. 2026" alebo "2026-12-24" -> "2026-12-24". */
function parseAdminDate(string $value): ?string
{
    $value = trim($value);
    if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $m)) {
        [$y, $mo, $d] = [(int) $m[1], (int) $m[2], (int) $m[3]];
    } elseif (preg_match('/^(\d{1,2})\.\s*(\d{1,2})\.\s*(\d{4})$/', $value, $m)) {
        [$d, $mo, $y] = [(int) $m[1], (int) $m[2], (int) $m[3]];
    } else {
        return null;
    }
    return checkdate($mo, $d, $y) ? sprintf('%04d-%02d-%02d', $y, $mo, $d) : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kontakty
    $settings['phone'] = postText('phone', 30);
    if (strlen(preg_replace('/\D/', '', $settings['phone'])) < 9) {
        $errors[] = 'Telefónne číslo nie je platné.';
    }
    $settings['email'] = postText('email', 150);
    if (!filter_var($settings['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail nie je platný.';
    }
    foreach (['address_street' => 80, 'address_zip' => 10, 'address_town' => 60] as $field => $max) {
        $settings[$field] = postText($field, $max);
    }

    // Otváracie hodiny
    foreach (array_keys($settings['opening_hours']) as $day) {
        $row = (array) ($_POST['hours'][$day] ?? []);
        if (!empty($row['closed'])) {
            $settings['opening_hours'][$day] = null;
            continue;
        }
        $open = trim((string) ($row['open'] ?? ''));
        $close = trim((string) ($row['close'] ?? ''));
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $open) || !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $close) || $close <= $open) {
            $errors[] = $day . ': zadajte čas otvorenia a zatvorenia (napr. 10:00 a 22:00, zatvorenie po otvorení).';
            continue;
        }
        $settings['opening_hours'][$day] = [$open, $close];
    }

    // Zatvorené dni - staršie ako včera sa automaticky vyradia
    $dates = [];
    foreach (preg_split('/[\r\n,;]+/', (string) ($_POST['closed_dates'] ?? '')) as $line) {
        if (trim($line) === '') {
            continue;
        }
        $date = parseAdminDate($line);
        if ($date === null) {
            $errors[] = 'Dátum „' . trim($line) . '“ nie je platný (použite tvar 24.12.2026).';
        } elseif ($date >= date('Y-m-d', strtotime('-1 day'))) {
            $dates[] = $date;
        }
    }
    $dates = array_values(array_unique($dates));
    sort($dates);
    $settings['closed_dates'] = $dates;

    // Oznam
    $settings['notice'] = [
        'active'  => !empty($_POST['notice_active']),
        'text'    => postText('notice_text', 300, true),
        'text_hu' => postText('notice_text_hu', 300, true),
    ];
    if ($settings['notice']['active'] && $settings['notice']['text'] === '') {
        $errors[] = 'Oznam je zapnutý, ale nemá text.';
    }

    // Rozvoz
    $settings['delivery_area'] = postText('delivery_area', 150);
    $settings['delivery_area_hu'] = postText('delivery_area_hu', 150);
    $settings['delivery_towns'] = array_values(array_filter(array_map('trim', explode(',', postText('delivery_towns', 300)))));
    foreach (['delivery_fee', 'delivery_free_from', 'delivery_min_order'] as $field) {
        $price = parsePrice(postText($field, 20));
        if ($price === null) {
            $errors[] = 'Suma v poli rozvozu nie je platné číslo.';
        } else {
            $settings[$field] = $price;
        }
    }

    // Sociálne siete
    foreach (['social_facebook', 'social_instagram'] as $field) {
        $value = postText($field, 300);
        if ($value !== '' && !preg_match('~^https://~', $value)) {
            $errors[] = 'Odkaz na sociálnu sieť musí začínať https://';
            continue;
        }
        $settings[$field] = $value;
    }

    // Prevádzkovateľ
    foreach (['company_name' => 120, 'company_address' => 200, 'company_ico' => 20] as $field => $max) {
        $settings[$field] = postText($field, $max);
    }

    if (!$errors) {
        adminSave('settings', $settings, 'Nastavenia boli uložené.');
        adminRedirect('settings');
    }
}

adminHeader('Nastavenia', 'settings');
?>
<h1>Nastavenia</h1>

<?php foreach ($errors as $error): ?>
<div class="admin-alert admin-alert--error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" action="<?= e(adminUrl('settings')) ?>" class="admin-form">
  <?= csrfField() ?>

  <section class="admin-card" id="oznam">
    <h2>Oznam na webe</h2>
    <p class="admin-muted">Zobrazí sa výrazne pod hlavičkou na všetkých stránkach - napr. „Počas sviatkov 24. - 26. 12. máme zatvorené.“</p>
    <label class="admin-check"><input type="checkbox" name="notice_active" value="1"<?= !empty($settings['notice']['active']) ? ' checked' : '' ?>> Zobraziť oznam</label>
    <div class="admin-grid">
      <div class="admin-field">
        <label for="notice_text">Text oznamu</label>
        <textarea id="notice_text" name="notice_text" rows="2" maxlength="300"><?= e($settings['notice']['text'] ?? '') ?></textarea>
      </div>
      <div class="admin-field">
        <label for="notice_text_hu">Text po maďarsky</label>
        <textarea id="notice_text_hu" name="notice_text_hu" rows="2" maxlength="300"><?= e($settings['notice']['text_hu'] ?? '') ?></textarea>
      </div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Otváracie hodiny</h2>
    <table class="admin-hours">
      <?php foreach ($settings['opening_hours'] as $day => $hours): ?>
      <tr>
        <th><?= e($day) ?></th>
        <td><input type="time" name="hours[<?= e($day) ?>][open]" value="<?= e($hours[0] ?? '10:00') ?>" aria-label="<?= e($day) ?> otvárame"></td>
        <td>–</td>
        <td><input type="time" name="hours[<?= e($day) ?>][close]" value="<?= e($hours[1] ?? '22:00') ?>" aria-label="<?= e($day) ?> zatvárame"></td>
        <td><label class="admin-check"><input type="checkbox" name="hours[<?= e($day) ?>][closed]" value="1"<?= $hours === null ? ' checked' : '' ?>> zatvorené</label></td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div class="admin-field">
      <label for="closed_dates">Mimoriadne zatvorené dni <span class="admin-muted">(sviatky, dovolenka - každý dátum na nový riadok, napr. 24.12.2026)</span></label>
      <textarea id="closed_dates" name="closed_dates" rows="3"><?= e(implode("\n", array_map(function ($d) { return date('j.n.Y', strtotime($d)); }, $settings['closed_dates']))) ?></textarea>
      <p class="admin-muted">V tieto dni web ukáže „Zatvorené“. Staré dátumy sa po uložení automaticky vymažú.</p>
    </div>
  </section>

  <section class="admin-card">
    <h2>Kontakt a adresa</h2>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field"><label for="phone">Telefón</label><input type="tel" id="phone" name="phone" value="<?= e($settings['phone']) ?>" required></div>
      <div class="admin-field"><label for="email">E-mail <span class="admin-muted">(sem chodia správy z formulárov)</span></label><input type="email" id="email" name="email" value="<?= e($settings['email']) ?>" required></div>
    </div>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field"><label for="address_street">Ulica a číslo</label><input type="text" id="address_street" name="address_street" value="<?= e($settings['address_street']) ?>"></div>
      <div class="admin-field"><label for="address_zip">PSČ</label><input type="text" id="address_zip" name="address_zip" value="<?= e($settings['address_zip']) ?>"></div>
      <div class="admin-field"><label for="address_town">Obec</label><input type="text" id="address_town" name="address_town" value="<?= e($settings['address_town']) ?>"></div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Rozvoz</h2>
    <div class="admin-grid">
      <div class="admin-field"><label for="delivery_area">Oblasť rozvozu</label><input type="text" id="delivery_area" name="delivery_area" value="<?= e($settings['delivery_area']) ?>"></div>
      <div class="admin-field"><label for="delivery_area_hu">Oblasť rozvozu po maďarsky</label><input type="text" id="delivery_area_hu" name="delivery_area_hu" value="<?= e($settings['delivery_area_hu']) ?>"></div>
    </div>
    <div class="admin-field"><label for="delivery_towns">Obce pre Google <span class="admin-muted">(oddelené čiarkou)</span></label><input type="text" id="delivery_towns" name="delivery_towns" value="<?= e(implode(', ', $settings['delivery_towns'])) ?>"></div>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field"><label for="delivery_min_order">Minimálna objednávka (€)</label><input type="text" id="delivery_min_order" name="delivery_min_order" inputmode="decimal" value="<?= e(number_format((float) $settings['delivery_min_order'], 2, ',', '')) ?>"></div>
      <div class="admin-field"><label for="delivery_fee">Poplatok za dopravu (€)</label><input type="text" id="delivery_fee" name="delivery_fee" inputmode="decimal" value="<?= e(number_format((float) $settings['delivery_fee'], 2, ',', '')) ?>"></div>
      <div class="admin-field"><label for="delivery_free_from">Doprava zdarma od (€)</label><input type="text" id="delivery_free_from" name="delivery_free_from" inputmode="decimal" value="<?= e(number_format((float) $settings['delivery_free_from'], 2, ',', '')) ?>"></div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Sociálne siete</h2>
    <p class="admin-muted">Vyplnené odkazy sa zobrazia v pätičke webu.</p>
    <div class="admin-grid">
      <div class="admin-field"><label for="social_facebook">Facebook</label><input type="url" id="social_facebook" name="social_facebook" value="<?= e($settings['social_facebook']) ?>" placeholder="https://www.facebook.com/..."></div>
      <div class="admin-field"><label for="social_instagram">Instagram</label><input type="url" id="social_instagram" name="social_instagram" value="<?= e($settings['social_instagram']) ?>" placeholder="https://www.instagram.com/..."></div>
    </div>
  </section>

  <section class="admin-card">
    <h2>Prevádzkovateľ</h2>
    <p class="admin-muted">Zobrazuje sa na stránke Ochrana osobných údajov (povinné podľa GDPR).</p>
    <div class="admin-grid admin-grid--3">
      <div class="admin-field"><label for="company_name">Obchodné meno</label><input type="text" id="company_name" name="company_name" value="<?= e($settings['company_name']) ?>" placeholder="napr. Tominno s.r.o."></div>
      <div class="admin-field"><label for="company_address">Sídlo</label><input type="text" id="company_address" name="company_address" value="<?= e($settings['company_address']) ?>"></div>
      <div class="admin-field"><label for="company_ico">IČO</label><input type="text" id="company_ico" name="company_ico" value="<?= e($settings['company_ico']) ?>"></div>
    </div>
  </section>

  <div class="admin-savebar">
    <button type="submit" class="admin-btn admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť nastavenia</button>
  </div>
</form>
<?php
adminFooter();
