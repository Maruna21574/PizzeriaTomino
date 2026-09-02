<?php
/**
 * Centrálna knižnica SVG ikon - nahrádza emoji naprieč celým webom.
 *
 * icon($name)     - jednofarebné (currentColor) UI ikony pre texty, tlačidlá,
 *                    kontakt, kroky atď. Farbu preberajú z okolitého CSS.
 * toppingIcon()   - definície farebných ilustračných ikon prísad pre
 *                    "Poskladaj si pizzu" (vlastna-pizza.php), vykresľované
 *                    ako <symbol> sprite a znovupoužívané cez <use>.
 */

function icon(string $name, string $class = 'icon'): string
{
    $line = [
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15.5 14"/>',
        'flame' => '<path d="M12 2c-1.5 3-4 5-4 8.5A4 4 0 0 0 12 15a4 4 0 0 0 4-4.5c0-1-.3-1.8-.7-2.6 1.2 1 2.7 3 2.7 5.6A6 6 0 0 1 6 13.5C6 8 9 5 12 2z" fill="currentColor" stroke="none"/>',
        'truck' => '<rect x="1" y="4" width="14" height="12"/><path d="M15 8h4l3 3v5h-7z"/><circle cx="5.5" cy="19" r="2"/><circle cx="17.5" cy="19" r="2"/>',
        'cash' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/>',
        'card' => '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
        'leaf' => '<ellipse cx="12" cy="12" rx="9" ry="5" transform="rotate(-45 12 12)"/><line x1="6" y1="18" x2="18" y2="6"/>',
        'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="currentColor" stroke="none"/>',
        'home' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'chef-hat' => '<circle cx="8" cy="8" r="4"/><circle cx="12" cy="6" r="4.5"/><circle cx="16" cy="8" r="4"/><rect x="7" y="10" width="10" height="8" rx="1"/><line x1="7" y1="18" x2="17" y2="18"/>',
        'pizza' => '<path d="M12 2 2 20h20L12 2z"/><path d="M4 18c1-1 2-1 3 0s2 1 3 0 2-1 3 0 2 1 3 0 2-1 3 0"/><circle cx="10" cy="12" r="0.9" fill="currentColor" stroke="none"/><circle cx="14" cy="15" r="0.9" fill="currentColor" stroke="none"/><circle cx="12" cy="9" r="0.9" fill="currentColor" stroke="none"/>',
        'pasta' => '<path d="M3 11h18a9 7 0 0 1-18 0z"/><ellipse cx="12" cy="11" rx="9" ry="2"/><path d="M8 9c.8-.8 1.6.8 2.4 0s1.6-.8 2.4 0 1.6.8 2.4 0"/>',
        'salad' => '<path d="M3 11h18a9 7 0 0 1-18 0z"/><ellipse cx="12" cy="11" rx="9" ry="2"/><path d="M9 8c1-2 3-3 5-2"/><circle cx="9" cy="9" r="0.9" fill="currentColor" stroke="none"/><circle cx="15" cy="9" r="0.9" fill="currentColor" stroke="none"/>',
        'cup' => '<path d="M6 3h12l-1 15a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 3z"/><line x1="5" y1="3" x2="19" y2="3"/><line x1="9" y1="8" x2="15" y2="8"/>',
        'cake' => '<path d="M3 20h18l-2-7H5l-2 7z"/><path d="M6 13l6-9 6 9"/><circle cx="12" cy="4" r="1.2" fill="currentColor" stroke="none"/>',
        'garlic' => '<path d="M12 3c3 2 5 5 5 9a5 5 0 0 1-10 0c0-4 2-7 5-9z"/><line x1="12" y1="3" x2="12" y2="6"/><line x1="9.5" y1="9" x2="9.5" y2="14"/><line x1="14.5" y1="9" x2="14.5" y2="14"/>',
        'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'mail' => '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22 6 12 13 2 6"/>',
        'cart' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
        'external-link' => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
        'trash' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'minus' => '<line x1="5" y1="12" x2="19" y2="12"/>',
        'plus' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'menu' => '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>',
        'chevron-down' => '<polyline points="6 9 12 15 18 9"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><line x1="12" y1="11" x2="12" y2="16"/><circle cx="12" cy="7.5" r="0.9" fill="currentColor" stroke="none"/>',
        'x' => '<line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/>',
        'chevron-left' => '<polyline points="15 18 9 12 15 6"/>',
        'chevron-right' => '<polyline points="9 18 15 12 9 6"/>',
        'zoom-in' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>',
    ];

    if (!isset($line[$name])) {
        return '';
    }

    return sprintf(
        '<svg class="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
        e($class),
        $line[$name]
    );
}

