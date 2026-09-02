<?php
/**
 * Dátová sada jedálneho lístka. Každá položka má id, kategóriu, názov,
 * popis (zloženie), cenu, gramáž (orientačná hmotnosť/objem hotového
 * pokrmu), prípadne štítky (napr. vegetariánska, pikantná), fotku
 * (z fotobanky Unsplash - voľne použiteľné, nahraď vlastnými produktovými
 * fotkami, keď budú k dispozícii) a zoznam alergénov (číselné kódy podľa
 * nariadenia EÚ č. 1169/2011 - pozri data/allergens.php).
 * Gramáž aj alergény sú orientačné - pred spustením webu si ich over/uprav
 * podľa skutočného zloženia a receptúr v prevádzke.
 */

return [

    'pizza' => [
        'label' => 'Pizza',
        'icon'  => 'pizza',
        'items' => [
            [
                'id' => 'p1', 'name' => 'Margherita',
                'desc' => 'Paradajkový základ, mozzarella, čerstvá bazalka, olivový olej',
                'price' => 6.90, 'weight' => '420 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p2', 'name' => 'Prosciutto e Funghi',
                'desc' => 'Paradajkový základ, mozzarella, šunka, čerstvé huby',
                'price' => 7.90, 'weight' => '480 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p3', 'name' => 'Quattro Formaggi',
                'desc' => 'Mozzarella, gorgonzola, parmezán, eidam, smotanový základ',
                'price' => 8.50, 'weight' => '460 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1600028068383-ea11a7a101f3?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p4', 'name' => 'Diavola',
                'desc' => 'Paradajkový základ, mozzarella, pikantná saláma, čili papričky',
                'price' => 8.20, 'weight' => '470 g', 'tags' => ['pikantná'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p5', 'name' => 'Capricciosa',
                'desc' => 'Šunka, žampióny, artičoky, olivy, mozzarella',
                'price' => 8.60, 'weight' => '500 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1594007654729-407eedc4be65?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p6', 'name' => 'Vegetariana',
                'desc' => 'Paprika, cuketa, cibuľa, kukurica, olivy, cherry paradajky, mozzarella',
                'price' => 7.80, 'weight' => '490 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1571066811602-716837d681de?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p7', 'name' => 'Tonno',
                'desc' => 'Tuniak, červená cibuľa, čierne olivy, mozzarella',
                'price' => 8.40, 'weight' => '480 g', 'tags' => [], 'allergens' => [1, 4, 7],
                'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p8', 'name' => 'Hawaii',
                'desc' => 'Šunka, ananás, mozzarella',
                'price' => 7.90, 'weight' => '470 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1590947132387-155cc02f3212?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p9', 'name' => 'Salame Piccante',
                'desc' => 'Pikantná saláma, mozzarella, paradajkový základ',
                'price' => 7.60, 'weight' => '460 g', 'tags' => ['pikantná'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p10', 'name' => 'Quattro Stagioni',
                'desc' => 'Štyri sektory: šunka, huby, artičoky, olivy',
                'price' => 8.70, 'weight' => '510 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p11', 'name' => 'Frutti di Mare',
                'desc' => 'Morské plody, cesnak, petržlenová vňať, paradajkový základ',
                'price' => 9.90, 'weight' => '520 g', 'tags' => [], 'allergens' => [1, 2, 7, 14],
                'img' => 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p12', 'name' => 'Tominno Špeciál',
                'desc' => 'Kuracie mäso, slanina, kukurica, cibuľa, cesnakový dresing, syrový okraj',
                'price' => 9.20, 'weight' => '530 g', 'tags' => ['obľúbená'], 'allergens' => [1, 3, 7],
                'img' => 'https://images.unsplash.com/photo-1552539618-7eec9b4d1796?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p13', 'name' => 'Parmská',
                'desc' => 'Parmská šunka, rukola, plátky parmezánu, cherry paradajky',
                'price' => 9.50, 'weight' => '480 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1601924582970-9238bcb495d9?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p14', 'name' => 'Gorgonzola e Noci',
                'desc' => 'Gorgonzola, vlašské orechy, med, mozzarella',
                'price' => 8.80, 'weight' => '470 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7, 8],
                'img' => 'https://images.unsplash.com/photo-1607013251379-e6eecfffe234?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'p15', 'name' => 'Mexická',
                'desc' => 'Jalapeños, kukurica, paprika, pikantná saláma, cibuľa',
                'price' => 8.60, 'weight' => '490 g', 'tags' => ['pikantná'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

    'cestoviny' => [
        'label' => 'Cestoviny',
        'icon'  => 'pasta',
        'items' => [
            [
                'id' => 'c1', 'name' => 'Spaghetti Bolognese',
                'desc' => 'Domáca mäsová ragú omáčka, parmezán',
                'price' => 6.90, 'weight' => '380 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'c2', 'name' => 'Penne Carbonara',
                'desc' => 'Slanina, smotana, vaječný žĺtok, parmezán, čierne korenie',
                'price' => 6.90, 'weight' => '380 g', 'tags' => [], 'allergens' => [1, 3, 7],
                'img' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'c3', 'name' => 'Lasagne Bolognese',
                'desc' => 'Vrstvené cestoviny, mäsová omáčka, bešamel, syr',
                'price' => 7.50, 'weight' => '400 g', 'tags' => [], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'c4', 'name' => 'Spaghetti Aglio e Olio',
                'desc' => 'Cesnak, olivový olej, čili, petržlenová vňať',
                'price' => 5.90, 'weight' => '350 g', 'tags' => ['vegetariánska'], 'allergens' => [1],
                'img' => 'https://images.unsplash.com/photo-1608219992759-8d74ed8d76eb?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'c5', 'name' => 'Penne Arrabbiata',
                'desc' => 'Paradajková omáčka, cesnak, čili, bazalka',
                'price' => 6.20, 'weight' => '360 g', 'tags' => ['vegetariánska', 'pikantná'], 'allergens' => [1],
                'img' => 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

    'salaty' => [
        'label' => 'Šaláty',
        'icon'  => 'salad',
        'items' => [
            [
                'id' => 's1', 'name' => 'Caprese',
                'desc' => 'Paradajky, mozzarella, bazalka, olivový olej',
                'price' => 5.90, 'weight' => '220 g', 'tags' => ['vegetariánska'], 'allergens' => [7],
                'img' => 'https://images.unsplash.com/photo-1608897013039-887f21d8c804?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 's2', 'name' => 'Grécky šalát',
                'desc' => 'Uhorka, paradajka, paprika, cibuľa, olivy, syr Feta',
                'price' => 6.50, 'weight' => '280 g', 'tags' => ['vegetariánska'], 'allergens' => [7],
                'img' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 's3', 'name' => 'Šalát s kuracím mäsom',
                'desc' => 'Grilované kuracie prsia, mix šalátov, cherry paradajky, parmezán',
                'price' => 6.90, 'weight' => '320 g', 'tags' => [], 'allergens' => [7],
                'img' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 's4', 'name' => 'Rukolový šalát s parmezánom',
                'desc' => 'Rukola, hoblovaný parmezán, cherry paradajky, balzamikový krém',
                'price' => 6.20, 'weight' => '200 g', 'tags' => ['vegetariánska'], 'allergens' => [7, 12],
                'img' => 'https://images.unsplash.com/photo-1600335895229-6e75511892c8?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

    'napoje' => [
        'label' => 'Nápoje',
        'icon'  => 'cup',
        'items' => [
            [
                'id' => 'n1', 'name' => 'Coca-Cola 0,5 l', 'desc' => '',
                'price' => 1.80, 'weight' => '0,5 l', 'tags' => [], 'allergens' => [],
                'img' => 'https://images.unsplash.com/photo-1554866585-cd94860890b7?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'n2', 'name' => 'Fanta 0,5 l', 'desc' => '',
                'price' => 1.80, 'weight' => '0,5 l', 'tags' => [], 'allergens' => [],
                'img' => 'https://images.unsplash.com/photo-1437418747212-8d9709afab22?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'n3', 'name' => 'Voda perlivá / neperlivá 0,5 l', 'desc' => '',
                'price' => 1.50, 'weight' => '0,5 l', 'tags' => [], 'allergens' => [],
                'img' => 'https://images.unsplash.com/photo-1560023907-5f339617ea30?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'n4', 'name' => 'Ľadový čaj 0,5 l', 'desc' => '',
                'price' => 1.80, 'weight' => '0,5 l', 'tags' => [], 'allergens' => [],
                'img' => 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'n5', 'name' => 'Pivo Šariš 0,5 l', 'desc' => '',
                'price' => 2.00, 'weight' => '0,5 l', 'tags' => [], 'allergens' => [1],
                'img' => 'https://images.unsplash.com/photo-1608270586620-248524c67de9?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

    'dezerty' => [
        'label' => 'Dezerty',
        'icon'  => 'cake',
        'items' => [
            [
                'id' => 'd1', 'name' => 'Tiramisu',
                'desc' => 'Domáci taliansky dezert s mascarpone a espressom',
                'price' => 3.50, 'weight' => '120 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 3, 7],
                'img' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'd2', 'name' => 'Panna Cotta',
                'desc' => 'Smotanový dezert s lesným ovocím',
                'price' => 3.20, 'weight' => '100 g', 'tags' => ['vegetariánska'], 'allergens' => [7],
                'img' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'd3', 'name' => 'Nutella Pizza',
                'desc' => 'Sladká pizza s Nutellou a ovocím',
                'price' => 5.50, 'weight' => '300 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7, 8],
                'img' => 'https://images.unsplash.com/photo-1541599468348-e96984315921?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

    'prilohy' => [
        'label' => 'Prílohy a dipy',
        'icon'  => 'garlic',
        'items' => [
            [
                'id' => 'x1', 'name' => 'Cesnakový chlieb', 'desc' => 'S bylinkovým maslom a syrom',
                'price' => 2.90, 'weight' => '200 g', 'tags' => ['vegetariánska'], 'allergens' => [1, 7],
                'img' => 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'x2', 'name' => 'Cesnakový dip', 'desc' => '',
                'price' => 0.80, 'weight' => '30 g', 'tags' => [], 'allergens' => [3, 7],
                'img' => 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'id' => 'x3', 'name' => 'BBQ / kečupový dip', 'desc' => '',
                'price' => 0.80, 'weight' => '30 g', 'tags' => [], 'allergens' => [10],
                'img' => 'https://images.unsplash.com/photo-1607330289024-1535c6b4e1c1?auto=format&fit=crop&w=900&q=80',
            ],
        ],
    ],

];
