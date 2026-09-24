<?php
/**
 * Recenzie z Google (zobrazené na úvodnej stránke) a údaje o Google profile.
 */

$reviews = reviewsContent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$action, $id] = array_pad(explode(':', (string) ($_POST['action'] ?? ''), 2), 2, '');

    if ($action === 'google') {
        $settings = siteSettings();
        foreach (['google_profile_url', 'google_review_url'] as $field) {
            $value = postText($field, 500);
            if ($value !== '' && !preg_match('~^https://~', $value)) {
                flash('Odkaz musí začínať https:// - „' . $value . '“ nebol uložený.', 'error');
                continue;
            }
            $settings[$field] = $value;
        }
        $rating = str_replace('.', ',', postText('google_rating', 4));
        $settings['google_rating'] = preg_match('/^[1-5](,\d)?$/', $rating) ? $rating : '';
        $settings['google_review_count'] = max(0, (int) ($_POST['google_review_count'] ?? 0));
        adminSave('settings', $settings, 'Údaje o Google profile boli uložené.');
        adminRedirect('reviews');
    }

    $review = [
        'name' => postText('name', 60),
        'rating' => max(1, min(5, (int) ($_POST['rating'] ?? 5))),
        'date' => postText('date', 40),
        'text' => postText('text', 1500, true),
    ];

    switch ($action) {
        case 'add':
            if ($review['name'] === '' || $review['text'] === '') {
                flash('Vyplňte meno aj text recenzie.', 'error');
                adminRedirect('reviews', [], 'nova');
            }
            $reviews[] = ['id' => newContentId()] + $review;
            adminSave('reviews', $reviews, 'Recenzia bola pridaná.');
            break;
        case 'save':
            foreach ($reviews as $i => $row) {
                if ($row['id'] === $id && $review['name'] !== '' && $review['text'] !== '') {
                    $reviews[$i] = ['id' => $id] + $review;
                    adminSave('reviews', $reviews, 'Recenzia bola uložená.');
                }
            }
            break;
        case 'up':
        case 'down':
            adminSave('reviews', moveById($reviews, $id, $action === 'up' ? -1 : 1), 'Poradie recenzií bolo zmenené.');
            break;
        case 'delete':
            adminSave('reviews', array_values(array_filter($reviews, function ($row) use ($id) { return $row['id'] !== $id; })), 'Recenzia bola zmazaná.');
            break;
    }
    adminRedirect('reviews');
}

$settings = siteSettings();

adminHeader('Recenzie', 'reviews');
?>
<h1>Recenzie</h1>

<div class="admin-alert admin-alert--warning">
  <strong>Vkladajte iba skutočné recenzie</strong> skopírované z Google profilu pizzerie - bez úprav textu a hodnotenia.
  Vymyslené alebo upravené recenzie sú podľa zákona o ochrane spotrebiteľa zakázané (hrozí pokuta od SOI) a porušujú aj pravidlá Google.
</div>

<form method="post" action="<?= e(adminUrl('reviews')) ?>" class="admin-card">
  <?= csrfField() ?>
  <h2>Google profil</h2>
  <div class="admin-grid">
    <div class="admin-field">
      <label for="google_profile_url">Odkaz na Google profil (Mapy Google)</label>
      <input type="url" id="google_profile_url" name="google_profile_url" value="<?= e($settings['google_profile_url']) ?>">
    </div>
    <div class="admin-field">
      <label for="google_review_url">Odkaz na napísanie recenzie</label>
      <input type="url" id="google_review_url" name="google_review_url" value="<?= e($settings['google_review_url']) ?>" placeholder="https://g.page/r/.../review">
      <p class="admin-muted">Nájdete ho v Google Business Profile → „Požiadať o recenzie“ / „Get more reviews“.</p>
    </div>
    <div class="admin-field">
      <label for="google_rating">Priemerné hodnotenie <span class="admin-muted">(napr. 4,8 - prázdne = nezobrazí sa)</span></label>
      <input type="text" id="google_rating" name="google_rating" maxlength="4" value="<?= e($settings['google_rating']) ?>">
    </div>
    <div class="admin-field">
      <label for="google_review_count">Počet recenzií na Google</label>
      <input type="number" id="google_review_count" name="google_review_count" min="0" value="<?= (int) $settings['google_review_count'] ?>">
    </div>
  </div>
  <button type="submit" name="action" value="google" class="admin-btn admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť</button>
