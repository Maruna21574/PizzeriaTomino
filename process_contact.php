<?php
/**
 * Spracovanie formulárov webu - kontaktný formulár (kontakt.php) a dopyt
 * na oslavu / firemnú akciu (oslavy-a-akcie.php). Ochrana proti spamu
 * a odoslanie e-mailu sú v includes/forms.php.
 * Objednávky jedla sa prijímajú iba telefonicky - web online objednávky nemá.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/forms.php';

// Jazyk stránky, z ktorej prišiel formulár - návrat aj hlásenia budú v ňom.
lang((string) ($_POST['lang'] ?? 'sk'));

$forms = formDefinitions();
$formId = (string) ($_POST['form'] ?? '');
$form = $forms[$formId] ?? $forms['kontakt'];

function redirectBack(array $form, string $query): void
{
    header('Location: ' . url($form['page']) . '?' . $query . '#formular');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($forms[$formId])) {
    header('Location: ' . url($form['page']));
    exit;
}

$spam = antiSpamCheck($formId, $_POST);
if ($spam === 'spam') {
    // Robotovi sa tvárime, že všetko prebehlo v poriadku.
    redirectBack($form, 'sent=1');
}
if ($spam !== null) {
    redirectBack($form, 'error=' . $spam);
}

$name  = formText($_POST, 'name', 100);
$email = formText($_POST, 'email', 150);
$phone = formText($_POST, 'phone', 30);
$message = formText($_POST, 'message', 3000, true);

$emailValid = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
$phoneValid = $phone !== '' && preg_match('/^[0-9+\s\/()-]{7,20}$/', $phone);

if ($formId === 'akcie') {
    $eventTypes = eventTypes();
    $eventType = (string) ($_POST['event_type'] ?? '');
    $date = formText($_POST, 'date', 10);
    $guests = (int) ($_POST['guests'] ?? 0);

    // Na dopyt treba meno, telefón (akcie dohadujeme telefonicky) a typ akcie.
    if ($name === '' || !$phoneValid || ($email !== '' && !$emailValid) || !isset($eventTypes[$eventType])
        || ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) || $guests < 0 || $guests > 500) {
        redirectBack($form, 'error=invalid');
    }

    $body  = "Typ akcie: {$eventTypes[$eventType]}\n";
    $body .= 'Termín: ' . ($date !== '' ? date('j. n. Y', strtotime($date)) : '-') . "\n";
    $body .= 'Počet osôb: ' . ($guests > 0 ? $guests : '-') . "\n\n";
    $body .= "Meno: {$name}\n";
    $body .= "Telefón: {$phone}\n";
    $body .= 'E-mail: ' . ($email !== '' ? $email : '-') . "\n\n";
    $body .= "Poznámka:\n" . ($message !== '' ? $message : '-') . "\n";
} else {
    if ($name === '' || $message === '' || !$emailValid || ($phone !== '' && !$phoneValid)) {
        redirectBack($form, 'error=invalid');
    }

    $body  = "Meno: {$name}\n";
    $body .= "E-mail: {$email}\n";
    $body .= 'Telefón: ' . ($phone !== '' ? $phone : '-') . "\n\n";
    $body .= "Správa:\n{$message}\n";
}

if (lang() !== 'sk') {
    $body .= "\nPozor: správa prišla z maďarskej verzie webu - zákazník zrejme hovorí po maďarsky.\n";
}

if (!sendSiteMail($form['subject'], $body, $emailValid ? $email : '')) {
    error_log('Formulár "' . $formId . '": odoslanie e-mailu zlyhalo.');
    redirectBack($form, 'error=mail');
}

redirectBack($form, 'sent=1');
