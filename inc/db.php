<?php
/**
 * PDO SQLite pripojenie. Pri prvom behu vytvorí schému a naplní demo dátami,
 * nech admin archív a portfólio nie sú pri odovzdaní prázdne.
 */

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $isNew = !file_exists(DB_PATH);
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON');

    if ($isNew) {
        db_install($pdo);
        db_seed($pdo);
    }

    return $pdo;
}

function db_install(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT NOT NULL UNIQUE,
            name_sk TEXT NOT NULL,
            name_en TEXT NOT NULL,
            icon TEXT NOT NULL DEFAULT 'star',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT NOT NULL UNIQUE,
            category_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
            title_sk TEXT NOT NULL,
            title_en TEXT NOT NULL,
            summary_sk TEXT NOT NULL DEFAULT '',
            summary_en TEXT NOT NULL DEFAULT '',
            description_sk TEXT NOT NULL DEFAULT '',
            description_en TEXT NOT NULL DEFAULT '',
            client TEXT NOT NULL DEFAULT '',
            year TEXT NOT NULL DEFAULT '',
            cover_image TEXT NOT NULL DEFAULT '',
            featured INTEGER NOT NULL DEFAULT 0,
            sort_order INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE project_images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id INTEGER NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
            image_path TEXT NOT NULL,
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL DEFAULT '',
            budget TEXT NOT NULL DEFAULT '',
            message TEXT NOT NULL,
            is_read INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );
    ");
}

