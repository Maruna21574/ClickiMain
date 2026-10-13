<?php
/** robots.txt generovaný dynamicky, aby obsahoval absolútnu adresu sitemapy (dostupný aj ako /robots.txt cez .htaccess). */
require_once __DIR__ . '/inc/functions.php';
header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\n";
echo "Disallow: /admin/\n";
echo "Disallow: /data/\n";
echo "Disallow: /inc/\n";
echo "Disallow: /*?lang=\n";
echo "Allow: /\n\n";
echo 'Sitemap: ' . site_url('/sitemap.xml') . "\n";
