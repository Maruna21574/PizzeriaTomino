<?php
/**
 * Odosielanie e-mailov z formulárov: SMTP cez PHPMailer + HTML šablóny.
 *
 * Nastavenie SMTP je v includes/config.local.php (nie je v gite, vzor je
 * v config.local.example.php) - heslo schránky sa nikdy nedáva do gitu.
 * Bez nastavenia MAIL_SMTP_HOST sa použije obyčajné PHP mail().
 *
 * Prevádzka dostáva správu z formulára (adminMailHtml), zákazník potvrdenie
 * (customerMailHtml) v jazyku, v ktorom formulár vyplnil (SK / HU).
 */

use PHPMailer\PHPMailer\PHPMailer;

const MAIL_BRAND_COLOR = '#c1272d';

/** Adresa odosielateľa e-mailov (schránka na doméne webu). */
function mailFromAddress(): string
{
    return defined('MAIL_FROM') && MAIL_FROM !== '' ? MAIL_FROM : 'noreply@' . SITE_DOMAIN;
}

/** Odošle e-mail (HTML + textová verzia). Chyby zapíše do logu, heslo nikdy. */
function sendMail(string $to, string $subject, string $html, string $text, string $replyTo = ''): bool
{
    require_once __DIR__ . '/../vendor/autoload.php';

    try {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 10;

        if (defined('MAIL_SMTP_HOST') && MAIL_SMTP_HOST !== '') {
            $mail->isSMTP();
            $mail->Host = MAIL_SMTP_HOST;
            $mail->Port = defined('MAIL_SMTP_PORT') ? (int) MAIL_SMTP_PORT : 465;
            $mail->SMTPSecure = defined('MAIL_SMTP_SECURE') && MAIL_SMTP_SECURE === 'tls'
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->Username = defined('MAIL_SMTP_USER') ? MAIL_SMTP_USER : mailFromAddress();
            $mail->Password = defined('MAIL_SMTP_PASS') ? MAIL_SMTP_PASS : '';
        } else {
            $mail->isMail();
        }

        $mail->setFrom(mailFromAddress(), SITE_NAME);
        $mail->addAddress($to);
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyTo);
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $text;
        return $mail->send();
    } catch (\Throwable $e) {
        error_log('E-mail sa nepodarilo odoslať: ' . $e->getMessage());
        return false;
    }
}

/** Spoločný vzhľad e-mailov (inline štýly - e-mailoví klienti nepodporujú CSS súbory). */
function mailLayout(string $title, string $contentHtml, string $footerHtml, string $preheader = ''): string
{
    $color = MAIL_BRAND_COLOR;
    return '<!DOCTYPE html><html lang="' . e(lang()) . '"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . e($title) . '</title></head>'
        . '<body style="margin:0;padding:0;background:#f4efe8;font-family:Arial,Helvetica,sans-serif;color:#2b2420;">'
        . '<span style="display:none;max-height:0;overflow:hidden;opacity:0;">' . e($preheader) . '</span>'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4efe8;padding:24px 12px;"><tr><td align="center">'
        . '<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;">'
        . '<tr><td style="background:' . $color . ';padding:18px 28px;color:#ffffff;font-size:22px;font-weight:bold;">'
        . '<a href="' . e(SITE_URL) . '" style="color:#ffffff;text-decoration:none;">'
        . '<img src="' . e(SITE_URL) . '/assets/img/logo_tomino_w.png" alt="' . e(SITE_NAME) . '" width="180" height="58" style="display:block;border:0;width:180px;height:auto;color:#ffffff;font-size:22px;font-weight:bold;">'
        . '</a></td></tr>'
        . '<tr><td style="padding:28px;font-size:15px;line-height:1.6;">' . $contentHtml . '</td></tr>'
        . '<tr><td style="padding:16px 28px;background:#faf6f0;color:#7a6f66;font-size:12px;line-height:1.5;">' . $footerHtml . '</td></tr>'
        . '</table></td></tr></table></body></html>';
}

/** Tabuľka "popis: hodnota" - $rows je [popis => hodnota]. */
function mailRowsHtml(array $rows): string
{
    $html = '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:16px 0;">';
    foreach ($rows as $label => $value) {
        $html .= '<tr><td style="padding:6px 12px 6px 0;color:#7a6f66;white-space:nowrap;vertical-align:top;">' . e((string) $label) . '</td>'
            . '<td style="padding:6px 0;font-weight:bold;">' . e((string) $value) . '</td></tr>';
    }
    return $html . '</table>';
}

function mailRowsText(array $rows): string
{
    $text = '';
    foreach ($rows as $label => $value) {
        $text .= $label . ': ' . $value . "\n";
    }
    return $text;
}