/**
 * Farebné ilustračné ikony prísad pre pizza builder. Vracia pole
 * [id => vnútorný SVG markup] - používa sa na vykreslenie <symbol> spritu
 * (renderToppingSprite) aj samostatne v chip tlačidlách cez <use>.
 */
function toppingIconDefs(): array
{
    return [
        'sunka' => '<circle cx="12" cy="12" r="9" fill="#f3a9a0"/><path d="M6 12c2-3 10-3 12 0" stroke="#ffffff" stroke-width="1.4" fill="none" stroke-linecap="round"/>',
        'slanina' => '<rect x="3" y="7" width="18" height="10" rx="4" fill="#e8998b"/><path d="M4 10c4 2 12-2 16 0M4 14c4 2 12-2 16 0" stroke="#c65c4d" stroke-width="1.3" fill="none" stroke-linecap="round"/>',
        'kuracie' => '<circle cx="11" cy="11" r="7" fill="#e3b57a"/><rect x="14" y="15" width="3" height="7" rx="1.5" fill="#e3b57a"/><circle cx="17.5" cy="21" r="2" fill="#e3b57a"/>',
        'salama' => '<circle cx="12" cy="12" r="9" fill="#b53a35"/><circle cx="9" cy="10" r="1.1" fill="#7a211d"/><circle cx="15" cy="9" r="1.1" fill="#7a211d"/><circle cx="12" cy="15" r="1.1" fill="#7a211d"/>',
        'tunak' => '<circle cx="12" cy="12" r="9" fill="#7c93a3"/><path d="M6 11a7 4 0 0 1 12 0" stroke="#c7d4dc" stroke-width="1.3" fill="none" stroke-linecap="round"/>',
        'mozzarella-extra' => '<circle cx="12" cy="12" r="9" fill="#fdf6e8"/><path d="M8 9c1 2 1 4 0 6M16 9c-1 2-1 4 0 6" stroke="#eadfc4" stroke-width="1.2" fill="none" stroke-linecap="round"/>',
        'gorgonzola' => '<circle cx="12" cy="12" r="9" fill="#f5eeda"/><path d="M8 9c1 1 0 2 1 3s-1 2 0 3M14 8c1 1 0 2 1 3s-1 2 0 3" stroke="#6f9c93" stroke-width="1.1" fill="none" stroke-linecap="round"/>',
        'parmezan' => '<circle cx="12" cy="12" r="9" fill="#f6e6a8"/><circle cx="9" cy="9" r="0.8" fill="#d9bd63"/><circle cx="14" cy="10" r="0.8" fill="#d9bd63"/><circle cx="11" cy="14" r="0.8" fill="#d9bd63"/><circle cx="15" cy="14" r="0.8" fill="#d9bd63"/>',
        'eidam' => '<circle cx="12" cy="12" r="9" fill="#f2c14e"/><circle cx="9" cy="10" r="1.3" fill="#fdf1cf"/><circle cx="15" cy="12" r="1" fill="#fdf1cf"/><circle cx="11" cy="15" r="1.1" fill="#fdf1cf"/>',
        'huby' => '<path d="M4 11a8 5 0 0 1 16 0z" fill="#e4d4b8"/><rect x="10" y="11" width="4" height="7" rx="1.5" fill="#fbf3e4"/>',
        'cibula' => '<circle cx="12" cy="12" r="9" fill="#d9c3e0"/><circle cx="12" cy="12" r="6" fill="none" stroke="#b48fc2" stroke-width="1"/><circle cx="12" cy="12" r="3" fill="none" stroke="#b48fc2" stroke-width="1"/>',
        'paprika' => '<circle cx="12" cy="12" r="9" fill="#7bb661"/><circle cx="12" cy="12" r="4" fill="#fdf6e8"/>',
        'kukurica' => '<circle cx="12" cy="12" r="9" fill="#f4d35e"/><circle cx="9" cy="9" r="1" fill="#d8ad2c"/><circle cx="13" cy="8" r="1" fill="#d8ad2c"/><circle cx="16" cy="10" r="1" fill="#d8ad2c"/><circle cx="9" cy="13" r="1" fill="#d8ad2c"/><circle cx="13" cy="13" r="1" fill="#d8ad2c"/><circle cx="16" cy="15" r="1" fill="#d8ad2c"/>',
        'olivy' => '<circle cx="12" cy="12" r="8" fill="#2f2a1e"/><circle cx="12" cy="12" r="3" fill="#e8b7a6"/>',
        'rukola' => '<path d="M12 4c5 2 7 8 3 14-6-1-8-9-3-14z" fill="#5c9a4b"/><line x1="12" y1="6" x2="13" y2="16" stroke="#3f6e33" stroke-width="1"/>',
        'cherry' => '<circle cx="12" cy="13" r="7" fill="#d94f3d"/><path d="M12 6c-1-1-1-2 0-3 1 1 1 2 0 3z" fill="#5c9a4b"/><circle cx="10" cy="11" r="1" fill="#f0a293"/>',
        'jalapenos' => '<circle cx="12" cy="12" r="9" fill="#8fc65a"/><circle cx="12" cy="12" r="4.5" fill="#e9f2c9"/><circle cx="10" cy="11" r="0.8" fill="#c9d98a"/><circle cx="13" cy="13" r="0.8" fill="#c9d98a"/>',
        'ananas' => '<path d="M4 12a8 8 0 0 1 16 0 8 8 0 0 1-16 0z" fill="#f2c230"/><path d="M6 10l4 4M10 10l4 4M14 10l4 4M6 14l4-4M10 14l4-4M14 14l4-4" stroke="#d9a71b" stroke-width="0.8"/>',
        'cesnak' => '<path d="M12 4c3 2 5 5 5 9a5 5 0 0 1-10 0c0-4 2-7 5-9z" fill="#fdf8ea"/><line x1="12" y1="4" x2="12" y2="8" stroke="#e6dcc0" stroke-width="1"/><line x1="9.5" y1="10" x2="9.5" y2="15" stroke="#e6dcc0" stroke-width="1"/><line x1="14.5" y1="10" x2="14.5" y2="15" stroke="#e6dcc0" stroke-width="1"/>',
        'oregano' => '<circle cx="8" cy="9" r="1.4" fill="#5c8a3a"/><circle cx="14" cy="8" r="1.6" fill="#6b9c46"/><circle cx="11" cy="13" r="1.3" fill="#5c8a3a"/><circle cx="16" cy="14" r="1.4" fill="#6b9c46"/><circle cx="9" cy="16" r="1.1" fill="#5c8a3a"/>',
        'chilli' => '<circle cx="8" cy="9" r="1.4" fill="#c8452f"/><circle cx="14" cy="8" r="1.6" fill="#e2653e"/><circle cx="11" cy="13" r="1.3" fill="#c8452f"/><circle cx="16" cy="14" r="1.4" fill="#e2653e"/><circle cx="9" cy="16" r="1.1" fill="#c8452f"/>',
    ];
}

function renderToppingSprite(): string
{
    $out = '<svg class="sprite-defs" aria-hidden="true" focusable="false">';
    foreach (toppingIconDefs() as $id => $body) {
        $out .= sprintf('<symbol id="topping-%s" viewBox="0 0 24 24">%s</symbol>', e($id), $body);
    }
    $out .= '</svg>';
    return $out;
}

function toppingIconUse(string $id, string $class = 'topping-icon'): string
{
    return sprintf('<svg class="%s" aria-hidden="true"><use href="#topping-%s"></use></svg>', e($class), e($id));
}
