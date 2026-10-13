<?php
require_once __DIR__ . '/inc/functions.php';

$legalKey = 'privacy';
$page = legal_content($legalKey);
$pageTitle = $page['meta_title'] ?? 'Clicki';
$pageDesc = $page['meta_desc'] ?? '';
$activeNav = '';

require __DIR__ . '/templates/header.php';
require __DIR__ . '/templates/legal-page.php';
require __DIR__ . '/templates/footer.php';
