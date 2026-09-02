<?php
/**
 * Konfigurácia pre "Poskladaj si vlastnú pizzu" (vlastna-pizza.php).
 * Základ + skupiny prísad s cenami. Používa sa na vykreslenie stránky
 * AJ na serverové prepočítanie ceny v process_order.php - nikdy sa
 * nedôveruje cene poslanej z prehliadača. Vizuál prísady (farebná SVG
 * ikonka) je definovaný podľa id v includes/icons.php (toppingIconDefs).
 */

return [

    'base' => [
        'name'  => 'Vlastná pizza (základ: paradajky, mozzarella)',
        'price' => 5.90,
        'img'   => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?auto=format&fit=crop&w=900&q=80',
    ],

    'groups' => [
        'maso' => [
            'label' => 'Mäso',
            'items' => [
                ['id' => 'sunka',    'name' => 'Šunka',            'price' => 0.80],
                ['id' => 'slanina',  'name' => 'Slanina',          'price' => 0.90],
                ['id' => 'kuracie',  'name' => 'Kuracie mäso',     'price' => 1.00],
                ['id' => 'salama',   'name' => 'Pikantná saláma',  'price' => 0.90],
                ['id' => 'tunak',    'name' => 'Tuniak',           'price' => 1.10],
            ],
        ],
        'syry' => [
            'label' => 'Syry',
            'items' => [
                ['id' => 'mozzarella-extra', 'name' => 'Extra mozzarella', 'price' => 0.90],
                ['id' => 'gorgonzola',       'name' => 'Gorgonzola',       'price' => 1.00],
                ['id' => 'parmezan',         'name' => 'Parmezán',         'price' => 1.00],
                ['id' => 'eidam',            'name' => 'Eidam',            'price' => 0.80],
            ],
        ],
        'zelenina' => [
            'label' => 'Zelenina',
            'items' => [
                ['id' => 'huby',      'name' => 'Žampióny',         'price' => 0.60],
                ['id' => 'cibula',    'name' => 'Cibuľa',           'price' => 0.50],
                ['id' => 'paprika',   'name' => 'Paprika',          'price' => 0.60],
                ['id' => 'kukurica',  'name' => 'Kukurica',         'price' => 0.60],
                ['id' => 'olivy',     'name' => 'Čierne olivy',     'price' => 0.70],
                ['id' => 'rukola',    'name' => 'Rukola',           'price' => 0.70],
                ['id' => 'cherry',    'name' => 'Cherry paradajky', 'price' => 0.70],
                ['id' => 'jalapenos', 'name' => 'Jalapeños',        'price' => 0.70],
                ['id' => 'ananas',    'name' => 'Ananás',           'price' => 0.70],
            ],
        ],
        'ostatne' => [
            'label' => 'Ostatné',
            'items' => [
                ['id' => 'cesnak',  'name' => 'Cesnak',        'price' => 0.40],
                ['id' => 'oregano', 'name' => 'Oregano',       'price' => 0.30],
                ['id' => 'chilli',  'name' => 'Chilli vločky', 'price' => 0.30],
            ],
        ],
    ],

];
