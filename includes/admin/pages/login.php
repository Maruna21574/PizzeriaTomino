<?php
/** Prihlásenie do administrácie. */

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (adminLoginBlocked()) {
        $error = 'Príliš veľa neúspešných pokusov. Skúste to znova o 15 minút.';
    } elseif (adminLogin((string) ($_POST['password'] ?? ''))) {
        if (adminUsesInitialPassword()) {
            flash('Vitajte! Používate počiatočné heslo - zmeňte si ho prosím v časti Heslo.', 'warning');
        }
        adminRedirect('dashboard');
    } else {
        $error = 'Nesprávne heslo.';
    }
}

adminHeader('Prihlásenie');
?>
<div class="admin-login">
  <img src="/assets/img/logo_tomino_b.png" alt="<?= e(SITE_NAME) ?>" class="admin-login__logo">
  <h1>Administrácia webu</h1>
  <?php if ($error !== ''): ?>
  <div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post" action="/admin/">
    <label for="password">Heslo</label>
    <input type="password" id="password" name="password" required autofocus autocomplete="current-password">
    <button type="submit" class="admin-btn admin-btn--primary admin-btn--block"><?= icon('lock', 'icon icon--sm') ?> Prihlásiť sa</button>
  </form>
  <p class="admin-muted"><a href="/">&larr; Späť na web</a></p>
</div>
<?php
adminFooter();
