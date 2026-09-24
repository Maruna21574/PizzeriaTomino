<?php /* Text stránky Ochrana osobných údajov - slovensky. Vkladá ochrana-osobnych-udajov.php. */ ?>
<h2>1. Prevádzkovateľ</h2>
<p>Prevádzkovateľom osobných údajov je <?= companyField(COMPANY_NAME, 'obchodné meno prevádzkovateľa') ?>, so sídlom <?= companyField(COMPANY_ADDRESS, 'adresa sídla') ?>, IČO: <?= companyField(COMPANY_ICO, 'IČO') ?>, prevádzka <?= e(SITE_NAME) ?>, <?= e(SITE_ADDRESS_FULL) ?> (ďalej len „my“).</p>
<p>Kontakt vo veciach ochrany osobných údajov: <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>, tel. <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a>.</p>

<h2>2. Aké údaje spracúvame a prečo</h2>
<h3>Kontaktný formulár</h3>
<p>Ak nám napíšete cez <a href="<?= e(url('/kontakt')) ?>">kontaktný formulár</a>, spracúvame vaše meno, e-mail, prípadne telefón a text správy. Údaje použijeme iba na to, aby sme vám mohli odpovedať. Právnym základom je náš oprávnený záujem odpovedať na vašu otázku (čl. 6 ods. 1 písm. f) GDPR), prípadne opatrenia pred uzavretím zmluvy (čl. 6 ods. 1 písm. b) GDPR).</p>

<h3>Dopyt na oslavu alebo firemnú akciu</h3>
<p>Ak nám pošlete dopyt cez formulár na stránke <a href="<?= e(url('/oslavy-a-akcie')) ?>">Oslavy a akcie</a>, spracúvame vaše meno, telefón, prípadne e-mail, typ akcie, predbežný termín, počet osôb a poznámku. Údaje použijeme na to, aby sme vás kontaktovali a akciu s vami dohodli. Právnym základom sú opatrenia pred uzavretím zmluvy na vašu žiadosť (čl. 6 ods. 1 písm. b) GDPR).</p>

<h3>Ochrana formulárov proti spamu</h3>
<p>Aby sme formuláre chránili pred automatickým spamom, pri odoslaní formulára si uložíme nevratne zahašovaný odtlačok vašej IP adresy a čas odoslania (najviac na 24 hodín). Z odtlačku sa vaša IP adresa nedá spätne zistiť. Právnym základom je náš oprávnený záujem na bezpečnosti webu (čl. 6 ods. 1 písm. f) GDPR).</p>

<h3>Telefonické objednávky s rozvozom</h3>
<p>Pri objednávke telefonicky potrebujeme vaše meno, telefónne číslo a adresu doručenia. Tieto údaje používame iba na prípravu a doručenie objednávky. Právnym základom je plnenie zmluvy (čl. 6 ods. 1 písm. b) GDPR).</p>

<h2>3. Ako dlho údaje uchovávame</h2>
<ul class="check-list">
  <li>Správy z kontaktného formulára a dopyty na akcie - najviac 1 rok od vybavenia.</li>
  <li>Údaje k objednávke - len po dobu nevyhnutnú na jej doručenie a vybavenie prípadnej reklamácie; údaje, ktoré sa objavia v účtovných dokladoch, uchovávame po dobu stanovenú zákonom o účtovníctve.</li>
</ul>

<h2>4. Komu údaje poskytujeme</h2>
<p>Vaše údaje nepredávame ani neposkytujeme tretím stranám na marketing. Prístup k nim môžu mať iba naši zamestnanci a kuriér (pri rozvoze) a poskytovateľ webhostingu a e-mailu, ktorý pre nás zabezpečuje technickú prevádzku webu. Údaje neprenášame mimo Európskej únie.</p>

<h2>5. Cookies a služby tretích strán</h2>
<p>Náš web sám nepoužíva sledovacie ani reklamné cookies. Na stránkach používame tieto služby tretích strán:</p>
<ul class="check-list">
  <li><strong>Google Fonts</strong> - písma webu sa načítavajú zo serverov Google, ktoré pritom môžu spracovať vašu IP adresu.</li>
  <li><strong>Mapy Google</strong> - mapa na stránke Kontakt je vložená zo služby Google Maps, ktorá môže ukladať vlastné cookies. Podrobnosti nájdete v <a href="https://policies.google.com/privacy?hl=sk" target="_blank" rel="noopener">zásadách ochrany súkromia Google</a>.</li>
</ul>

<h2>6. Vaše práva</h2>
<p>Máte právo požadovať prístup k svojim osobným údajom, ich opravu alebo vymazanie, obmedzenie spracúvania, prenosnosť údajov a namietať proti spracúvaniu založenému na oprávnenom záujme. Stačí nám napísať na <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.</p>
<p>Ak si myslíte, že s vašimi údajmi zaobchádzame v rozpore so zákonom, môžete podať sťažnosť dozornému orgánu - <a href="https://dataprotection.gov.sk" target="_blank" rel="noopener">Úradu na ochranu osobných údajov Slovenskej republiky</a>, Hraničná 12, 820 07 Bratislava.</p>

<p class="note">Tieto informácie sú platné od 24. 9. 2026.</p>
