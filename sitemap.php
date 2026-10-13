<?php
/** XML sitemap pre vyhľadávače: stránky, vstupné SEO stránky, projekty a články blogu (dostupná aj ako /sitemap.xml). */
require_once __DIR__ . '/inc/functions.php';

$urls = [];
foreach (['/', '/sluzby.php', '/portfolio.php', '/o-nas.php', '/kontakt.php', '/ponuka.php', '/blog.php', '/konzultacia', '/gdpr.php', '/cookies.php'] as $path) {
    $urls[] = [$path, null, $path === '/' ? '1.0' : '0.7'];
}
foreach (array_keys(landing_pages()) as $slug) {
    $urls[] = ['/' . $slug, null, '0.9'];
}
foreach (get_projects() as $p) {
    $urls[] = ['/projekt.php?slug=' . urlencode($p['slug']), substr((string)$p['created_at'], 0, 10), '0.6'];
}
foreach (get_posts() as $post) {
    $urls[] = ['/clanok.php?slug=' . urlencode($post['slug']), $post['published_at'], '0.6'];
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $mod, $prio]) {
    echo '  <url><loc>' . htmlspecialchars(site_url($path), ENT_XML1) . '</loc>'
        . ($mod ? '<lastmod>' . h($mod) . '</lastmod>' : '')
        . '<priority>' . $prio . '</priority></url>' . "\n";
}
echo "</urlset>\n";
