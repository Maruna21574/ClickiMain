<?php
/**
 * PDO SQLite pripojenie. Pri prvom behu vytvorí schému a základné kategórie
 * (projekty sa pridávajú cez /admin).
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
    db_migrate($pdo);

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

/**
 * Postupné úpravy schémy pre už existujúce databázy (verzia v PRAGMA user_version).
 * Nová zmena = nový blok `if ($version < N)` na konci.
 */
function db_migrate(PDO $pdo): void
{
    $version = (int)$pdo->query('PRAGMA user_version')->fetchColumn();

    if ($version < 1) {
        $cols = array_column($pdo->query('PRAGMA table_info(projects)')->fetchAll(PDO::FETCH_ASSOC), 'name');
        if (!in_array('live_url', $cols, true)) {
            $pdo->exec("ALTER TABLE projects ADD COLUMN live_url TEXT NOT NULL DEFAULT ''");
        }
        $pdo->exec('PRAGMA user_version = 1');
    }

    if ($version < 2) {
        $cols = array_column($pdo->query('PRAGMA table_info(messages)')->fetchAll(PDO::FETCH_ASSOC), 'name');
        if (!in_array('website', $cols, true)) {
            $pdo->exec("ALTER TABLE messages ADD COLUMN website TEXT NOT NULL DEFAULT ''");
        }
        $pdo->exec('PRAGMA user_version = 2');
    }
}

function db_seed(PDO $pdo): void
{
    $categories = [
        ['web', 'Weby a aplikácie', 'Websites & Apps', 'code', 1],
        ['socialne-siete', 'Sociálne siete', 'Social Media', 'share', 3],
        ['grafika', 'Grafický dizajn', 'Graphic Design', 'palette', 4],
        ['foto', 'Fotografia', 'Photography', 'camera', 5],
    ];
    $catStmt = $pdo->prepare('INSERT INTO categories (slug, name_sk, name_en, icon, sort_order) VALUES (?, ?, ?, ?, ?)');
    foreach ($categories as $c) {
        $catStmt->execute($c);
    }
}
