<?php
/**
 * Administrácia webu Pizzeria Tominno - vstupný bod (/admin/?p=stránka).
 * Jednotlivé stránky sú v includes/admin/pages/<stránka>.php.
 */
require_once __DIR__ . '/../includes/admin/bootstrap.php';
require_once __DIR__ . '/../includes/admin/layout.php';

adminStartSession();

$pages = ['dashboard', 'menu', 'item', 'category', 'gallery', 'reviews', 'settings', 'password', 'logout'];
$page = (string) ($_GET['p'] ?? 'dashboard');
if (!in_array($page, $pages, true)) {
    $page = 'dashboard';
}

if (!adminIsLoggedIn()) {
    require __DIR__ . '/../includes/admin/pages/login.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    adminVerifyCsrf();
}

require __DIR__ . '/../includes/admin/pages/' . $page . '.php';
