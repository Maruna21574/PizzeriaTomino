<?php
/** Odhlásenie (iba cez POST s CSRF tokenom - odkaz by sa dal zneužiť). */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    adminLogout();
}
header('Location: /admin/');
exit;
