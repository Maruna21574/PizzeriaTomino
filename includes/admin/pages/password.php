<?php
/** Zmena hesla do administrácie. */

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = (string) ($_POST['current'] ?? '');
    $new = (string) ($_POST['new'] ?? '');

    if (!password_verify($current, adminPasswordHash())) {
        $errors[] = 'Súčasné heslo nie je správne.';
    }
    if (mb_strlen($new, 'UTF-8') < ADMIN_PASSWORD_MIN_LENGTH) {
        $errors[] = 'Nové heslo musí mať aspoň ' . ADMIN_PASSWORD_MIN_LENGTH . ' znakov.';
    }
    if ($new !== (string) ($_POST['repeat'] ?? '')) {
        $errors[] = 'Nové heslá sa nezhodujú.';
    }

    if (!$errors) {
        if (adminSetPassword($new)) {
            session_regenerate_id(true);
            flash('Heslo bolo zmenené.');
        } else {
            flash('Heslo sa nepodarilo uložiť - priečinok storage/ nie je zapisovateľný.', 'error');
        }
        adminRedirect('password');
    }
}

adminHeader('Zmena hesla', 'password');
?>
<h1>Zmena hesla</h1>

<?php foreach ($errors as $error): ?>
<div class="admin-alert admin-alert--error"><?= e($error) ?></div>
<?php endforeach; ?>

<form method="post" action="<?= e(adminUrl('password')) ?>" class="admin-card admin-narrow">
  <?= csrfField() ?>
  <div class="admin-field">
    <label for="current">Súčasné heslo</label>
    <input type="password" id="current" name="current" required autocomplete="current-password">
  </div>
  <div class="admin-field">
    <label for="new">Nové heslo <span class="admin-muted">(aspoň <?= ADMIN_PASSWORD_MIN_LENGTH ?> znakov)</span></label>
    <input type="password" id="new" name="new" required minlength="<?= ADMIN_PASSWORD_MIN_LENGTH ?>" autocomplete="new-password">
  </div>
  <div class="admin-field">
    <label for="repeat">Nové heslo znova</label>
    <input type="password" id="repeat" name="repeat" required autocomplete="new-password">
  </div>
  <button type="submit" class="admin-btn admin-btn--primary"><?= icon('lock', 'icon icon--sm') ?> Zmeniť heslo</button>
</form>
<?php
adminFooter();
