<?php
/**
 * Pomocné funkcie zdieľané naprieč webom.
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string
{
    return number_format($price, 2, ',', ' ') . ' ' . CURRENCY;
}

function getMenu(): array
{
    static $menu = null;
    if ($menu === null) {
        $menu = require __DIR__ . '/../data/menu.php';
    }
    return $menu;
}

function findMenuItem(string $id): ?array
{
    foreach (getMenu() as $category) {
        foreach ($category['items'] as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
    }
    return null;
}

function isActivePage(string $page): string
{
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    return $current === $page ? ' active' : '';
}

function getPizzaBuilder(): array
{
    static $builder = null;
    if ($builder === null) {
        $builder = require __DIR__ . '/../data/pizza-builder.php';
    }
    return $builder;
}

function findTopping(string $id): ?array
{
    foreach (getPizzaBuilder()['groups'] as $group) {
        foreach ($group['items'] as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
    }
    return null;
}

function getAllergens(): array
{
    static $allergens = null;
    if ($allergens === null) {
        $allergens = require __DIR__ . '/../data/allergens.php';
    }
    return $allergens;
}

/**
 * Vráti popisky alergénov (napr. "1. Obilniny obsahujúce lepok") pre dané
 * číselné kódy položky z data/menu.php.
 */
function allergenLabels(array $codes): array
{
    $all = getAllergens();
    $labels = [];
    foreach ($codes as $code) {
        if (isset($all[$code])) {
            $labels[] = $code . '. ' . $all[$code];
        }
    }
    return $labels;
}

/**
 * Vykreslí info tlačidlo na kartičke jedla, ktoré cez JS (assets/js/main.js)
 * otvorí modálne okno so zoznamom alergénov danej položky.
 */
function allergenTriggerButton(array $item): string
{
    $labels = allergenLabels($item['allergens'] ?? []);
    return sprintf(
        '<button type="button" class="food-card__info" data-allergen-trigger data-item-name="%s" data-allergen-labels=\'%s\' aria-label="Alergény - %s">%s</button>',
        e($item['name']),
        e(json_encode($labels, JSON_UNESCAPED_UNICODE)),
        e($item['name']),
        icon('info')
    );
}

function isOpenNow(): bool
{
    // Prevádzka je otvorená denne 10:00 - 22:00 podľa includes/config.php.
    $hour = (int) date('G');
    return $hour >= 10 && $hour < 22;
}
