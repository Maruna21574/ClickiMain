<?php
/**
 * SEO vstupné stránky (landing pages) — jedna stránka = jedna téma / kľúčové slovo.
 * Adresy: /{slug} (rewrite v .htaccess na landing.php?slug={slug}).
 *
 * Polia: title (<title>), desc (meta description), h1, eyebrow, lead, cta, projects ('web' | 'foto' | slug projektu pre galériu),
 *        sections [h, p[], ul[]], steps [title, text], faq [q, a], related [slug...]
 */

return [
    'tvorba-webstranok' => [
        'nav' => 'Tvorba webstránok',
        'title' => 'Tvorba webstránok — moderné weby, ktoré predávajú | Clicki',
        'desc' => 'Tvorba webstránok pre firmy, živnostníkov aj značky. Rýchle, responzívne weby so SEO základom a administráciou. Nezáväzná ponuka do 12 hodín.',
        'h1' => 'Tvorba webstránok, ktoré vám prinášajú dopyty',
        'eyebrow' => 'Tvorba webstránok',
        'lead' => 'Postavíme vám webstránku, ktorá vyzerá profesionálne, načíta sa za sekundu a je pripravená na to, aby ju Google našiel. Od návrhu cez programovanie až po spustenie a zaškolenie, všetko s jedným kontaktom.',
        'cta' => 'Chcem ponuku na web',
        'projects' => 'web',
        'sections' => [
            [
                'h' => 'Pre koho robíme webstránky',
                'p' => ['Tvorbu webstránok robíme pre firmy, živnostníkov, hotely a penzióny, služby, remeselníkov, fotografov aj menšie e-shopy. Spoločné majú jedno: potrebujú web, ktorý nie je len vizitkou, ale pomáha získavať zákazníkov.'],
            ],
            [
                'h' => 'Čo všetko je súčasťou tvorby webstránky',
                'ul' => [
                    'Návrh dizajnu na mieru podľa vašej značky, nie z hotovej šablóny.',
                    'Responzívne zobrazenie na mobile, tablete aj počítači.',
                    'Rýchle načítanie a optimalizované obrázky.',
                    'SEO základ: nadpisy, meta údaje, štruktúra, mapa stránok a štruktúrované dáta.',
                    'Administrácia, v ktorej si texty a fotky zmeníte sami.',
                    'Kontaktný formulár s ochranou proti spamu.',
                    'Zaškolenie, dokumentácia a podpora po spustení.',
                ],
            ],
            [
                'h' => 'Prečo si dať webstránku robiť u nás',
                'p' => ['Nerobíme weby na pásovej výrobe. Každý projekt začína rozhovorom o tom, čo od webu čakáte: či má získavať dopyty, predávať alebo budovať dôveru. Podľa toho navrhujeme štruktúru, texty aj dizajn. Výsledok je web, ktorý je prehľadný, rýchly a pre návštevníka zrozumiteľný.',
                       'Komunikujete s jedným tímom a jedným kontaktom, takže nemusíte koordinovať grafika, programátora a copywritera zvlášť.'],
            ],
        ],
        'steps' => [
            ['Konzultácia', 'Zistíme, čo potrebujete, pre koho web robíte a aké sú ciele. Dostanete jasný rozsah a termín.'],
            ['Návrh a obsah', 'Pripravíme štruktúru, dizajn a texty. Priebežne ich spolu dolaďujeme.'],
            ['Vývoj', 'Web naprogramujeme, optimalizujeme na rýchlosť a otestujeme na všetkých zariadeniach.'],
            ['Spustenie a podpora', 'Nasadíme web, zaškolíme vás a ostávame k dispozícii.'],
        ],
        'faq' => [
            ['Ako dlho trvá tvorba webstránky?', 'Jednoduchý prezentačný web býva hotový za 2 až 4 týždne, rozsiahlejší web alebo e-shop za 4 až 8 týždňov. Konkrétny termín dostanete s ponukou.'],
            ['Koľko stojí tvorba webstránky?', 'Cena závisí od rozsahu, počtu podstránok a funkcií. Cenovú ponuku na mieru dostanete nezáväzne a zadarmo, odhad si viete naklikať aj v našom konfigurátore.'],
            ['Budem si vedieť web spravovať sám?', 'Áno. Súčasťou webu je jednoduchá administrácia a zaškolenie. Ak nechcete riešiť nič, môžeme sa o web starať my.'],
            ['Bude môj web vidieť v Google?', 'Každý web staviame so SEO základom. Na lepšie umiestnenia v konkurenčných odvetviach odporúčame SEO optimalizáciu ako samostatnú službu.'],
        ],
        'related' => ['web-na-mieru', 'tvorba-eshopov', 'seo-optimalizacia'],
    ],

    'web-na-mieru' => [
        'nav' => 'Web na mieru',
        'title' => 'Web na mieru: webstránka bez šablón pre vašu značku | Clicki',
        'desc' => 'Web na mieru bez šablón: vlastný dizajn, čistý kód, rýchlosť a funkcie presne podľa vašich potrieb. Webstránka na mieru, ktorá vás odlíši od konkurencie.',
        'h1' => 'Web na mieru, ktorý vás odlíši od konkurencie',
        'eyebrow' => 'Web na mieru',
        'lead' => 'Webstránka na mieru znamená, že dizajn, štruktúra aj funkcie vznikajú pre vás, nie z hotovej šablóny, ktorú používajú stovky ďalších firiem. Výsledok je rýchlejší, jedinečný a presne taký, aký potrebujete.',
        'cta' => 'Chcem web na mieru',
        'projects' => 'web',
        'sections' => [
            [
                'h' => 'Čo znamená web na mieru',
                'p' => ['Pri webe na mieru nevychádzame z hotovej témy. Navrhneme dizajn od základu podľa vašej značky, rozloženie obsahu podľa toho, ako sa návštevníci rozhodujú, a funkcie podľa toho, čo reálne potrebujete. Nemáte tak zbytočné doplnky a web je rýchlejší.'],
            ],
            [
                'h' => 'Web na mieru vs. šablóna',
                'ul' => [
                    '**Dizajn:** jedinečný vs. vzhľad, ktorý má veľa iných webov.',
                    '**Rýchlosť:** čistý kód bez balastu vs. ťažké témy s desiatkami doplnkov.',
                    '**Funkcie:** presne to, čo potrebujete vs. obmedzenia šablóny a pluginov.',
                    '**Rast:** web sa dá rozširovať bez prerábania vs. nutnosť meniť celé riešenie.',
                    '**Bezpečnosť:** menej cudzieho kódu znamená menej zraniteľností.',
                ],
            ],
            [
                'h' => 'Kedy sa web na mieru oplatí',
                'p' => ['Web na mieru sa oplatí, ak sa chcete odlíšiť od konkurencie, potrebujete špecifickú funkciu (rezervácie, kalkulačky, klientska zóna, prepojenie na iný systém) alebo vám záleží na rýchlosti a dlhodobej udržateľnosti. Ak si nie ste istí, poradíme, čo je pre vás rozumnejšie.'],
            ],
            [
                'h' => 'Funkcie, ktoré vieme doplniť',
                'ul' => [
                    'Rezervačné systémy a online objednávky.',
                    'Konfigurátory a kalkulačky cien.',
                    'Klientska zóna a interné nástroje.',
                    'Prepojenie na účtovníctvo, platobné brány a CRM.',
                    'Viacjazyčný web a vlastná administrácia.',
                ],
            ],
        ],
        'steps' => [
            ['Zadanie', 'Spoločne ujasníme ciele, funkcie a rozsah. Dostanete rozpis toho, čo sa bude robiť.'],
            ['Dizajn', 'Navrhneme vzhľad podľa vašej značky a odsúhlasíme ho ešte pred programovaním.'],
            ['Programovanie', 'Web postavíme na čistom kóde, bez zbytočných pluginov.'],
            ['Testovanie a spustenie', 'Otestujeme rýchlosť a zobrazenie a web nasadíme.'],
        ],
        'faq' => [
            ['Čo je to web na mieru?', 'Je to webstránka navrhnutá a naprogramovaná špeciálne pre vás, bez použitia hotovej šablóny. Dizajn aj funkcie zodpovedajú vašej značke a potrebám.'],
            ['Je web na mieru drahší ako šablóna?', 'Na začiatku môže byť drahší, dlhodobo však býva výhodnejší: je rýchlejší, jedinečný a netreba preň drahé úpravy či prerábanie.'],
            ['Môžem si web na mieru sám upravovať?', 'Áno. Súčasťou je administrácia, v ktorej si zmeníte texty, fotky aj ceny bez programátora.'],
            ['Robíte webstránky na mieru aj pre malé firmy?', 'Áno. Rozsah vieme prispôsobiť aj menším firmám a živnostníkom, začať môžeme základom a funkcie pridávať postupne.'],
        ],
        'related' => ['tvorba-webstranok', 'tvorba-eshopov', 'seo-optimalizacia'],
    ],

    'tvorba-eshopov' => [
        'nav' => 'Tvorba e-shopov',
        'title' => 'Tvorba e-shopov na mieru — internetový obchod | Clicki',
        'desc' => 'Tvorba e-shopu na mieru: rýchly internetový obchod s platobnou bránou, dopravcami a faktúrami. E-shop na mieru s dôrazom na konverzie a SEO.',
        'h1' => 'Tvorba e-shopov, ktoré sú rýchle a predávajú',
        'eyebrow' => 'Tvorba e-shopov',
        'lead' => 'Postavíme vám e-shop na mieru s dôrazom na to, čo rozhoduje o predaji: rýchlosť, jednoduchý nákup, dôveryhodnosť a viditeľnosť v Google. Bez zbytočných poplatkov za predplatné a s plnou kontrolou nad obchodom.',
        'cta' => 'Chcem ponuku na e-shop',
        'projects' => 'web',
        'sections' => [
            [
                'h' => 'Čo má dobrý e-shop obsahovať',
                'ul' => [
                    'Prehľadnú navigáciu, vyhľadávanie a filtrovanie produktov.',
                    'Jednoduchý nákup v pár krokoch, aj bez registrácie.',
                    'Platobnú bránu (karta, Apple Pay, Google Pay) a prevod.',
                    'Prepojenie na dopravcov a výdajné miesta (Packeta, DPD, SPS).',
                    'Automatické faktúry a prepojenie na účtovný systém.',
                    'Varianty produktov, zľavové kupóny a akcie.',
                    'Správu objednávok, skladu a zákazníkov v jednej administrácii.',
                ],
            ],
            [
                'h' => 'Rýchlosť rozhoduje o predaji',
                'p' => ['Každá sekunda načítania navyše znamená menej objednávok. Preto e-shopy staviame na čistom, rýchlom kóde a optimalizujeme obrázky aj databázové dopyty. Rýchly e-shop dostáva viac objednávok a Google ho hodnotí lepšie.'],
            ],
            [
                'h' => 'E-shop na mieru alebo hotové riešenie',
                'p' => ['Hotové platformy sú rýchle na spustenie, ale obmedzujú dizajn, funkcie aj SEO a často sa platia mesačne. E-shop na mieru vám dáva voľnosť: vlastný dizajn, presne vaše procesy a žiadne limity. Pomôžeme vám zvoliť rozumné riešenie podľa počtu produktov a plánov rastu.'],
            ],
            [
                'h' => 'SEO pre e-shopy',
                'p' => ['E-shop bez návštevnosti nepredáva. Pri tvorbe e-shopu preto riešime aj SEO: štruktúru kategórií, popisy produktov, rýchlosť, štruktúrované dáta produktov a mapu stránok, aby sa vaše produkty zobrazovali v Google.'],
            ],
        ],
        'steps' => [
            ['Analýza', 'Zistíme, čo predávate, komu a ako, a navrhneme vhodné riešenie e-shopu.'],
            ['Dizajn a štruktúra', 'Pripravíme prehľadný dizajn a štruktúru kategórií orientovanú na nákup.'],
            ['Vývoj a napojenia', 'Naprogramujeme e-shop a prepojíme platby, dopravu a fakturáciu.'],
            ['Spustenie', 'Naplníme produkty, otestujeme celý nákupný proces a e-shop spustíme.'],
        ],
        'faq' => [
            ['Koľko stojí tvorba e-shopu?', 'Cena závisí od počtu produktov, funkcií a napojení (platby, doprava, fakturácia). Cenovú ponuku dostanete nezáväzne a zadarmo.'],
            ['Dá sa e-shop prepojiť na faktúry a sklad?', 'Áno. E-shop vieme prepojiť na fakturačné systémy, platobné brány, dopravcov aj skladové systémy.'],
            ['Ako dlho trvá tvorba e-shopu?', 'Závisí od rozsahu, bežne 4 až 8 týždňov od schválenia návrhu.'],
            ['Môžem si produkty pridávať sám?', 'Áno. Administrácia je jednoduchá a súčasťou je zaškolenie. Produkty viete pridávať aj hromadne.'],
        ],
        'related' => ['tvorba-webstranok', 'web-na-mieru', 'seo-optimalizacia'],
    ],

    'svadobna-fotografia' => [
        'nav' => 'Svadobná fotografia',
        'title' => 'Svadobná fotografia a svadobný fotograf | Clicki',
        'desc' => 'Svadobný fotograf, ktorý zachytí vašu svadbu prirodzene a bez pózovania. Svadobná fotografia po celom Slovensku, ukážky prác a nezáväzná ponuka.',
        'h1' => 'Svadobná fotografia, ktorá vás vráti do toho dňa',
        'eyebrow' => 'Svadobný fotograf',
        'lead' => 'Svadobný deň ubehne rýchlo. Fotografie zostanú. Zachytíme vašu svadbu prirodzene, bez umelého pózovania, tak aby ste sa pri nich o roky neskôr vedeli smiať aj dojať.',
        'cta' => 'Chcem svadobného fotografa',
        'projects' => 'svadobna-fotografia',
        'sections' => [
            [
                'h' => 'Svadobný fotograf, ktorý nie je na obtiaž',
                'p' => ['Vieme, že fotograf môže byť na svadbe niekto, koho si hosti všímajú až príliš. Preto pracujeme nenápadne, držíme sa v pozadí a zachytávame skutočné emócie: smiech rodičov, pohľad ženícha, slzy pri sľube. Aj tie krajšie portréty vznikajú v pokojnej atmosfére, nie v časovej tiesni.'],
            ],
            [
                'h' => 'Čo zahŕňa svadobná fotografia',
                'ul' => [
                    'Prípravy nevesty a ženícha.',
                    'Obrad (cirkevný aj civilný) a gratulácie.',
                    'Skupinové a rodinné fotografie.',
                    'Portréty novomanželov a fotenie vonku.',
                    'Hostina, zábava a zábery z večera.',
                    'Profesionálne spracované fotografie v online galérii.',
                ],
            ],
            [
                'h' => 'Ako prebieha spolupráca',
                'p' => ['Najprv sa spoznáme, preberieme váš plán dňa a vaše predstavy. Spolu pripravíme zoznam dôležitých záberov a ľudí, ktorých nemôžu chýbať. V deň svadby sa o všetko postaráme, vy si ho užívate. Fotografie dostanete spracované v dohodnutom termíne.'],
            ],
            [
                'h' => 'Svadobná fotografia po celom Slovensku',
                'p' => ['Svadby fotíme po celom Slovensku. Ak plánujete svadbu v zahraničí alebo vo výnimočnom prostredí, radi sa prispôsobíme.'],
            ],
        ],
        'steps' => [
            ['Prvý kontakt', 'Napíšete nám dátum a miesto svadby. Ozveme sa s dostupnosťou a ponukou.'],
            ['Stretnutie', 'Preberieme plán dňa, vaše priania a dôležité momenty.'],
            ['Svadobný deň', 'Fotografujeme nenápadne a prirodzene od príprav až po večer.'],
            ['Fotografie', 'Vybrané a spracované zábery dostanete v online galérii.'],
        ],
        'faq' => [
            ['Ako dlho dopredu treba rezervovať svadobného fotografa?', 'Najlepšie čo najskôr, ideálne 6 až 12 mesiacov vopred, hlavne pri letných sobotách. Ak máte menej času, napíšte, pozrieme sa na voľné termíny.'],
            ['Koľko fotografií dostaneme?', 'Počet závisí od dĺžky svadby. Bežne ide o stovky spracovaných záberov. Presný rozsah dohodneme v ponuke.'],
            ['Kedy dostaneme fotografie?', 'Spracované fotografie odovzdávame v dohodnutom termíne, štandardne do niekoľkých týždňov, ukážky pár dní po svadbe.'],
            ['Fotíte aj svadby mimo vášho mesta?', 'Áno, fotíme po celom Slovensku. Cestovné náklady sú súčasťou ponuky.'],
        ],
        'related' => ['tvorba-webstranok', 'web-na-mieru', 'seo-optimalizacia'],
    ],

    'seo-optimalizacia' => [
        'nav' => 'SEO optimalizácia',
        'title' => 'SEO optimalizácia webu — buďte vidieť v Google | Clicki',
        'desc' => 'SEO optimalizácia webstránok a e-shopov: technický audit, kľúčové slová, obsah a lokálne SEO. Dostaneme váš web vyššie vo výsledkoch Google.',
        'h1' => 'SEO optimalizácia, vďaka ktorej vás nájdu zákazníci',
        'eyebrow' => 'SEO optimalizácia',
        'lead' => 'Ak vás zákazník nenájde v Google, pre neho neexistujete. SEO optimalizáciou zlepšíme technický stav webu, obsah aj dôveryhodnosť tak, aby sa váš web zobrazoval pri hľadaniach, na ktorých vám záleží.',
        'cta' => 'Chcem SEO audit',
        'projects' => 'web',
        'sections' => [
            [
                'h' => 'Čo obnáša SEO optimalizácia',
                'ul' => [
                    '**Technický audit:** indexácia, štruktúra, rýchlosť, mobilné zobrazenie, chyby.',
                    '**Kľúčové slová:** zistíme, čo ľudia v Google skutočne hľadajú.',
                    '**On-page SEO:** nadpisy, popisy, obsah a interné prepojenia.',
                    '**Obsah:** články a stránky, ktoré odpovedajú na otázky zákazníkov.',
                    '**Lokálne SEO:** Google Business profil a mapy.',
                    '**Meranie:** sledujeme poradie, návštevnosť a dopyty.',
                ],
            ],
            [
                'h' => 'Prečo je SEO dôležité',
                'p' => ['Väčšina ľudí klikne na jeden z prvých výsledkov. SEO je dlhodobá investícia: na rozdiel od reklamy neplatíte za každé kliknutie a výsledky vydržia aj po skončení optimalizácie. Je to najlacnejší spôsob, ako získavať zákazníkov, ktorí vás už aktívne hľadajú.'],
            ],
            [
                'h' => 'Ako dlho trvá, kým SEO zaberie',
                'p' => ['Prvé zmeny bývajú vidieť za niekoľko týždňov, stabilné výsledky za 3 až 6 mesiacov podľa konkurencie. Nesľubujeme „prvé miesto zaručene“, to nemôže zaručiť nikto. Sľubujeme poctivú prácu, jasné reporty a postupný rast.'],
            ],
            [
                'h' => 'SEO a rýchlosť webu',
                'p' => ['Rýchlosť načítania je dôležitý faktor poradia aj predaja. Súčasťou optimalizácie je preto aj zrýchlenie webu: obrázky, kód, cache a hosting.'],
            ],
        ],
        'steps' => [
            ['Audit', 'Prejdeme web a nájdeme technické chyby aj príležitosti.'],
            ['Stratégia', 'Zvolíme kľúčové slová a plán úprav podľa vášho odvetvia.'],
            ['Optimalizácia', 'Opravíme chyby, upravíme obsah a pripravíme nové stránky.'],
            ['Meranie a rast', 'Sledujeme výsledky a postupne ich zlepšujeme.'],
        ],
        'faq' => [
            ['Čo je SEO optimalizácia?', 'Je to súbor úprav webu a obsahu, vďaka ktorým vás vyhľadávače lepšie pochopia a ukážu ľuďom, ktorí hľadajú vaše služby alebo produkty.'],
            ['Ako dlho trvá, kým uvidím výsledky?', 'Prvé zmeny sú vidieť za niekoľko týždňov, stabilný rast zvyčajne za 3 až 6 mesiacov.'],
            ['Zaručíte prvé miesto v Google?', 'Nie. Poradie ovplyvňuje veľa faktorov a nik ho nemôže zaručiť. Sľubujeme transparentnú prácu a merateľné zlepšenie.'],
            ['Robíte SEO aj pre web, ktorý ste nerobili vy?', 'Áno. SEO vieme robiť pre ľubovoľný web. Najprv spravíme audit a povieme, čo má zmysel opraviť.'],
        ],
        'related' => ['tvorba-webstranok', 'tvorba-eshopov', 'web-na-mieru'],
    ],
];
