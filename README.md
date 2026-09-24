# Pizzeria Tominno – web

Web pizzerie v PHP (bez databázy). Slovenská verzia na `/`, maďarská na `/hu/`.
Obsah (menu, galéria, recenzie, otváracie hodiny…) upravuje klient v administrácii na `/admin`.

## Nasadenie na hosting

1. Nahrajte celý repozitár (vrátane priečinka `vendor/` – knižnica Dompdf na PDF menu).
   Originály fotiek (`assets/img/jedlo/` a pod.) sa nenahrávajú – sú v `.gitignore`.
2. Nastavte **zapisovateľné** priečinky (práva 755/775 podľa hostingu):
   - `storage/` – obsah z administrácie, zálohy, heslo, PDF cache, ochrana formulárov
   - `assets/img/foto/` a `assets/img/foto/thumb/` – fotky nahrané v administrácii
3. PHP 8.1+ s rozšíreniami `gd`, `mbstring`, `dom` (bežne dostupné). Odporúčané `exif`
   (otočenie fotiek z mobilu).
4. Presmerovanie na HTTPS v `.htaccess` funguje až s platným SSL certifikátom na doméne.
5. Po prvom prihlásení do `/admin` si klient zmení počiatočné heslo (Heslo v menu administrácie).

## Kde je čo

| Čo | Kde |
|---|---|
| Obsah upravený v administrácii | `storage/content/*.json` (zálohy v `storage/backup/`) |
| Východiskový obsah (kým sa nič neuloží) | `data/menu.php`, `data/gallery.php`, `data/reviews.php`, `defaultSettings()` v `includes/config.php` |
| Maďarský preklad textov webu | `data/lang-hu.php` – kontrola úplnosti: `php tools/check-translations.php` |
| Administrácia | `admin/` (vstup), `includes/admin/` (logika a stránky) |
| PDF menu | `menu-pdf.php` – generuje sa automaticky pri zmene menu (cache v `storage/cache/`) |
| Štýly | `assets/scss/` → `npx sass --style=compressed --no-source-map assets/scss/main.scss assets/css/style.css` |

## Obnova zo zálohy

Pri každom uložení v administrácii sa predchádzajúca verzia uloží do `storage/backup/`
(posledných 30 pre každý typ obsahu). Na obnovu stačí skopírovať zálohu späť do
`storage/content/<názov>.json`. Zmazaním súboru v `storage/content/` sa web vráti
k východiskovému obsahu z `data/`.

## Zabudnuté heslo do administrácie

Zmažte `storage/admin.json` – platí opäť počiatočné heslo (hash `ADMIN_PASSWORD_HASH`
v `includes/config.php`). Nový hash vytvoríte príkazom
`php -r "echo password_hash('noveHeslo', PASSWORD_DEFAULT);"`.