</form>

<h2>Recenzie na webe (<?= count($reviews) ?>)</h2>
<?php if (!$reviews): ?>
<p class="admin-muted">Zatiaľ žiadne recenzie. Kým nie sú pridané, na webe sa zobrazí len výzva „Ohodnoťte nás na Google“.</p>
<?php endif; ?>

<?php foreach ($reviews as $review): ?>
<form method="post" action="<?= e(adminUrl('reviews')) ?>" class="admin-card admin-review">
  <?= csrfField() ?>
  <button type="submit" name="action" value="save:<?= e($review['id']) ?>" class="admin-default-submit" tabindex="-1" aria-hidden="true">Uložiť</button>
  <div class="admin-grid admin-grid--3">
    <div class="admin-field">
      <label>Meno (ako na Google)</label>
      <input type="text" name="name" maxlength="60" required value="<?= e($review['name']) ?>">
    </div>
    <div class="admin-field">
      <label>Hodnotenie</label>
      <select name="rating">
        <?php for ($r = 5; $r >= 1; $r--): ?>
        <option value="<?= $r ?>"<?= (int) $review['rating'] === $r ? ' selected' : '' ?>><?= str_repeat('★', $r) . str_repeat('☆', 5 - $r) ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="admin-field">
      <label>Dátum <span class="admin-muted">(nepovinné)</span></label>
      <input type="text" name="date" maxlength="40" value="<?= e($review['date'] ?? '') ?>" placeholder="august 2026">
    </div>
  </div>
  <div class="admin-field">
    <label>Text recenzie</label>
    <textarea name="text" rows="3" maxlength="1500" required><?= e($review['text']) ?></textarea>
  </div>
  <div class="admin-actions">
    <button type="submit" name="action" value="save:<?= e($review['id']) ?>" class="admin-btn admin-btn--sm admin-btn--primary"><?= icon('save', 'icon icon--sm') ?> Uložiť</button>
    <?= adminActionButton('up:' . $review['id'], 'Posunúť hore', 'arrow-up', 'icon-only') ?>
    <?= adminActionButton('down:' . $review['id'], 'Posunúť dole', 'arrow-down', 'icon-only') ?>
    <?= adminActionButton('delete:' . $review['id'], 'Zmazať', 'trash', 'icon-only admin-btn--danger', 'Naozaj zmazať recenziu od ' . $review['name'] . '?') ?>
  </div>
</form>
<?php endforeach; ?>

<form method="post" action="<?= e(adminUrl('reviews')) ?>" class="admin-card" id="nova">
  <?= csrfField() ?>
  <h2>Pridať recenziu z Google</h2>
  <div class="admin-grid admin-grid--3">
    <div class="admin-field">
      <label for="r_name">Meno (ako na Google)</label>
      <input type="text" id="r_name" name="name" maxlength="60" placeholder="napr. Ján K.">
    </div>
    <div class="admin-field">
      <label for="r_rating">Hodnotenie</label>
      <select id="r_rating" name="rating">
        <?php for ($r = 5; $r >= 1; $r--): ?>
        <option value="<?= $r ?>"><?= str_repeat('★', $r) . str_repeat('☆', 5 - $r) ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="admin-field">
      <label for="r_date">Dátum <span class="admin-muted">(nepovinné)</span></label>
      <input type="text" id="r_date" name="date" maxlength="40" placeholder="august 2026">
    </div>
  </div>
  <div class="admin-field">
    <label for="r_text">Text recenzie (presne ako na Google)</label>
    <textarea id="r_text" name="text" rows="3" maxlength="1500"></textarea>
  </div>
  <button type="submit" name="action" value="add" class="admin-btn admin-btn--primary"><?= icon('plus', 'icon icon--sm') ?> Pridať recenziu</button>
</form>
<?php
adminFooter();
