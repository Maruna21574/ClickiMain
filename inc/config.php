<?php
/**
 * Clicki — základná konfigurácia.
 * Pri nasadení na Websupport uprav ADMIN_EMAIL a MAIL_FROM podľa reálnej domény.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_ROOT', dirname(__DIR__));
define('DATA_DIR', SITE_ROOT . '/data');
define('DB_PATH', DATA_DIR . '/clicki.sqlite');
define('UPLOADS_DIR', SITE_ROOT . '/uploads/projects');
define('UPLOADS_URL', '/uploads/projects');

define('SITE_NAME', 'Clicki');
define('ADMIN_EMAIL', 'info@clicki.sk');
define('MAIL_FROM', 'web@clicki.sk');

define('MAX_UPLOAD_BYTES', 6 * 1024 * 1024);
define('ALLOWED_IMAGE_EXT', ['jpg', 'jpeg', 'png', 'webp']);

date_default_timezone_set('Europe/Bratislava');

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Údaje o prevádzkovateľovi pre stránku Ochrana osobných údajov — zobrazia sa len vyplnené riadky.
define('LEGAL_NAME', 'Clicki');
define('LEGAL_ICO', '');
define('LEGAL_ADDRESS', '');

// Rýchly kontakt (plávajúce tlačidlo, kontaktná stránka, rezervácia hovoru)
define('CONTACT_PHONE', '+421940391243');
define('CONTACT_PHONE_DISPLAY', '+421 940 391 243');
define('CONTACT_WHATSAPP', '421940391243');
