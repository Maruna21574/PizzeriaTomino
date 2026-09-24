<?php
/**
 * HTML rozloženie administrácie - hlavička s navigáciou, hlásenia, päta.
 */

function adminNav(): array
{
    return [
        'dashboard' => ['Prehľad', 'home'],
        'menu'      => ['Jedálny lístok', 'pizza'],
        'gallery'   => ['Galéria', 'zoom-in'],
        'reviews'   => ['Recenzie', 'star'],
        'settings'  => ['Nastavenia', 'clock'],
        'password'  => ['Heslo', 'info'],
    ];
}

function adminHeader(string $title, string $active = ''): void
{
    $cssVersion = filemtime(__DIR__ . '/../../admin/admin.css');
    ?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> | Administrácia <?= e(SITE_NAME) ?></title>
<link rel="stylesheet" href="/admin/admin.css?v=<?= (int) $cssVersion ?>">
</head>
<body class="admin">
<?php if (adminIsLoggedIn()): ?>
<header class="admin-header">
  <a href="<?= e(adminUrl('dashboard')) ?>" class="admin-brand"><img src="/assets/img/logo_tomino_w.png" alt="<?= e(SITE_NAME) ?>"><span>Administrácia</span></a>
  <button type="button" class="admin-nav-toggle" aria-label="Menu" onclick="document.body.classList.toggle('nav-open')"><span></span><span></span><span></span></button>
  <nav class="admin-nav">
    <?php foreach (adminNav() as $page => [$label, $iconName]): ?>
    <a href="<?= e(adminUrl($page)) ?>" class="<?= $page === $active ? 'active' : '' ?>"><?= icon($iconName, 'icon icon--sm') ?> <?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="/" target="_blank" rel="noopener"><?= icon('external-link', 'icon icon--sm') ?> Zobraziť web</a>
    <form method="post" action="<?= e(adminUrl('logout')) ?>" class="admin-nav__logout">
      <?= csrfField() ?>
      <button type="submit"><?= icon('x', 'icon icon--sm') ?> Odhlásiť</button>
    </form>
  </nav>
</header>
<?php endif; ?>
<main class="admin-main">
  <?php foreach (takeFlashes() as $flash): ?>
  <div class="admin-alert admin-alert--<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
  <?php endforeach; ?>
<?php
}

function adminFooter(): void
{
    $jsVersion = filemtime(__DIR__ . '/../../admin/admin.js');
    ?>
</main>
<script src="/admin/admin.js?v=<?= (int) $jsVersion ?>"></script>
</body>
</html>
<?php
}

/** Náhľad fotky v administrácii (alebo sivý rámček, ak fotka chýba). */
function adminThumb(string $name, string $class = 'admin-thumb'): string
{
    if ($name === '') {
        return '<span class="' . e($class) . ' ' . e($class) . '--empty">bez fotky</span>';
    }
    return '<img class="' . e($class) . '" src="' . e(photo($name, true)) . '?v=1" alt="" loading="lazy">';
}

/** Tlačidlo, ktoré odošle akciu v rámci formulára (napr. posun hore/dole, zmazanie). */
function adminActionButton(string $action, string $label, string $icon = '', string $class = '', string $confirm = ''): string
{
    return sprintf(
        '<button type="submit" name="action" value="%s" class="admin-btn admin-btn--sm %s" title="%s"%s>%s%s</button>',
        e($action),
        e($class),
        e($label),
        $confirm !== '' ? ' data-confirm="' . e($confirm) . '"' : '',
        $icon !== '' ? icon($icon, 'icon icon--sm') : '',
        $icon !== '' && strpos($class, 'icon-only') !== false ? '' : ' ' . e($label)
    );
}
