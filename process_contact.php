<?php
/**
 * Spracovanie formulárov webu - kontaktný formulár (kontakt.php) a dopyt
 * na oslavu / firemnú akciu (oslavy-a-akcie.php). Ochrana proti spamu je
 * v includes/forms.php, odosielanie e-mailov a ich šablóny v includes/mail.php.
 * Objednávky jedla sa prijímajú iba telefonicky - web online objednávky nemá.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/forms.php';
require_once __DIR__ . '/includes/mail.php';

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

$summary = [];   // zhrnutie dopytu pre potvrdenie zákazníkovi
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

    $rows = [
        'Typ akcie' => $eventTypes[$eventType],
        'Termín' => $date !== '' ? date('j. n. Y', strtotime($date)) : '-',
        'Počet osôb' => $guests > 0 ? (string) $guests : '-',
        'Meno' => $name,
        'Telefón' => $phone,
        'E-mail' => $email !== '' ? $email : '-',
    ];
    $summary = [
        t('Typ akcie') => t($eventTypes[$eventType]),
        t('Termín') => $rows['Termín'],
        t('Počet osôb') => $rows['Počet osôb'],
    ];
} else {
    if ($name === '' || $message === '' || !$emailValid || ($phone !== '' && !$phoneValid)) {
        redirectBack($form, 'error=invalid');
    }

    $rows = [
        'Meno' => $name,
        'E-mail' => $email,
        'Telefón' => $phone !== '' ? $phone : '-',
    ];
}

// Tá istá správa dvakrát (dvojklik, robot) - zákazníkovi sa tvárime, že prešla.
if (isDuplicateSubmission($formId, [$name, $email, $phone, $message])) {
    redirectBack($form, 'sent=1');
}
// Denný strop správ z celého webu (ochrana pred zaplavením schránky).
if (bucketExceeded('daily', FORM_DAILY_LIMIT, 86400)) {
    error_log('Formulár "' . $formId . '": prekročený denný limit správ.');
    redirectBack($form, 'error=mail');
}

[$html, $text] = adminMailContent($form['subject'], $rows, $message, $emailValid ? $email : '', $phoneValid ? $phone : '');
$subject = $form['subject'] . ' - ' . $name;
if (!sendMail(SITE_EMAIL, $subject, $html, $text, $emailValid ? $email : '')) {
    error_log('Formulár "' . $formId . '": odoslanie e-mailu zlyhalo.');
    redirectBack($form, 'error=mail');
}

// Potvrdenie zákazníkovi (ak zadal e-mail) - najviac FORM_RECIPIENT_LIMIT za hodinu na adresu.
// Zlyhanie potvrdenia správu neruší, prevádzka ju už má.
if ($emailValid && !bucketExceeded('to|' . strtolower($email), FORM_RECIPIENT_LIMIT, 3600)) {
    [$customerSubject, $customerHtml, $customerText] = customerMailContent($summary);
    if (!sendMail($email, $customerSubject, $customerHtml, $customerText, SITE_EMAIL)) {
        error_log('Formulár "' . $formId . '": potvrdenie zákazníkovi sa nepodarilo odoslať.');
    }
}

redirectBack($form, 'sent=1');
