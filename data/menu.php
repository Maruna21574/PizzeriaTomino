<?php
/**
 * Jedálny lístok Pizzeria Tominno - prepísaný z tlačeného menu prevádzky.
 *
 * Každá kategória má názov, ikonu, voliteľný podnadpis/poznámku a fotku
 * (assets/img/foto). Položka má číslo (iba pizza), názov, gramáž, cenu,
 * zloženie a alergény (číselné kódy podľa nariadenia EÚ č. 1169/2011 -
 * pozri data/allergens.php). Položka s viacerými veľkosťami používa
 * 'variants' namiesto 'weight' a 'price'. Kľúč 'photo' pridá k položke
 * vlastnú fotku - iba tam, kde fotka naozaj zobrazuje danú položku.
 */

$napoli = 'Pomodoro pelato Rosso Gargano, Mozzarella Fiordilatte Taglio Napoli';

return [

    'pizza' => [
        'label'    => 'Neapolská pizza',
        'subtitle' => 'Pečená v peci na drevo',
        'icon'     => 'pizza',
        'photo'    => 'pizza-margherita-pec',
        'note'     => 'Cesto z talianskej múky fermentujeme 48 hodín. Pizzu pečieme pri vysokej teplote v peci na drevo - tak, ako sa to robí v Neapole.',
        'items' => [
            ['num' => 1,  'name' => 'Margherita',        'weight' => '450 g', 'price' => 6.90,  'desc' => "$napoli, čerstvá bazalka, olivový olej", 'allergens' => [1, 7], 'photo' => 'pizza-margherita'],
            ['num' => 2,  'name' => 'Kukuricová',        'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, šunka, kukurica, oregano", 'allergens' => [1, 7], 'photo' => 'pizza-kukuricova'],
            ['num' => 3,  'name' => 'Toscana',           'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, šunka, čerstvé šampiňóny, kukurica, oregano", 'allergens' => [1, 7]],
            ['num' => 4,  'name' => 'Pollo Bianco',      'weight' => '700 g', 'price' => 7.90,  'desc' => 'Smotanový základ, Mozzarella Fiordilatte Taglio Napoli, niva, brokolica, kuracie mäso, oregano', 'allergens' => [1, 7]],
            ['num' => 5,  'name' => 'Quatro Formaggi',   'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, encián, niva, parmezán, rukola, oregano", 'allergens' => [1, 7]],
            ['num' => 6,  'name' => 'Gazdovská',         'weight' => '770 g', 'price' => 8.50,  'desc' => "$napoli, saláma, slanina, cibuľa, čierne olivy, feferóny, oregano", 'allergens' => [1, 7]],
            ['num' => 7,  'name' => 'Salami',            'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, saláma, šampiňóny, oregano", 'allergens' => [1, 7]],
            ['num' => 8,  'name' => 'Carbonara',         'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, šunka, slanina, vajce, cesnak, parmezán Formaggio Bianco, oregano", 'allergens' => [1, 7]],
            ['num' => 9,  'name' => 'Tirolský špek',     'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, tirolský speck, cherry paradajky, rukola, parmezán Formaggio Bianco, oregano", 'allergens' => [1, 7]],
            ['num' => 10, 'name' => 'Tuniaková',         'weight' => '770 g', 'price' => 7.90,  'desc' => "$napoli, tuniak, červená cibuľa, zelené olivy, citrón, oregano", 'allergens' => [1, 7]],
            ['num' => 11, 'name' => 'Diavolo',           'weight' => '700 g', 'price' => 7.90,  'desc' => 'Mäsovo-pikantná zmes, Mozzarella Fiordilatte Taglio Napoli, čerstvé paradajky, paprika, parmezán Formaggio Bianco, oregano', 'allergens' => [1, 7]],
            ['num' => 12, 'name' => 'Tominno',           'weight' => '850 g', 'price' => 9.90,  'desc' => "$napoli, prosciutto crudo stagionato, čerstvé šampiňóny, kukurica, karamelizovaná cibuľa, pečená slanina, parmezán Formaggio Bianco, oregano", 'allergens' => [1, 7]],
            ['num' => 13, 'name' => 'S kozím syrom',     'weight' => '700 g', 'price' => 10.90, 'desc' => 'Smotanový základ, Mozzarella Fiordilatte Taglio Napoli, baby špenát, kozí syr, brusnicová omáčka', 'allergens' => [1, 7]],
            ['num' => 14, 'name' => 'Farmárska',         'weight' => '850 g', 'price' => 8.50,  'desc' => "$napoli, domáca klobása, cibuľa, niva, oregano", 'allergens' => [1, 7]],
            ['num' => 15, 'name' => 'Olivová',           'weight' => '700 g', 'price' => 7.90,  'desc' => "$napoli, šunka, cherry paradajky, mix olív, oregano", 'allergens' => [1, 7]],
            ['num' => 16, 'name' => 'Gorgonzola',        'weight' => '700 g', 'price' => 10.90, 'desc' => 'Smotanový základ, gorgonzola, med, marinované hrušky, zmes orechov, olivový olej', 'allergens' => [1, 7, 8], 'photo' => 'pizza-gorgonzola'],
            ['num' => 17, 'name' => 'Ventricina Piccante', 'weight' => '750 g', 'price' => 10.90, 'desc' => "$napoli, ventricina piccante, čierne olivy", 'allergens' => [1, 7]],
            ['num' => 18, 'name' => 'Burrata',           'weight' => '700 g', 'price' => 10.90, 'desc' => "$napoli, burrata, čerstvá bazalka", 'allergens' => [1, 7]],
        ],
    ],

    'pecivo' => [
        'label' => 'Pizza pečivo a bruschetta',
        'icon'  => 'garlic',
        'photo' => 'bruschetta',
        'items' => [
            ['name' => 'Pizza chlieb oregano', 'weight' => '280 g', 'price' => 5.99, 'desc' => 'Cesnakový', 'allergens' => [1]],
            ['name' => 'Pizza štangle',        'weight' => '280 g', 'price' => 5.99, 'desc' => '', 'allergens' => [1]],
            ['name' => 'Pizza pečivo',         'weight' => '1 ks',  'price' => 1.50, 'desc' => '', 'allergens' => [1]],
            ['name' => 'Bruschetta',           'weight' => '200 g', 'price' => 6.99, 'desc' => 'Zapekaná ciabatta, cibuľa, čerstvé paradajky, cesnak, bazalkové pesto, olivový olej, parmezán Formaggio Bianco', 'allergens' => [1], 'photo' => 'bruschetta'],
        ],
    ],

    'salaty' => [
        'label' => 'Šaláty',
        'icon'  => 'salad',
        'photo' => 'bufala-salat',
        'items' => [
            ['name' => 'Bufala šalát',                        'price' => 8.50, 'desc' => 'Polníček, šalát mix, rukola, cherry paradajky, sušené paradajky, prosciutto, bufala, pizza pečivo', 'allergens' => [7], 'photo' => 'bufala-salat'],
            ['name' => 'Caprese šalát',                       'price' => 5.50, 'desc' => 'Mozzarella, nakrájané paradajky, bazalkové listy, olivový olej a balzamiko', 'allergens' => [7]],
            ['name' => 'Šalát s kozím syrom',                 'price' => 8.50, 'desc' => 'Mix šalát, polníček, kozí syr, brusnicová omáčka, opražená slaninka', 'allergens' => [7]],
            ['name' => 'Kurací šalát s granátovým jablkom',   'price' => 8.50, 'desc' => 'Čerstvý špenát, polníček, cherry paradajky, citrón, kuracie mäso, niva syr, granátové jablko, chrumkavý chlebík', 'allergens' => [1, 7], 'photo' => 'kuraci-salat-granatove-jablko'],
        ],
    ],

    'burgery' => [
        'label' => 'Burgery',
        'icon'  => 'burger',
        'photo' => 'burgre-cierny-a-cerveny',
        'items' => [
            ['name' => 'Domáci hovädzí burger',                      'weight' => '380 g', 'price' => 7.90, 'desc' => 'Burger Buns žemľa, dresing, kyslá uhorka, čerstvá paradajka, zelený šalát, hovädzie mäso, cheddar plátky, karamelizovaná cibuľa, BBQ omáčka, polníček', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Špeciál hovädzí burger',                     'weight' => '450 g', 'price' => 9.90, 'desc' => 'Burger Buns žemľa čierna/červená, dresing, hovädzie mäso, cheddar plátky, kyslá uhorka, čerstvá paradajka, zelený šalát, chrumkavá cibuľa, slaninové chipsy, BBQ omáčka, polníček + zemiakové hranolky + omáčka podľa výberu z ponuky', 'allergens' => [1, 3, 7, 11], 'photo' => 'burger-cierny-s-hranolkami'],
            ['name' => 'Domáci hovädzí burger s tekutým cheddar syrom', 'weight' => '400 g', 'price' => 9.50, 'desc' => 'Burger Buns maslová žemľa, dresing, kyslá uhorka, čerstvá paradajka, zelený šalát, hovädzie mäso, cheddar plátky, slaninové chipsy, karamelizovaná cibuľa, BBQ omáčka, polníček + tekutý cheddar', 'allergens' => [1, 3, 7, 11], 'photo' => 'burger-tekuty-cheddar'],
            ['name' => 'Domáci hovädzí burger + volské oko',         'weight' => '450 g', 'price' => 9.50, 'desc' => 'Burger Buns maslová žemľa, slaninová omáčka, zelený šalát, hovädzie mäso, cheddar plátky, pražená slanina, BBQ omáčka, volské oko, polníček', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Domáci hovädzí burger s kozím syrom',        'weight' => '450 g', 'price' => 9.90, 'desc' => 'Burger Buns maslová žemľa, dresing, šalát, baby špenát, cvikla plátok, hovädzie mäso, kozí syr, brusnicová omáčka', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Tekvicový burger s hovädzím mäsom',          'weight' => '450 g', 'price' => 9.50, 'desc' => 'Burger Buns tekvicová žemľa, dresing, kyslá uhorka, rukola, paradajka, majonéza, tekvicová placka, hovädzie mäso, čerstvá cibuľa', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Burger kurací strips',                        'weight' => '480 g', 'price' => 9.50, 'desc' => 'Burger Buns maslová žemľa, majonéza, ľadový šalát, kyslá uhorka, BBQ omáčka, slaninové chipsy, kuracie stripsy', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Vegaburger',                                   'weight' => '300 g', 'price' => 6.90, 'desc' => 'Burger Buns žemľa, majonéza, zelený šalát, vyprážaný camembert, chrumkavá cibuľa, polníček', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Mini kurací burger',                          'weight' => '300 g', 'price' => 4.00, 'desc' => 'Burger Buns žemľa, majonéza, zelený šalát, kuracie mäso, karamelizovaná cibuľa, syr, polníček', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Detský burger',                               'weight' => '280 g', 'price' => 4.00, 'desc' => 'Burger Buns žemľa, kečup, šalát, kuracie mäso, syr, čerstvá uhorka, čerstvá paradajka + zemiakové smajlíky 4 ks', 'allergens' => [1, 3, 7, 11]],
            ['name' => 'Hot-dog',                                      'weight' => '280 g', 'price' => 6.50, 'desc' => 'Burger Buns maslový rožok, párok, ľadový šalát, paradajka, jalapeño paprička, BBQ, tekutý cheddar, polníček', 'allergens' => [1, 3, 7, 11]],
        ],
    ],

    'kebab' => [
        'label' => 'Kebab a grill',
        'icon'  => 'flame',
        'photo' => 'kebab-tanier',
        'items' => [
            ['name' => 'Kebab mini',              'weight' => '350 g', 'price' => 4.50, 'desc' => 'Čerstvé kuracie mäso, zelenina, dresing', 'allergens' => [3, 7]],
            ['name' => 'Kebab tanier',            'weight' => '700 g', 'price' => 7.90, 'desc' => 'Zemiakové hranolky/batáty, čerstvé kuracie mäso, zelenina, dresing', 'allergens' => [3, 7], 'photo' => 'kebab-tanier'],
            ['name' => 'Kebab v pizza chlebe',    'weight' => '700 g', 'price' => 6.90, 'desc' => 'Pizza chlieb, čerstvé kuracie mäso, šalát, čerstvá cibuľa, dresing', 'allergens' => [1, 3, 7], 'photo' => 'kebab-v-pizza-chlebe'],
            ['name' => 'Tortilla',                'weight' => '400 g', 'price' => 7.00, 'desc' => 'Tortilla, čerstvé kuracie mäso, zelenina, dresing', 'allergens' => [1, 3, 7]],
            ['name' => 'Tortilla s batátami a tekutým cheddar syrom', 'weight' => '500 g', 'price' => 8.50, 'desc' => 'Tortilla, čerstvé kuracie mäso, zelenina, batáty, tekutý cheddar', 'allergens' => [1, 3, 7]],
            ['name' => 'Tominno box',             'variants' => [['label' => 'Malý 1700 g', 'price' => 18.00], ['label' => 'Veľký 2200 g', 'price' => 20.00]], 'desc' => 'Zemiakové hranolky, čerstvé kuracie mäso, zelenina, dresing, pizza chlieb', 'allergens' => [1, 3, 7]],
            ['name' => 'Kurací gril',             'weight' => '550 g', 'price' => 8.50, 'desc' => 'Kuracie prsia, baklažán, cuketa, paprika, šampiňóny, cibuľa, paradajky, pizza pečivo', 'allergens' => [1, 3, 7], 'photo' => 'kuraci-gril'],
            ['name' => 'Kuracie stripsy',         'weight' => '400 g', 'price' => 7.00, 'desc' => '', 'allergens' => [1, 9, 12], 'photo' => 'kuracie-stripsy-box'],
            ['name' => 'Kuracie krídla',          'weight' => '400 g', 'price' => 7.00, 'desc' => '', 'allergens' => [1, 9, 12]],
            ['name' => 'Tominno kurací box',      'weight' => '1000 g', 'price' => 18.00, 'desc' => 'Mix kuracie stripsy, kuracie krídla, zemiakové hranolky, omáčka', 'allergens' => [1, 9, 12]],
        ],
    ],

    'naobjednavku' => [
        'label' => 'Na objednávku',
        'icon'  => 'heart',
        'photo' => 'party-misa-3',
        'note'  => 'Párty misy pripravujeme na objednávku - na oslavy, posedenia s priateľmi či firemné akcie. Zavolajte nám vopred.',
        'link'  => ['url' => '/oslavy-a-akcie', 'label' => 'Oslavy a firemné akcie u nás'],
        'items' => [
            ['name' => 'Mix Denko', 'weight' => 'na osobu', 'price' => 11.50, 'desc' => 'Kuracie stripsy, kuracie krídla, čerstvý kurací kebab, zemiakové hranolky, batátové hranolky, vyprážaný syr, cheddar uhlíky, cibuľové krúžky, čerstvé pizza pečivo, zelenina, omáčka podľa vlastného výberu', 'allergens' => [], 'photo' => 'party-misa-3'],
        ],
    ],

    'sladke' => [
        'label' => 'Sladké',
        'icon'  => 'cake',
        'photo' => 'sladke-pizzove-vankusiky',
        'items' => [
            ['name' => 'Pistachio cannoli',          'weight' => '1 ks',  'price' => 2.99, 'desc' => 'Na objednávku vopred', 'allergens' => [7]],
            ['name' => 'Sladké pizzové vankúšiky',   'weight' => '400 g', 'price' => 6.00, 'desc' => '', 'allergens' => [], 'photo' => 'sladke-pizzove-vankusiky'],
        ],
    ],

    'prilohy' => [
        'label' => 'Prílohy a omáčky',
        'icon'  => 'fries',
        'photo' => 'specialita-cheddar-bbq',
        'items' => [
            ['name' => 'Zemiakové hranolky',   'variants' => [['label' => 'Veľké 300 g', 'price' => 2.50], ['label' => 'Malé 120 g', 'price' => 1.50]], 'desc' => '', 'allergens' => []],
            ['name' => 'Batátové hranolky',    'variants' => [['label' => 'Veľké 300 g', 'price' => 3.50], ['label' => 'Malé 120 g', 'price' => 2.00]], 'desc' => '', 'allergens' => []],
            ['name' => 'Zemiakové smajlíky',   'weight' => '200 g', 'price' => 3.50, 'desc' => '', 'allergens' => []],
            ['name' => 'Tekutý cheddar',       'price' => 2.50, 'desc' => '', 'allergens' => []],
            ['name' => 'Cheddar uhlíky',       'weight' => '1 ks', 'price' => 0.80, 'desc' => '', 'allergens' => [7]],
            ['name' => 'Mozzarella vyprážané tyčinky', 'weight' => '1 ks', 'price' => 0.80, 'desc' => '', 'allergens' => [7]],
            ['name' => 'Cibuľové krúžky',      'price' => 3.50, 'desc' => '', 'allergens' => []],
            ['name' => 'Talianska príloha',    'price' => 3.50, 'desc' => '', 'allergens' => []],
            ['name' => 'Omáčka podľa výberu',  'weight' => '1 ks', 'price' => 1.50, 'desc' => 'Slaninová, BBQ, pikantná, medovo-horčicová, cesnaková, cheddarová, brusnicová, majonéza, jalapeño, kečup', 'allergens' => []],
        ],
    ],

];
