<?php
/**
 * Východiskové sekcie galérie - použijú sa, kým administrácia neuloží
 * vlastnú galériu (storage/content/gallery.json).
 *
 * 'file'      = názov fotky v assets/img/foto (bez .jpg), náhľad je v foto/thumb
 * 'home'      = fotka sa zobrazí v ukážke galérie na úvodnej stránke
 * 'on_events' = fotky sekcie sa zobrazia na stránke Oslavy a akcie
 */

$photos = function (array $list, array $home = []): array {
    $out = [];
    foreach ($list as $file => $label) {
        $out[] = ['file' => $file, 'label' => $label, 'home' => in_array($file, $home, true)];
    }
    return $out;
};

$home = ['pizza-v-peci', 'burgre-pred-pecou', 'prevadzka-interier', 'party-misa-2', 'pizza-prosciutto-burrata', 'terasa'];

return [
    [
        'title' => 'Neapolská pizza',
        'photos' => $photos([
            'pizza-margherita-pec'     => 'Margherita z pece na drevo',
            'pizza-v-peci'             => 'Pizza v peci na drevo',
            'pizza-margherita'         => 'Margherita',
            'pizza-gorgonzola'         => 'Gorgonzola s hruškami a orechmi',
            'pizza-kukuricova'         => 'Kukuricová',
            'pizza-prosciutto-burrata' => 'Pizza s prosciuttom a burratou',
            'pizza-prosciutto-rukola'  => 'Pizza s prosciuttom a rukolou',
            'pizza-rukola-parmezan'    => 'Pizza s rukolou a parmezánom',
            'pizza-neapolska'          => 'Neapolská pizza',
        ], $home),
    ],
    [
        'title' => 'Burgery',
        'photos' => $photos([
            'burger-tekuty-cheddar'      => 'Burger s tekutým cheddarom',
            'burger-cierny-s-hranolkami' => 'Špeciál hovädzí burger',
            'burgre-cierny-a-cerveny'    => 'Burgery pred pecou',
            'burgre-pred-pecou'          => 'Burgery z našej kuchyne',
            'burger-cerveny'             => 'Burger v červenej žemli',
            'mini-burgre'                => 'Mini burgery',
            'burger-1'                   => 'Domáci hovädzí burger',
            'burger-2'                   => 'Burger s tekvicovými semienkami',
            'burger-3'                   => 'Domáci burger',
        ], $home),
    ],
    [
        'title' => 'Kebab, grill a šaláty',
        'photos' => $photos([
            'kebab-tanier'                  => 'Kebab tanier',
            'kebab-v-pizza-chlebe'          => 'Kebab v pizza chlebe',
            'tortilla-a-stripsy'            => 'Tortilla a kuracie stripsy',
            'kuracie-stripsy-box'           => 'Kuracie stripsy s hranolkami',
            'kuraci-gril'                   => 'Kurací gril',
            'bufala-salat'                  => 'Bufala šalát',
            'kuraci-salat-granatove-jablko' => 'Kurací šalát s granátovým jablkom',
            'salaty-v-miske'                => 'Šaláty',
            'bruschetta'                    => 'Bruschetta',
            'specialita-cheddar-bbq'        => 'S cheddarom a BBQ omáčkou',
            'sladke-pizzove-vankusiky'      => 'Sladké pizzové vankúšiky',
        ], $home),
    ],
    [
        'title' => 'Párty misy na objednávku',
        'on_events' => true,
        'photos' => $photos([
            'party-misa-1'           => 'Párty misa',
            'party-misa-2'           => 'Párty misa',
            'party-misa-3'           => 'Mix Denko',
            'party-misa-4'           => 'Párty misa',
            'kuracie-stripsy-etazer' => 'Kuracie stripsy na etažére',
        ], $home),
    ],
    [
        'title' => 'Naše suroviny',
        'photos' => $photos([
            'talianska-muka'     => 'Talianska múka na neapolskú pizzu',
            'talianske-suroviny' => 'Talianske suroviny',
        ], $home),
    ],
    [
        'title' => 'Prevádzka',
        'photos' => $photos([
            'prevadzka-interier' => 'Interiér pizzerie',
            'terasa'             => 'Terasa',
            'prevadzka-1'        => 'Interiér',
            'prevadzka-5'        => 'Interiér',
            'prevadzka-2'        => 'Vstup',
            'prevadzka-3'        => 'Sedenie',
            'prevadzka-4'        => 'Interiér',
            'prevadzka-vianoce'  => 'Vianoce v pizzerii',
            'kuchyna'            => 'Kuchyňa',
            'rozvoz-auto'        => 'Naše rozvozové auto',
        ], $home),
    ],
    [
        'title' => 'Akcie a oslavy',
        'on_events' => true,
        'photos' => $photos([
            'nas-tim'                 => 'Náš tím',
            'detsky-den-1'            => 'Detský deň',
            'detsky-den-2'            => 'Detský deň',
            'detsky-den-3'            => 'Detský deň',
            'detsky-den-cukrova-vata' => 'Cukrová vata',
            'detsky-den-4'            => 'Detský deň',
            'detsky-den-5'            => 'Detský deň',
            'oslava-1'                => 'Oslava v pizzerii',
            'oslava-2'                => 'Oslava v pizzerii',
            'mikulas-v-pizzerii'      => 'Mikuláš v pizzerii',
        ], $home),
    ],
];
