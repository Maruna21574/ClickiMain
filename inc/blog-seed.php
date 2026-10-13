<?php
/**
 * Úvodné články blogu (nahrajú sa raz pri migrácii databázy, potom sa spravujú v /admin/blog.php).
 *
 * Formát textu: odseky oddelené prázdnym riadkom, "## " = nadpis, "- " = odrážka,
 * "> " = zvýraznený tip, "![popis](/cesta.jpg)" = obrázok, "[[projekt:slug]]" = karta projektu.
 * Fotky v /uploads/blog sú z Unsplash (bezplatná licencia).
 */

return array (
  0 => 
  array (
    'slug' => 'kolko-stoji-web-na-mieru',
    'topic' => 'web',
    'published_at' => '2026-09-28',
    'title_sk' => 'Koľko stojí web na mieru a od čoho závisí jeho cena',
    'title_en' => 'How much does a custom website cost and what drives the price',
    'summary_sk' => 'Prečo sa ponuky na web líšia aj o stovky eur a na čo si dať pozor, aby ste neplatili za zbytočnosti.',
    'summary_en' => 'Why website quotes can differ by hundreds of euros and what to watch for so you do not pay for things you do not need.',
    'body_sk' => 'Otázka „koľko stojí web“ je ako otázka „koľko stojí auto“. Dá sa kúpiť lacné aj luxusné a obe vás odvezú do práce. Rozdiel je v tom, čo od neho očakávate a ako dlho vám má slúžiť.

> Základné pravidlo: nízka cena bez rozpisu je skoro vždy len odložená doplatka. Pýtajte sa, čo presne obsahuje.

## Čo cenu webu ovplyvňuje najviac

Cena nie je dielo náhody. Skladá sa z niekoľkých vecí, ktoré si môžete pred oslovením dodávateľa premyslieť:

- **Rozsah:** jedna stránka (landing page) je úplne iná práca ako firemný web s desiatkou podstránok alebo e-shop.
- **Dizajn:** šablóna s upravenými farbami je lacnejšia, ale vyzerá ako stovky iných webov. Dizajn na mieru stojí viac, no vás odlíši.
- **Funkcie:** rezervácie, platobná brána, klientska zóna alebo prepojenie na účtovný systém pridávajú vývojárske hodiny.
- **Obsah:** texty, fotografie a grafika. Ak ich máte pripravené, ušetríte. Ak nie, pripravia sa za príplatok.
- **Rýchlosť a SEO:** web, ktorý sa načíta za sekundu a je správne nastavený pre Google, stojí viac práce než „len pekná stránka“.

## Prečo sú ponuky také rozdielne

Dve ponuky na „firemný web“ môžu vyzerať rovnako, no skrývať dve úplne odlišné veci. Jedna zahŕňa dizajn, programovanie, SEO základ aj zaškolenie. Druhá len nasadenie hotovej šablóny. Preto si vždy pýtajte rozpis toho, čo cena obsahuje.

[[projekt:hotel-hradok]]

## Na čo si dať pozor

- **Nízka cena bez rozpisu.** Často znamená, že sa niečo doplatí neskôr.
- **Chýbajúca správa obsahu.** Ak si nedokážete zmeniť text sami, platíte za každú drobnosť.
- **Žiadna dokumentácia a zaškolenie.** Web má byť váš, nie závislý na jednom človeku.
- **Zabudnutá údržba.** Weby treba aktualizovať a zálohovať. Počítajte s tým od začiatku.

## Ako si ušetriť peniaze rozumne

Nezačínajte tým, že budete škrtať kvalitu. Začnite tým, že si ujasníte cieľ: či má web predávať, získavať dopyty alebo len informovať. Čím presnejšie zadanie, tým presnejšia a často aj nižšia cena. Pomôže aj rozdeliť projekt na etapy, kde najprv spustíte to najdôležitejšie a ostatné pridáte neskôr.

Ak chcete orientačný odhad pre váš prípad, v našom konfigurátore ponuky si ho naklikáte za minútu a nezáväzne.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/kolko-stoji-web-na-mieru.jpg',
  ),
  1 => 
  array (
    'slug' => 'co-je-seo-a-preco-ho-potrebujete',
    'topic' => 'seo',
    'published_at' => '2026-09-21',
    'title_sk' => 'Čo je SEO a prečo ho váš web potrebuje',
    'title_en' => 'What SEO is and why your website needs it',
    'summary_sk' => 'Jednoduché vysvetlenie, ako funguje vyhľadávanie v Google a čo môžete spraviť, aby si vás ľudia našli.',
    'summary_en' => 'A plain explanation of how Google search works and what you can do so people find you.',
    'body_sk' => 'Predstavte si, že máte krásny obchod, ale v uličke bez označenia, kde nikto nechodí. Presne takto vyzerá web bez SEO. Existuje, ale nikto ho nenájde.

SEO (optimalizácia pre vyhľadávače) je súbor úprav, vďaka ktorým vás Google lepšie pochopí a ukáže ľuďom, ktorí hľadajú práve to, čo ponúkate.

## Z čoho sa SEO skladá

- **Technická stránka:** rýchle načítanie, fungovanie na mobile, bezpečné spojenie (HTTPS) a čistá štruktúra webu.
- **Obsah:** texty, ktoré odpovedajú na otázky ľudí. Nie reklamné frázy, ale konkrétne informácie.
- **Dôveryhodnosť:** odkazy z iných kvalitných stránok, recenzie a profil firmy v Google.
- **Lokálne SEO:** ak pôsobíte v konkrétnom meste, treba sa ukázať v mapách a výsledkoch „blízko mňa“.

[[projekt:regen-zilina-masaze]]

## Prečo je to dôležité

Väčšina ľudí klikne na jeden z prvých výsledkov. Ak ste na druhej strane, pre zákazníka v podstate neexistujete. Na rozdiel od reklamy sa SEO neplatí za každé kliknutie, a keď sa raz podarí dostať vyššie, výsledky vydržia dlho.

## Čo môžete spraviť hneď

- Skontrolujte, či sa váš web zobrazuje na mobile bez problémov.
- Každá podstránka by mala mať vlastný jedinečný nadpis a krátky popis.
- Pridajte svoju firmu do Google Business profilu a vyplňte ho celý.
- Píšte o tom, čo vaši zákazníci skutočne riešia, napríklad blogom.

> Jedna vec platí vždy: najlepšie SEO je web, ktorý je rýchly, prehľadný a odpovedá na to, čo ľudia skutočne hľadajú.

## Čo SEO nie je

SEO nie je kúzlo a nedá sa urobiť za týždeň. Výsledky prichádzajú v týždňoch až mesiacoch. Pozor aj na tých, ktorí sľubujú „1. miesto v Google zaručene“. Nikto okrem Googlu to zaručiť nemôže.

Ak si nie ste istí, ako na tom váš web je, radi sa naň pozrieme a poradíme, čo má zmysel opraviť ako prvé.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/co-je-seo-a-preco-ho-potrebujete.jpg',
  ),
  2 => 
  array (
    'slug' => 'sedem-chyb-kvoli-ktorym-web-nepredava',
    'topic' => 'web',
    'published_at' => '2026-09-14',
    'title_sk' => '7 chýb, kvôli ktorým váš web nepredáva',
    'title_en' => '7 mistakes that stop your website from selling',
    'summary_sk' => 'Web môže vyzerať pekne a stále nezískavať dopyty. Toto sú najčastejšie dôvody, prečo sa to stáva.',
    'summary_en' => 'A website can look great and still bring no enquiries. These are the most common reasons why.',
    'body_sk' => 'Pekný web ešte nie je dobrý web. Ak vám nepriveduje zákazníkov, niekde sa stráca pozornosť. Tu sú chyby, ktoré vidíme najčastejšie.

> Rýchly test: ukážte svoj web niekomu cudziemu na päť sekúnd. Ak nevie povedať, čo ponúkate, máte prácu.

## 1. Nie je jasné, čo robíte

Návštevník má na rozhodnutie pár sekúnd. Ak z prvej obrazovky nepochopí, čo ponúkate a pre koho, odíde. Hlavný nadpis má odpovedať na otázku: „Čo z toho mám ja?“

## 2. Chýba jasná výzva k akcii

Po prečítaní stránky by mal zákazník vedieť, čo urobiť ďalej. Zavolať, napísať, objednať. Ak tlačidlo chýba alebo sa stráca, nebude klikať.

## 3. Web je pomalý

Ak sa stránka načíta dlhšie ako tri sekundy, veľká časť ľudí odíde ešte pred jej zobrazením. Najčastejšie vinníky sú obrovské obrázky a zbytočné doplnky.

## 4. Nefunguje dobre na mobile

Väčšina návštev dnes prichádza z telefónu. Malé písmo, preplnené menu alebo tlačidlá, ktoré sa nedajú trafiť, znamenajú stratené dopyty.

[[projekt:orvex-mt-eshop]]

## 5. Žiadna dôvera

Bez referencií, recenzií, fotiek reálnych ľudí alebo ukážok práce nemá návštevník dôvod veriť, že ste dobrí. Dôvera je často dôležitejšia ako cena.

## 6. Dlhý a zložitý kontaktný formulár

Každé zbytočné pole znižuje počet odoslaných správ. Spýtajte sa len na to, čo naozaj potrebujete vedieť na prvý kontakt.

## 7. Web sa nikdy neaktualizuje

Starý obsah, neplatné ceny a pokazené odkazy pôsobia nedôveryhodne. Web nie je brožúra, je to živá vizitka firmy.

## Kde začať

Otvorte si vlastný web na telefóne a skúste splniť úlohu svojho zákazníka: nájsť cenu alebo vás kontaktovať. Ak narazíte na prekážku, našli ste prvé miesto, kde sa dá zlepšiť.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/sedem-chyb-kvoli-ktorym-web-nepredava.jpg',
  ),
  3 => 
  array (
    'slug' => 'ako-zrychlit-web',
    'topic' => 'seo',
    'published_at' => '2026-09-07',
    'title_sk' => 'Ako zrýchliť web: praktický sprievodca bez zbytočného žargónu',
    'title_en' => 'How to speed up your website: a practical guide',
    'summary_sk' => 'Rýchly web predáva viac a Google ho má radšej. Tu je, čo vplýva na rýchlosť a kde začať.',
    'summary_en' => 'A fast website sells more and Google prefers it. Here is what affects speed and where to start.',
    'body_sk' => 'Rýchlosť webu nie je technická maličkosť. Ovplyvňuje, či zostanú návštevníci, či vás Google ukáže vyššie a či vám zákazník nakoniec napíše.

## Ako zistiť, ako ste na tom

Zadajte adresu svojho webu do bezplatného nástroja Google PageSpeed Insights. Dostanete skóre pre mobil aj počítač a zoznam konkrétnych odporúčaní. Zameriavajte sa na mobil, tam bývajú výsledky horšie.

> Zlaté pravidlo: ak sa web na mobile ukáže do troch sekúnd, väčšina ľudí zostane. Čo je navyše, je bonus.

## Najčastejšie príčiny pomalého webu

- **Veľké obrázky:** fotka z mobilu môže mať 5 MB, no na webe stačí zlomok.
- **Zbytočné pluginy a skripty:** každé doplnkové rozšírenie niečo načítava.
- **Slabý hosting:** ak je server pomalý, žiadna optimalizácia ho nezachráni.
- **Chýbajúca cache:** web sa pri každej návšteve skladá odznova, hoci by mohol byť pripravený.
- **Ťažké písma a videá:** nahrajte len to, čo naozaj používate.

## Čo opraviť ako prvé

1. **Obrázky.** Zmenšite ich na reálnu veľkosť a použite moderné formáty (WebP). Býva to najväčší skok v rýchlosti.
2. **Lenivé načítanie.** Obrázky pod záhybom stránky sa môžu načítať až pri skrolovaní.
3. **Odstráňte nepoužívané doplnky** a skripty tretích strán, ktoré nepotrebujete.
4. **Zapnite cache** a kompresiu na serveri.
5. **Skontrolujte hosting.** Lacný zdieľaný hosting môže byť brzdou.

[[projekt:cool-comfort-klimatizacie]]

## Koľko je dosť

Cieľ nie je dokonalé skóre 100, ale web, ktorý sa na mobile javí hotový za dve až tri sekundy. Rozdiel medzi päť a dve sekundy zákazníci cítia, rozdiel medzi 90 a 100 bodmi už takmer vôbec.

Ak si nechcete zaťažovať hlavu technikou, zrýchlenie webu vieme urobiť za vás vrátane merania pred a po úprave.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/ako-zrychlit-web.jpg',
  ),
  4 => 
  array (
    'slug' => 'preco-je-sprava-webu-lacnejsia-ako-oprava',
    'topic' => 'sprava-webu',
    'published_at' => '2026-08-31',
    'title_sk' => 'Prečo je pravidelná správa webu lacnejšia než oprava po havárii',
    'title_en' => 'Why regular website maintenance costs less than fixing a crash',
    'summary_sk' => 'Web nie je hotový, keď sa spustí. Bez údržby je len otázkou času, kedy sa pokazí v najhoršiu chvíľu.',
    'summary_en' => 'A website is not finished once it launches. Without maintenance it is only a matter of time before it breaks at the worst moment.',
    'body_sk' => 'Mnoho firiem si web objedná, zaplatí a zabudne naň. Kým funguje, všetko je v poriadku. Problém prichádza, keď prestane.

## Čo sa s webom deje na pozadí

Technológie sa menia. Prehliadače, verzie PHP, bezpečnostné záplaty, pluginy aj systém, na ktorom web beží, dostávajú aktualizácie. Ak sa neaplikujú, vznikajú medzery, cez ktoré sa dá do webu dostať.

> Web je ako auto: pravidelný servis stojí zlomok toho, čo oprava po poruche uprostred cesty.

## Najčastejšie havárie

- **Napadnutý web:** nasadený spam, presmerovanie na cudzie stránky alebo zablokovanie Googlom.
- **Neaktuálny plugin:** po aktualizácii servera web náhle prestane fungovať.
- **Vypršaný certifikát:** prehliadač začne strašiť návštevníkov varovaním „nebezpečné spojenie“.
- **Strata dát:** bez zálohy sa pokazený web nedá vrátiť späť.

## Čo obnáša rozumná správa

- Pravidelné aktualizácie systému a doplnkov.
- Automatické zálohy a overenie, že sa dajú obnoviť.
- Monitoring dostupnosti: dozviete sa o výpadku skôr než zákazník.
- Bezpečnostná kontrola a ochrana pred spamom.
- Drobné úpravy obsahu, ceny či fotky bez nutnosti hľadať vývojára.

[[projekt:hotel-hradok]]

## Prečo sa to oplatí

Oprava po havárii je vždy drahšia, pretože sa deje pod tlakom. Platíte za urgentný zásah, strácate zákazníkov a niekedy aj dobré meno. Mesačný paušál je predvídateľný náklad a pokoj v hlave.

Ak chcete mať o web postarané, ponúkame správu webu s pevným mesačným poplatkom a jedným kontaktom na všetko.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/preco-je-sprava-webu-lacnejsia-ako-oprava.jpg',
  ),
  5 => 
  array (
    'slug' => 'socialne-siete-pre-malu-firmu',
    'topic' => 'socialne-siete',
    'published_at' => '2026-08-24',
    'title_sk' => 'Sociálne siete pre malú firmu: ako začať, aby to nebola strata času',
    'title_en' => 'Social media for a small business: how to start without wasting time',
    'summary_sk' => 'Nemusíte byť všade. Stačí si vybrať správnu sieť, mať plán a držať sa ho.',
    'summary_en' => 'You do not need to be everywhere. Pick the right platform, have a plan and stick to it.',
    'body_sk' => 'Pri slove „sociálne siete“ si mnoho majiteľov firiem predstaví nekonečné publikovanie a tlak, že musia byť všade. V skutočnosti stačí menej, ale cielene.

## Vyberte si jednu až dve siete

Každá sieť má iné publikum. Skôr než začnete, premyslite, kde sa vaši zákazníci zdržiavajú:

- **Instagram:** vizuálne témy ako jedlo, móda, krása, interiér, fotografia a služby s výsledkom, ktorý sa dá ukázať.
- **Facebook:** lokálne firmy, komunity, udalosti a staršia cieľová skupina.
- **LinkedIn:** B2B služby, nábor a odborná komunikácia.

![Silný obraz zastaví palec pri skrolovaní. Kvalitný obsah je polovica úspechu.](/uploads/blog/social-fig.jpg)

## Ujasnite si, prečo tam ste

Chcete získavať dopyty, budovať značku alebo komunikovať s existujúcimi zákazníkmi? Od cieľa závisí obsah aj to, ako budete výsledok merať.

## Jednoduchý obsahový plán

Vyhnite sa len reklamným príspevkom. Osvedčený pomer je takýto:

1. **Užitočný obsah:** tipy, návody, odpovede na časté otázky.
2. **Zákulisie:** ako pracujete, kto ste, čo sa deje u vás.
3. **Dôkaz:** hotové projekty, recenzie, výsledky klientov.
4. **Ponuka:** jasná výzva, čo má zákazník urobiť.

## Pravidelnosť poráža intenzitu

Lepšie je pridať dva kvalitné príspevky týždenne celé mesiace než desať za týždeň a potom sa odmlčať. Vopred si pripravte plán na mesiac a obsah môžete dávkovať.

> Číslo, ktoré sa neodrazí v dopytoch alebo predaji, je len ozdoba. Merajte to, čo pre vás reálne znamená rast.

## Čo merať

Nesledujte len počet lajkov. Dôležitejšie sú dosah, uložené príspevky, správy od ľudí a to, koľko dopytov reálne prišlo. Číslo, ktoré sa neodrazí v dopytoch, je len ozdoba.

Ak sociálne siete nechcete riešiť sami, môžeme sa o ne starať: od plánu cez grafiku a fotky až po publikovanie.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/socialne-siete-pre-malu-firmu.jpg',
  ),
  6 => 
  array (
    'slug' => 'co-ma-obsahovat-dobra-vizualna-identita',
    'topic' => 'grafika',
    'published_at' => '2026-08-17',
    'title_sk' => 'Čo má obsahovať dobrá vizuálna identita a prečo nestačí len logo',
    'title_en' => 'What a good visual identity includes and why a logo is not enough',
    'summary_sk' => 'Logo je len začiatok. Značka pôsobí dôveryhodne, keď vyzerá rovnako všade, kde ju zákazník stretne.',
    'summary_en' => 'A logo is only the beginning. A brand feels trustworthy when it looks the same everywhere a customer meets it.',
    'body_sk' => 'Logo je to, čo si ľudia zapamätajú ako prvé, ale samo o sebe z vás značku neurobí. Značka vzniká z celku: ako vyzeráte na webe, vizitke, faktúre aj v príspevku na Instagrame.

## Z čoho sa vizuálna identita skladá

- **Logo a jeho varianty:** farebné, čiernobiele, horizontálne, štvorcové pre profilovku.
- **Farebná paleta:** hlavné a doplnkové farby s presnými kódmi.
- **Typografia:** pár vybraných písiem pre nadpisy a texty.
- **Grafické prvky:** ikony, vzory, štýl fotografií a ilustrácií.
- **Pravidlá použitia:** ako logo používať, čo sa nesmie, aké sú ochranné odstupy.

[[projekt:stavus-interierove-prace]]

## Prečo na konzistentnosti záleží

Keď vás zákazník stretne na webe, na letáku a na sociálnych sieťach a vždy vyzeráte rovnako, vytvorí sa pocit profesionality. Chaos vo vizuáli naopak pôsobí, akoby ste nemali veci pod kontrolou.

## Najčastejšie chyby

- Logo, ktoré sa dá použiť len na bielom pozadí.
- Príliš veľa farieb a písiem bez systému.
- Dizajn podľa módy, ktorý o pár rokov zostarne.
- Žiadne pravidlá, takže si každý vytvára vlastnú verziu.

> Dobrú značku spoznáte podľa toho, že ju spoznáte aj bez loga: farby, tón aj štýl fotografií hovoria za ňu.

## Ako to robiť rozumne

Začnite otázkou, aká má značka byť a pre koho. Až potom sa riešia farby a tvary. Dobrá identita je jednoduchá, čitateľná v malej veľkosti a funguje aj bez farieb.

Radi pripravíme logo aj kompletný manuál značky tak, aby ste ho vedeli používať sami.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/co-ma-obsahovat-dobra-vizualna-identita.jpg',
  ),
  7 => 
  array (
    'slug' => 'ako-sa-pripravit-na-firemne-fotenie',
    'topic' => 'foto',
    'published_at' => '2026-08-10',
    'title_sk' => 'Ako sa pripraviť na firemné fotenie, aby sa z neho dalo naozaj čerpať',
    'title_en' => 'How to prepare for a business photoshoot that you can actually use',
    'summary_sk' => 'Dobré fotky nie sú len otázkou fotografa. Pomôže vám príprava, zoznam záberov a pár jednoduchých pravidiel.',
    'summary_en' => 'Good photos are not just down to the photographer. Preparation, a shot list and a few simple rules go a long way.',
    'body_sk' => 'Fotky z firemného fotenia využijete na webe, v ponuke, na sociálnych sieťach aj v tlačenej podobe. Preto sa oplatí na ne dobre pripraviť, aby ste si z jedného dňa odniesli materiál na celý rok.

## Pred fotením

- **Ujasnite si účel:** web, sociálne siete, nábor alebo katalóg? Podľa toho sa líšia zábery.
- **Spravte zoznam záberov:** ľudia, priestor, proces práce, produkty, detaily. Nemusí byť dokonalý, stačí osnova.
- **Dohodnite si termín:** vyberte čas, keď je v prevádzke dobré svetlo a nie je veľký ruch.
- **Upracte priestor:** zbytočné veci, káble a papiere na stole sa na fotkách rozhodne ukážu.

![Pokojná atmosféra a pár slov pred fotením dokážu zázraky. Prirodzené zábery vznikajú, keď sa ľudia cítia dobre.](/uploads/blog/photo-fig.jpg)

## Čo oblečenie a ľudia

Zvoľte jednoduché oblečenie bez výrazných vzorov a veľkých log. Farby majú ladiť s vašou značkou. Pre ľudí, ktorí nie sú zvyknutí pred objektívom, pomáha povedať im vopred, čo sa bude diať. Menšia tréma znamená prirodzenejšie fotky.

## Počas fotenia

Nebojte sa vyskúšať aj zábery v pohybe, napríklad pri práci s klientom. Pôsobia živšie než zostavené pózy. Pýtajte sa fotografa na to, čo ho napadne, bežne má dobrý odhad, ako veci ukázať.

[[projekt:portretna-fotografia]]

## Po fotení

Dohodnite sa na formátoch a veľkostiach, ktoré budete potrebovať. Dobré je mať verziu na web, na sociálne siete aj na tlač. Fotky si zoraďte do priečinkov a pomenujte ich, ušetríte si hľadanie o rok neskôr.

Ak plánujete fotenie pre firmu, produkty alebo tím, rád s vami prejdeme cieľ aj zoznam záberov.',
    'body_en' => '',
    'cover_image' => '/uploads/blog/ako-sa-pripravit-na-firemne-fotenie.jpg',
  ),
);