function db_seed(PDO $pdo): void
{
    $categories = [
        ['web', 'Weby a aplikácie', 'Websites & Apps', 'code', 1],
        ['konfiguratory', 'Konfigurátory na mieru', 'Custom Configurators', 'sliders', 2],
        ['socialne-siete', 'Sociálne siete', 'Social Media', 'share', 3],
        ['grafika', 'Grafický dizajn', 'Graphic Design', 'palette', 4],
        ['foto', 'Fotografia', 'Photography', 'camera', 5],
        ['dron', 'Dronové zábery', 'Drone Footage', 'drone', 6],
    ];
    $catStmt = $pdo->prepare('INSERT INTO categories (slug, name_sk, name_en, icon, sort_order) VALUES (?, ?, ?, ?, ?)');
    $catIds = [];
    foreach ($categories as $c) {
        $catStmt->execute($c);
        $catIds[$c[0]] = (int)$pdo->lastInsertId();
    }

    $projects = [
        ['nova-interier', 'web', 'Nová Interiér — e-shop s dizajnovým nábytkom', 'Nová Interiér — designer furniture e-shop',
            'Rýchly e-shop s vlastným checkoutom a prepojením na sklad.', 'Fast e-shop with a custom checkout and live stock sync.',
            'Kompletný redizajn e-shopu vrátane výkonnostnej optimalizácie, vlastného administrátorského rozhrania a prepojenia na skladový systém klienta. Dôraz na rýchlosť načítania a konverzný pomer.',
            'Full e-shop redesign including performance optimisation, a custom back office and integration with the client\'s warehouse system. Focus on load speed and conversion rate.',
            'Nová Interiér s.r.o.', '2025', 1, 1],
        ['bistro-verde', 'web', 'Bistro Verde — web s online rezerváciami', 'Bistro Verde — web with online reservations',
            'Reštauračný web s rezervačným systémom a digitálnym menu.', 'Restaurant site with a booking system and digital menu.',
            'Moderný web pre bistro so živým menu, online rezerváciami stolov a napojením na Google Business profil.', 'A modern site for a bistro with a live menu, table reservations and a Google Business profile sync.',
            'Bistro Verde', '2024', 0, 2],

        ['moj-nabytok-konfigurator', 'konfiguratory', 'MôjNábytok — 3D konfigurátor kuchynských liniek', 'MyFurniture — 3D kitchen configurator',
            'Interaktívny konfigurátor kuchýň na mieru s okamžitou cenovou ponukou.', 'Interactive custom kitchen configurator with instant pricing.',
            'Webová aplikácia, v ktorej si zákazník poskladá kuchynskú linku krok za krokom — materiály, farby, doplnky — a dostane okamžitú orientačnú cenu aj 3D náhľad.', 'A web app where customers build a kitchen unit step by step — materials, colours, add-ons — with an instant price estimate and 3D preview.',
            'MôjNábytok s.r.o.', '2025', 1, 3],
        ['studio-kamenna-konfigurator', 'konfiguratory', 'Studio Kamenná — konfigurátor kamenných obkladov', 'Studio Kamenná — stone cladding configurator',
            'Nástroj na výber a kombinovanie kamenných obkladov naživo.', 'A tool for selecting and combining stone cladding live.',
            'Konfigurátor umožňuje kombinovať typy kameňa, škárovanie a osvetlenie priamo na fotografii fasády zákazníka.', 'The configurator lets users combine stone types, grouting and lighting directly on a photo of their own façade.',
            'Studio Kamenná', '2024', 0, 4],

        ['trendy-beauty-socialne', 'socialne-siete', 'Trendy Beauty — správa sociálnych sietí', 'Trendy Beauty — social media management',
            'Kompletná obsahová stratégia a denná správa Instagramu a TikToku.', 'Full content strategy and daily Instagram & TikTok management.',
            'Dlhodobá spolupráca zahŕňajúca tvorbu obsahového plánu, natáčanie reelov, grafiku a platenú propagáciu s mesačným reportingom.', 'A long-term collaboration covering content planning, reel production, graphics and paid promotion with monthly reporting.',
            'Trendy Beauty', '2025', 1, 5],
        ['urban-fit-gym-socialne', 'socialne-siete', 'Urban Fit Gym — rast komunity na sociálnych sieťach', 'Urban Fit Gym — social community growth',
            'Budovanie komunity a kampane na získavanie nových členov.', 'Community building and member-acquisition campaigns.',
            'Nastavenie obsahovej stratégie fitness štúdia zameranej na krátke video formáty a lokálne kampane.', 'Set up a content strategy for a fitness studio focused on short-form video and local campaigns.',
            'Urban Fit Gym', '2024', 0, 6],

        ['lumina-brand', 'grafika', 'Lumina — kompletná vizuálna identita', 'Lumina — full visual identity',
            'Branding od loga po firemný manuál a tlačoviny.', 'Branding from logo to brand guidelines and print.',
            'Návrh značky Lumina zahŕňal logo, farebnú paletu, typografiu, firemný manuál a sadu tlačovín pripravených na výrobu.', 'The Lumina brand design covered logo, colour palette, typography, a brand manual and a print-ready collateral set.',
            'Lumina Group', '2025', 1, 7],
        ['cafe-nero-grafika', 'grafika', 'Café Nero — obalový dizajn a menu', 'Café Nero — packaging & menu design',
            'Dizajn obalov, kávových vreciek a tlačeného menu.', 'Packaging, coffee bag and printed menu design.',
            'Kompletná grafická línia pre kaviareň — od vreciek na kávu po menu boardy a merch.', 'A full graphic line for a café — from coffee bags to menu boards and merch.',
            'Café Nero', '2024', 0, 8],

        ['wedding-stories-foto', 'foto', 'Wedding Stories — svadobná fotografia', 'Wedding Stories — wedding photography',
            'Celodenná svadobná reportáž v prirodzenom štýle.', 'Full-day wedding coverage in a natural documentary style.',
            'Reportážne svadobné fotenie kladúce dôraz na prirodzené momenty a atmosféru dňa.', 'Documentary-style wedding photography focused on natural moments and the atmosphere of the day.',
            'súkromný klient', '2025', 1, 9],
        ['product-line-foto', 'foto', 'Produktová fotografia — kolekcia doplnkov', 'Product photography — accessories collection',
            'Štúdiové zábery produktovej kolekcie pre e-shop.', 'Studio shots of a product collection for an e-shop.',
            'Séria produktových fotografií s dôrazom na konzistentné svetlo a pripravenosť na e-shop aj sociálne siete.', 'A product photography series focused on consistent lighting, ready for both e-commerce and social media.',
            'Accessories Co.', '2024', 0, 10],

        ['aerial-estate-dron', 'dron', 'Aerial Estate — letecké zábery nehnuteľností', 'Aerial Estate — real estate drone footage',
            'Dronové video a foto pre prezentáciu luxusných nehnuteľností.', 'Drone video and photo for luxury real estate listings.',
            'Letecké zábery a video prelety pozemkov a rezidencií pre realitnú kanceláriu, strihané na krátke prezentačné video.', 'Aerial photo and flythrough video of plots and residences for a real estate agency, edited into a short showcase video.',
            'Aerial Estate Realitná kancelária', '2025', 1, 11],
        ['festival-flyover-dron', 'dron', 'Festival Flyover — dronové zábery z podujatia', 'Festival Flyover — event drone coverage',
            'Dynamické letecké zábery z hudobného festivalu.', 'Dynamic aerial footage from a music festival.',
            'Dronové natáčanie počas dvojdňového hudobného festivalu, spracované do aftermovie.', 'Drone filming across a two-day music festival, edited into an aftermovie.',
            'Festival Flyover o.z.', '2024', 0, 12],
    ];

    $projStmt = $pdo->prepare('INSERT INTO projects
        (slug, category_id, title_sk, title_en, summary_sk, summary_en, description_sk, description_en, client, year, cover_image, featured, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $imgStmt = $pdo->prepare('INSERT INTO project_images (project_id, image_path, sort_order) VALUES (?, ?, ?)');

    foreach ($projects as $p) {
        [$slug, $catSlug, $titleSk, $titleEn, $sumSk, $sumEn, $descSk, $descEn, $client, $year, $featured, $order] = $p;
        $cover = '/assets/img/placeholder.php?title=' . urlencode($titleSk) . '&cat=' . urlencode($catSlug) . '&seed=' . urlencode($slug);
        $projStmt->execute([$slug, $catIds[$catSlug], $titleSk, $titleEn, $sumSk, $sumEn, $descSk, $descEn, $client, $year, $cover, $featured, $order]);
        $pid = (int)$pdo->lastInsertId();
        for ($i = 1; $i <= 3; $i++) {
            $imgStmt->execute([$pid, '/assets/img/placeholder.php?title=' . urlencode($titleSk) . '&cat=' . urlencode($catSlug) . '&seed=' . urlencode($slug . '-' . $i), $i]);
        }
    }
}
