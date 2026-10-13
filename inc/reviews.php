<?php
/**
 * Recenzie pre slider (templates/reviews.php) — zobrazujú sa na úvodnej stránke a na stránke O nás.
 *
 * !!! UKÁŽKOVÉ TEXTY !!! Mená aj recenzie sú vymyslené, len aby bolo vidieť, ako sekcia vyzerá.
 * Pred spustením webu ich NAHRAĎ skutočnými recenziami z Google profilu (alebo sekciu vypni
 * nastavením 'enabled' => false). Zobrazovať vymyslené recenzie s logom Google je klamlivé
 * a v EÚ aj protiprávne (smernica o nekalých obchodných praktikách, pravidlá Google).
 *
 * 'time'  = text pri mene ("pred 2 týždňami"), 'stars' = 1–5, 'color' = farba kruhu s iniciálkou.
 * 'url'   = odkaz na tvoj Google profil (tlačidlo "Všetky recenzie"); prázdne = bez odkazu.
 */

return [
    'enabled' => true,
    'url' => '',
    'items' => [
        [
            'name' => 'Martin K.', 'color' => '#E8710A', 'stars' => 5,
            'time' => ['sk' => 'pred 2 týždňami', 'en' => '2 weeks ago'],
            'text' => [
                'sk' => 'Nový web sme mali hotový presne v termíne a vyzerá lepšie, než sme čakali. Hneď po spustení nám začali chodiť dopyty. Komunikácia bola od začiatku jasná a bez zbytočných kolečiek.',
                'en' => 'Our new website was ready right on schedule and looks better than we expected. Inquiries started coming in right after launch. Communication was clear from the start, with no unnecessary hoops.',
            ],
        ],
        [
            'name' => 'Lucia H.', 'color' => '#1A73E8', 'stars' => 5,
            'time' => ['sk' => 'pred mesiacom', 'en' => 'a month ago'],
            'text' => [
                'sk' => 'Konečne niekto, kto rozumie tomu, ako spojiť web, sociálne siete aj fotky do jedného štýlu. Naša značka pôsobí úplne inak a zákazníci nám to hovoria sami.',
                'en' => 'Finally someone who understands how to bring a website, social media and photos together in one style. Our brand feels completely different now and customers tell us so themselves.',
            ],
        ],
        [
            'name' => 'Peter V.', 'color' => '#188038', 'stars' => 5,
            'time' => ['sk' => 'pred 2 mesiacmi', 'en' => '2 months ago'],
            'text' => [
                'sk' => 'E-shop beží rýchlo a správa produktov je jednoduchá aj pre mňa, ako netechnického človeka. Keď som potreboval úpravu, bolo to vybavené v ten istý deň.',
                'en' => 'The e-shop runs fast and managing products is simple even for a non-technical person like me. When I needed a change, it was done the same day.',
            ],
        ],
        [
            'name' => 'Zuzana M.', 'color' => '#A142F4', 'stars' => 5,
            'time' => ['sk' => 'pred 3 mesiacmi', 'en' => '3 months ago'],
            'text' => [
                'sk' => 'Profesionálny prístup, pekný dizajn a hlavne férová cena. Oceňujem, že nám všetko vysvetlili po ľudsky a poradili aj to, čo sme sa nepýtali.',
                'en' => 'Professional approach, beautiful design and, above all, a fair price. I appreciate that everything was explained in plain language and we got advice on things we did not even ask about.',
            ],
        ],
        [
            'name' => 'Tomáš B.', 'color' => '#D93025', 'stars' => 4,
            'time' => ['sk' => 'pred 4 mesiacmi', 'en' => '4 months ago'],
            'text' => [
                'sk' => 'Skvelá práca na SEO — za pár týždňov sme sa posunuli na prvú stranu vyhľadávania na kľúčové slová. Pri texte bolo treba pár kôl úprav, ale výsledok stál za to.',
                'en' => 'Great SEO work — within a few weeks we moved to the first page of search results for our key terms. The copy needed a few rounds of edits, but the result was worth it.',
            ],
        ],
        [
            'name' => 'Jana S.', 'color' => '#00796B', 'stars' => 5,
            'time' => ['sk' => 'pred 5 mesiacmi', 'en' => '5 months ago'],
            'text' => [
                'sk' => 'Fotky aj web od jedného tímu — ušetrilo nám to kopu starostí a koordinácie. Odporúčam každému, kto chce mať online prezentáciu v poriadku.',
                'en' => 'Photos and website from one team — it saved us a lot of hassle and coordination. I recommend them to anyone who wants their online presence done right.',
            ],
        ],
        [
            'name' => 'Michaela D.', 'color' => '#E52592', 'stars' => 5,
            'time' => ['sk' => 'pred 6 mesiacmi', 'en' => '6 months ago'],
            'text' => [
                'sk' => 'Správu webu nechávame na nich už pol roka. Všetko je aktualizované, zálohované a keď treba niečo zmeniť, stačí jeden e-mail. Konečne sa o web nemusíme starať.',
                'en' => 'We have left our website maintenance to them for half a year. Everything is updated and backed up, and when something needs changing, one email is enough. We finally do not have to worry about the website.',
            ],
        ],
        [
            'name' => 'Ondrej P.', 'color' => '#3949AB', 'stars' => 5,
            'time' => ['sk' => 'pred 7 mesiacmi', 'en' => '7 months ago'],
            'text' => [
                'sk' => 'Potrebovali sme rezervačný systém na mieru a dostali sme presne to, čo sme si predstavovali. Zákazníci si termíny rezervujú sami a nám ubudlo telefonátov.',
                'en' => 'We needed a custom booking system and got exactly what we had in mind. Customers book appointments themselves and we get far fewer phone calls.',
            ],
        ],
        [
            'name' => 'Katarína R.', 'color' => '#F9AB00', 'stars' => 5,
            'time' => ['sk' => 'pred 8 mesiacmi', 'en' => '8 months ago'],
            'text' => [
                'sk' => 'Grafika, logo aj sociálne siete v jednom štýle. Prvýkrát máme pocit, že naša firma pôsobí tak, ako si zaslúži. Veľké ďakujem za trpezlivosť pri ladení detailov.',
                'en' => 'Graphics, logo and social media all in one style. For the first time we feel our company looks the way it deserves to. Thank you for your patience with all the little details.',
            ],
        ],
        [
            'name' => 'Marek L.', 'color' => '#546E7A', 'stars' => 5,
            'time' => ['sk' => 'pred 9 mesiacmi', 'en' => '9 months ago'],
            'text' => [
                'sk' => 'Web sa načíta za zlomok sekundy a na mobile vyzerá výborne. Po optimalizácii nám výrazne klesol počet ľudí, ktorí odchádzajú hneď z úvodnej stránky.',
                'en' => 'The website loads in a fraction of a second and looks great on mobile. After the optimisation, far fewer visitors leave straight from the homepage.',
            ],
        ],
        [
            'name' => 'Simona T.', 'color' => '#8E24AA', 'stars' => 4,
            'time' => ['sk' => 'pred rokom', 'en' => 'a year ago'],
            'text' => [
                'sk' => 'Spokojní s webom aj so spoluprácou. Termín sa o pár dní posunul, ale boli sme o tom informovaní vopred a výsledok je veľmi pekný.',
                'en' => 'Happy with both the website and the collaboration. The deadline slipped by a few days, but we were told in advance and the result is very nice.',
            ],
        ],
        [
            'name' => 'Branislav Z.', 'color' => '#2E7D32', 'stars' => 5,
            'time' => ['sk' => 'pred rokom', 'en' => 'a year ago'],
            'text' => [
                'sk' => 'Rýchla komunikácia, férové ceny a výsledok, ktorý prekonal očakávania. Určite oslovíme znova pri ďalšom projekte.',
                'en' => 'Fast communication, fair prices and a result that exceeded expectations. We will definitely get in touch again for our next project.',
            ],
        ],
    ],
];
