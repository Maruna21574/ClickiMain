<?php
/**
 * Voľby konfigurátora na stránke "Získať ponuku" (ponuka.php) + interný cenník.
 * Návštevník ceny NEVIDÍ — slúžia len na orientačný odhad, ktorý príde adminovi
 * spolu s dopytom (Správy v administrácii + e-mail). Ceny sú v eurách.
 *
 * 'from' => true  = pri položke sa zobrazí "od" (cena závisí od rozsahu)
 * 'types'         = pre ktoré typy projektu sa skupina funkcií zobrazí
 */

return [
    // horná hranica odhadu = súčet × range_factor (napr. 790 € → 790 – 950 €)
    'range_factor' => 1.2,

    'note' => [
        'sk' => 'Cenovú ponuku pripravíme na mieru podľa vášho výberu — nezáväzne a zadarmo. Nie ste si istí? Nevadí, doladíme to spolu.',
        'en' => 'We\'ll prepare a tailored quote based on your choices — free and with no obligation. Not sure yet? No problem, we\'ll work it out together.',
    ],

    'types' => [
        'landing' => [
            'label' => ['sk' => 'Landing page', 'en' => 'Landing page'],
            'desc' => ['sk' => 'Jedna pôsobivá stránka pre produkt, akciu alebo službu.', 'en' => 'One striking page for a product, campaign or service.'],
            'price' => 390, 'icon' => 'layers',
        ],
        'web' => [
            'label' => ['sk' => 'Firemný web', 'en' => 'Business website'],
            'desc' => ['sk' => 'Prezentačný web s viacerými podstránkami a administráciou.', 'en' => 'A presentation website with several pages and an admin.'],
            'price' => 790, 'icon' => 'globe',
            'pages' => ['included' => 5, 'max' => 30, 'per_page' => 60],
        ],
        'eshop' => [
            'label' => ['sk' => 'E-shop', 'en' => 'E-shop'],
            'desc' => ['sk' => 'Internetový obchod s košíkom, objednávkami a správou produktov.', 'en' => 'An online store with a cart, orders and product management.'],
            'price' => 1490, 'icon' => 'trending-up',
            'products' => true,
        ],
        'custom' => [
            'label' => ['sk' => 'Web / aplikácia na mieru', 'en' => 'Custom website / app'],
            'desc' => ['sk' => 'Rezervácie, klientska zóna, interný systém — riešenie presne podľa vás.', 'en' => 'Bookings, client zone, internal system — built exactly for you.'],
            'price' => 2490, 'icon' => 'code', 'from' => true,
        ],
    ],

    'products' => [
        'small' => ['label' => ['sk' => 'Do 50 produktov', 'en' => 'Up to 50 products'], 'price' => 0],
        'medium' => ['label' => ['sk' => '50 – 500 produktov', 'en' => '50 – 500 products'], 'price' => 190],
        'large' => ['label' => ['sk' => 'Viac ako 500 produktov', 'en' => 'More than 500 products'], 'price' => 390],
    ],

    'feature_groups' => [
        'eshop' => [
            'label' => ['sk' => 'Funkcie e-shopu', 'en' => 'E-shop features'],
            'types' => ['eshop'],
            'items' => [
                'payment' => ['label' => ['sk' => 'Platobná brána (karta, Apple & Google Pay)', 'en' => 'Payment gateway (card, Apple & Google Pay)'], 'price' => 250],
                'invoices' => ['label' => ['sk' => 'Automatické faktúry (SuperFaktúra, iDoklad)', 'en' => 'Automatic invoices (SuperFaktúra, iDoklad)'], 'price' => 190],
                'shipping' => ['label' => ['sk' => 'Dopravcovia a výdajné miesta (Packeta, DPD, SPS)', 'en' => 'Carriers and pickup points (Packeta, DPD, SPS)'], 'price' => 190],
                'variants' => ['label' => ['sk' => 'Varianty produktov (veľkosti, farby)', 'en' => 'Product variants (sizes, colours)'], 'price' => 150],
                'coupons' => ['label' => ['sk' => 'Zľavové kupóny a akcie', 'en' => 'Discount codes and sales'], 'price' => 120],
                'accounts' => ['label' => ['sk' => 'Zákaznícke kontá a história objednávok', 'en' => 'Customer accounts and order history'], 'price' => 180],
                'import' => ['label' => ['sk' => 'Import produktov z XML / Excelu', 'en' => 'Product import from XML / Excel'], 'price' => 220],
                'feeds' => ['label' => ['sk' => 'Feed pre Heureku a Google Shopping', 'en' => 'Heureka and Google Shopping feeds'], 'price' => 150],
                'erp' => ['label' => ['sk' => 'Napojenie na sklad / ERP', 'en' => 'Warehouse / ERP integration'], 'price' => 450, 'from' => true],
            ],
        ],
        'features' => [
            'label' => ['sk' => 'Funkcie webu', 'en' => 'Website features'],
            'types' => ['landing', 'web', 'eshop', 'custom'],
            'items' => [
                'blog' => ['label' => ['sk' => 'Blog / novinky', 'en' => 'Blog / news'], 'price' => 150],
                'booking' => ['label' => ['sk' => 'Online rezervácie termínov', 'en' => 'Online appointment booking'], 'price' => 490],
                'newsletter' => ['label' => ['sk' => 'Newsletter (Ecomail, Mailchimp)', 'en' => 'Newsletter (Ecomail, Mailchimp)'], 'price' => 120],
                'members' => ['label' => ['sk' => 'Prihlásenie a klientska zóna', 'en' => 'Login and client zone'], 'price' => 390],
                'animations' => ['label' => ['sk' => 'Prémiový dizajn a animácie', 'en' => 'Premium design and animations'], 'price' => 290],
            ],
        ],
        'content' => [
            'label' => ['sk' => 'Obsah a vizuál', 'en' => 'Content and visuals'],
            'types' => ['landing', 'web', 'eshop', 'custom'],
            'items' => [
                'copywriting' => ['label' => ['sk' => 'Napísanie textov (copywriting)', 'en' => 'Copywriting'], 'price' => 190],
                'logo' => ['label' => ['sk' => 'Logo a vizuálna identita', 'en' => 'Logo and visual identity'], 'price' => 290],
                'photos' => ['label' => ['sk' => 'Profesionálne fotky (s Attelier Kay)', 'en' => 'Professional photos (with Attelier Kay)'], 'price' => 190, 'from' => true],
                'seo' => ['label' => ['sk' => 'SEO optimalizácia pri spustení', 'en' => 'SEO optimisation at launch'], 'price' => 190],
            ],
        ],
    ],

    'languages' => [
        'per' => 250,
        'options' => [
            0 => ['sk' => 'Iba slovenčina', 'en' => 'Slovak only'],
            1 => ['sk' => '+1 jazyk', 'en' => '+1 language'],
            2 => ['sk' => '+2 jazyky', 'en' => '+2 languages'],
            3 => ['sk' => '+3 jazyky', 'en' => '+3 languages'],
        ],
    ],

    // mesačné platby — správa webu
    'care' => [
        'none' => ['label' => ['sk' => 'Bez správy', 'en' => 'No maintenance'], 'desc' => ['sk' => 'Web si spravujete sami.', 'en' => 'You manage the website yourself.'], 'price' => 0],
        'start' => ['label' => ['sk' => 'Správa Štart', 'en' => 'Care Start'], 'desc' => ['sk' => 'Aktualizácie, zálohy, bezpečnosť, 1 h úprav mesačne.', 'en' => 'Updates, backups, security, 1 h of edits a month.'], 'price' => 29],
        'pro' => ['label' => ['sk' => 'Správa Pro', 'en' => 'Care Pro'], 'desc' => ['sk' => 'Všetko zo Štartu + 3 h úprav, sledovanie rýchlosti a SEO.', 'en' => 'Everything in Start + 3 h of edits, speed and SEO monitoring.'], 'price' => 59],
    ],

    'express' => [
        'factor' => 0.25,
        'label' => ['sk' => 'Expresné dodanie (prednostne, do 2 týždňov)', 'en' => 'Express delivery (priority, within 2 weeks)'],
    ],
];
