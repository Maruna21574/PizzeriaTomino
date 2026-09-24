<?php /* Text stránky Ochrana osobných údajov - maďarsky (Adatvédelmi tájékoztató). Vkladá ochrana-osobnych-udajov.php. */ ?>
<h2>1. Adatkezelő</h2>
<p>A személyes adatok kezelője: <?= companyField(COMPANY_NAME, 'az adatkezelő cégneve') ?>, székhely: <?= companyField(COMPANY_ADDRESS, 'székhely címe') ?>, cégazonosító szám (IČO): <?= companyField(COMPANY_ICO, 'IČO') ?>, üzlet: <?= e(SITE_NAME) ?>, <?= e(SITE_ADDRESS_FULL) ?> (a továbbiakban „mi”).</p>
<p>Elérhetőség adatvédelmi ügyekben: <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>, tel. <a href="tel:<?= e(SITE_PHONE_TEL) ?>"><?= e(SITE_PHONE) ?></a>.</p>

<h2>2. Milyen adatokat kezelünk és miért</h2>
<h3>Kapcsolatfelvételi űrlap</h3>
<p>Ha a <a href="<?= e(url('/kontakt')) ?>">kapcsolatfelvételi űrlapon</a> keresztül ír nekünk, kezeljük a nevét, e-mail-címét, esetleg telefonszámát és az üzenet szövegét. Az adatokat kizárólag arra használjuk, hogy válaszolni tudjunk Önnek. A jogalap a kérdésének megválaszolásához fűződő jogos érdekünk (GDPR 6. cikk (1) bekezdés f) pont), illetve a szerződés megkötését megelőző lépések (GDPR 6. cikk (1) bekezdés b) pont).</p>

<h3>Ajánlatkérés ünnepségre vagy céges rendezvényre</h3>
<p>Ha az <a href="<?= e(url('/oslavy-a-akcie')) ?>">Ünnepségek és rendezvények</a> oldalon található űrlapon ajánlatot kér, kezeljük a nevét, telefonszámát, esetleg e-mail-címét, a rendezvény típusát, a tervezett időpontot, a vendégek számát és a megjegyzést. Az adatokat arra használjuk, hogy felvegyük Önnel a kapcsolatot és egyeztessük a rendezvényt. A jogalap az Ön kérésére a szerződés megkötését megelőzően tett lépések (GDPR 6. cikk (1) bekezdés b) pont).</p>

<h3>Az űrlapok védelme a spam ellen</h3>
<p>Az űrlapok automatikus spam elleni védelme érdekében beküldéskor elmentjük az IP-címe visszafordíthatatlanul hash-elt lenyomatát és a beküldés idejét (legfeljebb 24 órára). A lenyomatból az IP-címe nem állítható vissza. A jogalap a weboldal biztonságához fűződő jogos érdekünk (GDPR 6. cikk (1) bekezdés f) pont).</p>

<h3>Telefonos rendelés házhozszállítással</h3>
<p>Telefonos rendelésnél szükségünk van a nevére, telefonszámára és a szállítási címre. Ezeket az adatokat kizárólag a rendelés elkészítéséhez és kiszállításához használjuk. A jogalap a szerződés teljesítése (GDPR 6. cikk (1) bekezdés b) pont).</p>

<h2>3. Meddig őrizzük meg az adatokat</h2>
<ul class="check-list">
  <li>A kapcsolatfelvételi űrlapon érkezett üzeneteket és a rendezvényekre vonatkozó ajánlatkéréseket - legfeljebb 1 évig az ügy lezárásától számítva.</li>
  <li>A rendeléssel kapcsolatos adatokat - csak a kiszállításhoz és az esetleges reklamáció intézéséhez szükséges ideig; a számviteli bizonylatokon szereplő adatokat a számviteli törvényben előírt ideig őrizzük.</li>
</ul>

<h2>4. Kinek adjuk át az adatokat</h2>
<p>Adatait nem adjuk el, és marketing céljából nem adjuk át harmadik félnek. Hozzáférhetnek alkalmazottaink és a futár (kiszállításkor), valamint a tárhely- és e-mail-szolgáltató, amely a weboldal technikai működését biztosítja. Adatait nem továbbítjuk az Európai Unión kívülre.</p>

<h2>5. Sütik és harmadik fél szolgáltatásai</h2>
<p>Weboldalunk maga nem használ követő vagy reklámcélú sütiket. Az oldalakon a következő harmadik féltől származó szolgáltatásokat használjuk:</p>
<ul class="check-list">
  <li><strong>Google Fonts</strong> - a weboldal betűtípusai a Google szervereiről töltődnek be, amelyek ennek során kezelhetik az IP-címét.</li>
  <li><strong>Google Térkép</strong> - a Kapcsolat oldalon lévő térkép a Google Maps szolgáltatásból van beágyazva, amely saját sütiket tárolhat. Részletek a <a href="https://policies.google.com/privacy?hl=hu" target="_blank" rel="noopener">Google adatvédelmi irányelveiben</a>.</li>
</ul>

<h2>6. Az Ön jogai</h2>
<p>Joga van hozzáférést kérni személyes adataihoz, kérni azok helyesbítését vagy törlését, az adatkezelés korlátozását, az adathordozhatóságot, valamint tiltakozni a jogos érdeken alapuló adatkezelés ellen. Elég, ha ír nekünk a <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a> címre.</p>
<p>Ha úgy gondolja, hogy adatait jogszabályellenesen kezeljük, panaszt tehet a felügyeleti hatóságnál - a <a href="https://dataprotection.gov.sk" target="_blank" rel="noopener">Szlovák Köztársaság Személyes Adatok Védelmének Hivatalánál</a> (Úrad na ochranu osobných údajov SR), Hraničná 12, 820 07 Bratislava.</p>

<p class="note">Jelen tájékoztató 2026. 9. 24-től érvényes.</p>
