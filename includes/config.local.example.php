<?php
/**
 * Vzor lokálnej konfigurácie servera. Skopírujte ako includes/config.local.php
 * (nie je v gite) a upravte. Použite napr. na testovacej subdoméne, aby
 * kanonické odkazy a zdieľanie ukazovali na ňu a Google ju neindexoval.
 */

define('SITE_URL', 'https://tominopizzeria.clicki.sk');
define('SITE_DOMAIN', 'clicki.sk');
define('SITE_NOINDEX', true);

// Odosielanie e-mailov z formulárov cez SMTP (schránka na doméne webu).
// Heslo patrí IBA sem (súbor nie je v gite), nikdy do kódu ani do gitu.
// Bez MAIL_SMTP_HOST sa použije obyčajné PHP mail() (horšie doručenie).
define('MAIL_SMTP_HOST', 'smtp.websupport.sk');
define('MAIL_SMTP_PORT', 465);
define('MAIL_SMTP_SECURE', 'ssl');          // 'ssl' (port 465) alebo 'tls' (port 587)
define('MAIL_SMTP_USER', 'noreply@clicki.sk');
define('MAIL_SMTP_PASS', 'heslo-schranky');
define('MAIL_FROM', 'noreply@clicki.sk');   // odosielateľ; musí byť na doméne webu
