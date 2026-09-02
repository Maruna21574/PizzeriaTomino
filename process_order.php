<?php
/**
 * Prijme objednávku z objednavka.php (JS fetch, JSON), overí ju,
 * vypočíta konečnú cenu SERVEROVO (nedôveruje cenám z frontendu),
 * uloží ju do /orders a pošle notifikáciu e-mailom cez PHP mail().
 * Platba je vždy na dobierku - nepoužíva sa žiadna platobná brána.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=UTF-8');

function respond(bool $ok, string $message, array $extra = []): void
{
    echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Nepovolená metóda.');
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    respond(false, 'Neplatné dáta objednávky.');
}

// Honeypot
if (!empty($data['website'])) {
    respond(true, 'OK');
}

$fullName = trim((string) ($data['fullName'] ?? ''));
$phone    = trim((string) ($data['phone'] ?? ''));
$email    = trim((string) ($data['email'] ?? ''));
$street   = trim((string) ($data['street'] ?? ''));
$city     = trim((string) ($data['city'] ?? ''));
$note     = trim((string) ($data['note'] ?? ''));
$payment  = (string) ($data['payment'] ?? '');
$agree    = !empty($data['agree']);
$cartIn   = is_array($data['cart'] ?? null) ? $data['cart'] : [];

$errors = [];
if ($fullName === '') $errors[] = 'Vyplňte meno a priezvisko.';
if ($phone === '' || !preg_match('/^[0-9+\s\/]{7,20}$/', $phone)) $errors[] = 'Zadajte platné telefónne číslo.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Zadajte platný e-mail.';
if ($street === '') $errors[] = 'Vyplňte ulicu a číslo.';
if ($city === '') $errors[] = 'Vyplňte obec / mesto.';
if (!array_key_exists($payment, PAYMENT_METHODS)) $errors[] = 'Vyberte spôsob platby.';
if (!$agree) $errors[] = 'Musíte súhlasiť so spracovaním osobných údajov.';
if (empty($cartIn)) $errors[] = 'Košík je prázdny.';

// Znovu overíme každú položku a cenu voči dátam v data/menu.php,
// resp. voči data/pizza-builder.php pre vlastné pizze. Cena aj názov
// sa vždy dopočítajú tu na serveri - nikdy sa nepreberajú z prehliadača.
$orderItems = [];
$subtotal = 0.0;
foreach ($cartIn as $line) {
    $id = (string) ($line['id'] ?? '');
    $qty = max(1, min(50, (int) ($line['qty'] ?? 1)));

    if (!empty($line['custom'])) {
        $builder = getPizzaBuilder();
        $toppingIds = is_array($line['toppings'] ?? null) ? $line['toppings'] : [];
        $toppingNames = [];
        $price = (float) $builder['base']['price'];

        foreach (array_unique($toppingIds) as $toppingId) {
            $topping = findTopping((string) $toppingId);
            if ($topping) {
                $price += $topping['price'];
                $toppingNames[] = $topping['name'];
            }
        }

        $name = $toppingNames ? 'Vlastná pizza' : 'Vlastná pizza (základ)';
        $lineTotal = $price * $qty;
        $subtotal += $lineTotal;
        $orderItems[] = [
            'id' => $id,
            'name' => $name,
            'toppings' => $toppingNames,
            'price' => round($price, 2),
            'qty' => $qty,
            'lineTotal' => round($lineTotal, 2),
        ];
        continue;
    }

    $menuItem = findMenuItem($id);
    if (!$menuItem) {
        continue;
    }
    $lineTotal = $menuItem['price'] * $qty;
    $subtotal += $lineTotal;
    $orderItems[] = [
        'id' => $id,
        'name' => $menuItem['name'],
        'price' => $menuItem['price'],
        'qty' => $qty,
        'lineTotal' => round($lineTotal, 2),
    ];
}

if (empty($orderItems)) {
    $errors[] = 'Košík neobsahuje žiadne platné položky.';
}

if (!empty($errors)) {
    http_response_code(422);
    respond(false, implode(' ', $errors));
}

if ($subtotal < DELIVERY_MIN_ORDER) {
    http_response_code(422);
    respond(false, sprintf('Minimálna hodnota objednávky je %s.', formatPrice(DELIVERY_MIN_ORDER)));
}

$deliveryFee = $subtotal >= DELIVERY_FREE_FROM ? 0.0 : DELIVERY_FEE;
$total = round($subtotal + $deliveryFee, 2);

$orderId = date('Ymd-His') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));

$orderRecord = [
    'orderId' => $orderId,
    'createdAt' => date('c'),
    'customer' => compact('fullName', 'phone', 'email', 'street', 'city', 'note'),
    'payment' => $payment,
    'items' => $orderItems,
    'subtotal' => round($subtotal, 2),
    'deliveryFee' => round($deliveryFee, 2),
    'total' => $total,
];

// Uloženie objednávky do súboru (jednoduchý log, funguje aj bez databázy).
$ordersDir = __DIR__ . '/orders';
if (!is_dir($ordersDir)) {
    @mkdir($ordersDir, 0755, true);
}
@file_put_contents(
    $ordersDir . '/' . $orderId . '.json',
    json_encode($orderRecord, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

// E-mail pre prevádzku
$itemLines = '';
foreach ($orderItems as $it) {
    $itemLines .= sprintf("- %s x%d = %s\n", $it['name'], $it['qty'], formatPrice($it['lineTotal']));
    if (!empty($it['toppings'])) {
        $itemLines .= sprintf("  (prísady: %s)\n", implode(', ', $it['toppings']));
    }
}

$paymentLabel = PAYMENT_METHODS[$payment];

$body = "Nová objednávka #{$orderId}\n\n";
$body .= "Zákazník: {$fullName}\n";
$body .= "Telefón: {$phone}\n";
$body .= "E-mail: " . ($email !== '' ? $email : '-') . "\n";
$body .= "Adresa doručenia: {$street}, {$city}\n";
$body .= "Poznámka: " . ($note !== '' ? $note : '-') . "\n\n";
$body .= "Položky:\n{$itemLines}\n";
$body .= "Medzisúčet: " . formatPrice($subtotal) . "\n";
$body .= "Doprava: " . formatPrice($deliveryFee) . "\n";
$body .= "Spolu: " . formatPrice($total) . "\n\n";
$body .= "Spôsob platby: {$paymentLabel}\n";

$host = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'pizzeriatominno.sk');
$headers = [
    'From: ' . SITE_NAME . ' <noreply@' . $host . '>',
    'Content-Type: text/plain; charset=UTF-8',
];
if ($email !== '') {
    $headers[] = 'Reply-To: ' . $email;
}

@mail(SITE_EMAIL, "Nová objednávka #{$orderId} - " . SITE_NAME, $body, implode("\r\n", $headers));

// Potvrdenie zákazníkovi (ak zadal e-mail)
if ($email !== '') {
    $customerBody = "Dobrý deň {$fullName},\n\n";
    $customerBody .= "ďakujeme za vašu objednávku č. {$orderId} v " . SITE_NAME . ".\n\n";
    $customerBody .= "Položky:\n{$itemLines}\n";
    $customerBody .= "Spolu na úhradu: " . formatPrice($total) . " (" . $paymentLabel . ")\n\n";
    $customerBody .= "Adresa doručenia: {$street}, {$city}\n";
    $customerBody .= "V prípade otázok nás kontaktujte na " . SITE_PHONE . ".\n\n";
    $customerBody .= "Ďakujeme, tím " . SITE_NAME;

    $customerHeaders = [
        'From: ' . SITE_NAME . ' <noreply@' . $host . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ];
    @mail($email, 'Potvrdenie objednávky #' . $orderId . ' - ' . SITE_NAME, $customerBody, implode("\r\n", $customerHeaders));
}

respond(true, 'Objednávka bola úspešne odoslaná.', ['orderId' => $orderId, 'total' => $total]);