/**
 * E-mail pre prevádzku. $rows = údaje z formulára, $message = voľný text.
 * Vráti [html, text]. Prevádzka číta po slovensky, texty sa neprekladajú.
 */
function adminMailContent(string $title, array $rows, string $message, string $email, string $phone): array
{
    $color = MAIL_BRAND_COLOR;
    $buttons = '';
    if ($email !== '') {
        $buttons .= '<a href="mailto:' . e($email) . '" style="display:inline-block;margin:0 8px 8px 0;padding:10px 18px;background:' . $color . ';color:#ffffff;text-decoration:none;border-radius:4px;font-weight:bold;">Odpovedať e-mailom</a>';
    }
    if ($phone !== '') {
        $buttons .= '<a href="tel:' . e(preg_replace('/[^0-9+]/', '', $phone)) . '" style="display:inline-block;margin:0 8px 8px 0;padding:10px 18px;border:2px solid ' . $color . ';color:' . $color . ';text-decoration:none;border-radius:4px;font-weight:bold;">Zavolať</a>';
    }

    $messageHtml = $message !== ''
        ? '<div style="margin:16px 0;padding:14px 16px;background:#faf6f0;border-left:4px solid ' . $color . ';">' . nl2br(e($message)) . '</div>'
        : '';
    $langNote = lang() !== 'sk'
        ? '<p style="margin:12px 0;padding:10px 14px;background:#fff4d6;border-radius:4px;">Správa prišla z maďarskej verzie webu - zákazník zrejme hovorí po maďarsky.</p>'
        : '';

    $html = mailLayout(
        $title,
        '<h1 style="margin:0 0 8px;font-size:20px;">' . e($title) . '</h1>' . mailRowsHtml($rows) . $messageHtml . $langNote . '<div style="margin-top:20px;">' . $buttons . '</div>',
        'Správa bola odoslaná z formulára na webe ' . e(SITE_URL) . ' dňa ' . date('j. n. Y H:i') . '. Na tento e-mail môžete odpovedať priamo - odpoveď pôjde zákazníkovi.',
        $title
    );

    $text = $title . "\n\n" . mailRowsText($rows) . ($message !== '' ? "\nSpráva:\n" . $message . "\n" : '')
        . (lang() !== 'sk' ? "\nPozor: správa prišla z maďarskej verzie webu - zákazník zrejme hovorí po maďarsky.\n" : '');

    return [$html, $text];
}

/**
 * Potvrdenie pre zákazníka v jazyku formulára. Neobsahuje text, ktorý
 * zákazník napísal (aby sa cez formulár nedali posielať cudzie texty
 * tretím osobám) - iba zhrnutie dopytu na akciu a kontakt na prevádzku.
 */
function customerMailContent(array $summaryRows): array
{
    $color = MAIL_BRAND_COLOR;
    $subject = t('Ďakujeme za vašu správu') . ' - ' . SITE_NAME;

    $intro = t('Dobrý deň,');
    $thanks = t('ďakujeme, že ste nás kontaktovali. Vašu správu sme prijali a ozveme sa vám čo najskôr.');
    $urgent = t('Ak je vec naliehavá, zavolajte nám:');
    $ignore = t('Túto správu ste dostali, pretože ste vyplnili formulár na našom webe. Ak ste ho nevyplnili vy, tento e-mail môžete ignorovať.');

    $summaryHtml = '';
    $summaryText = '';
    if ($summaryRows) {
        $summaryHtml = '<h2 style="margin:20px 0 0;font-size:16px;">' . e(t('Zhrnutie vášho dopytu')) . '</h2>' . mailRowsHtml($summaryRows);
        $summaryText = "\n" . t('Zhrnutie vášho dopytu') . "\n" . mailRowsText($summaryRows);
    }

    $contactHtml = '<p style="margin:20px 0 0;">' . e($urgent) . ' <a href="tel:' . e(SITE_PHONE_TEL) . '" style="color:' . $color . ';font-weight:bold;text-decoration:none;">' . e(SITE_PHONE) . '</a><br>'
        . e(SITE_ADDRESS_FULL) . '</p>';

    $html = mailLayout(
        $subject,
        '<p style="margin:0 0 12px;">' . e($intro) . '</p><p style="margin:0;">' . e($thanks) . '</p>' . $summaryHtml . $contactHtml,
        e($ignore) . '<br>' . e(SITE_URL),
        $thanks
    );
    $text = $intro . "\n\n" . $thanks . "\n" . $summaryText . "\n" . $urgent . ' ' . SITE_PHONE . "\n" . SITE_ADDRESS_FULL . "\n\n" . $ignore . "\n" . SITE_URL . "\n";

    return [$subject, $html, $text];
}
