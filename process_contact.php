<?php
/**
 * Spracovanie všeobecného kontaktného formulára (kontakt.php).
 * Objednávky z košíka spracúva samostatne process_order.php.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /kontakt.php');
    exit;
}

// Honeypot proti spamu - skryté pole, ktoré vypĺňajú iba boti.
if (!empty($_POST['website'])) {
    header('Location: /kontakt.php?sent=1');
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /kontakt.php?error=1');
    exit;
}

$subject = 'Nová správa z kontaktného formulára - ' . SITE_NAME;
$body = "Meno: {$name}\n";
$body .= "E-mail: {$email}\n";
$body .= "Telefón: " . ($phone !== '' ? $phone : '-') . "\n\n";
$body .= "Správa:\n{$message}\n";

$headers = [
    'From: ' . SITE_NAME . ' <noreply@' . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'pizzeriatominno.sk') . '>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
];

@mail(SITE_EMAIL, $subject, $body, implode("\r\n", $headers));

header('Location: /kontakt.php?sent=1');
exit;
