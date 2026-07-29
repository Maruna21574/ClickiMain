<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/* ---------- output / escaping ---------- */

function h(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/* ---------- language ---------- */

function current_lang(): string
{
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['sk', 'en'], true)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    return $_SESSION['lang'] ?? 'sk';
}

function load_i18n(): array
{
    static $dict = null;
    if ($dict === null) {
        $file = __DIR__ . '/i18n/' . current_lang() . '.php';
        $dict = file_exists($file) ? require $file : [];
    }
    return $dict;
}

function t(string $key): string
{
    $dict = load_i18n();
    $val = $dict[$key] ?? $key;
    return is_string($val) ? $val : $key;
}

/** Ako t(), ale pre štruktúrovaný obsah (polia — services, faq, testimonials...). */
function td(string $key): array
{
    $dict = load_i18n();
    $val = $dict[$key] ?? [];
    return is_array($val) ? $val : [];
}

/** Vyberie SK/EN pole z DB riadku, napr. field($project, 'title') -> title_sk / title_en */
function field(array $row, string $base): string
{
    $key = $base . '_' . current_lang();
    return $row[$key] ?? ($row[$base . '_sk'] ?? '');
}

/** Skratka pre názov kategórie naviazanej na projekt (category_name_sk/category_name_en z JOINu). */
function cat_name(array $projectRow): string
{
    return field($projectRow, 'category_name');
}

/** Cover obrázok projektu s bezpečným fallbackom, ak admin zatiaľ nenahral fotku. */
function cover_image(array $projectRow): string
{
    if (!empty($projectRow['cover_image'])) {
        return $projectRow['cover_image'];
    }
    $cat = $projectRow['category_slug'] ?? 'web';
    return '/assets/img/placeholder.php?title=' . urlencode(field($projectRow, 'title')) . '&cat=' . urlencode($cat);
}

function lang_url(string $lang): string
{
    $qs = $_GET;
    $qs['lang'] = $lang;
    return '?' . http_build_query($qs);
}

/* ---------- misc helpers ---------- */

function slugify(string $text): string
{
    $map = [
        'á'=>'a','ä'=>'a','č'=>'c','ď'=>'d','é'=>'e','í'=>'i','ĺ'=>'l','ľ'=>'l','ň'=>'n',
        'ó'=>'o','ô'=>'o','ŕ'=>'r','š'=>'s','ť'=>'t','ú'=>'u','ý'=>'y','ž'=>'z',
        'Á'=>'a','Ä'=>'a','Č'=>'c','Ď'=>'d','É'=>'e','Í'=>'i','Ĺ'=>'l','Ľ'=>'l','Ň'=>'n',
        'Ó'=>'o','Ô'=>'o','Ŕ'=>'r','Š'=>'s','Ť'=>'t','Ú'=>'u','Ý'=>'y','Ž'=>'z',
    ];
    $text = strtr($text, $map);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function excerpt(string $text, int $len = 140): string
{
    $text = trim($text);
    if (mb_strlen($text) <= $len) {
        return $text;
    }
    return mb_substr($text, 0, $len) . '…';
}

function format_date(string $sqlDate): string
{
    $ts = strtotime($sqlDate);
    return $ts ? date('j.n.Y', $ts) : $sqlDate;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    $sent = $_POST['csrf_token'] ?? '';
    return !empty($sent) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $sent);
}

/* ---------- auth ---------- */

function admin_exists(): bool
{
    $count = db()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    return (int)$count > 0;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_login(): void
{
    if (!admin_exists()) {
        redirect('/admin/login.php');
    }
    if (!is_logged_in()) {
        redirect('/admin/login.php');
    }
}

function attempt_login(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        return true;
    }
    return false;
}

/* ---------- uploads ---------- */

function handle_image_upload(string $fieldKey): ?string
{
    if (empty($_FILES[$fieldKey])) {
        return null;
    }
    return handle_single_upload($_FILES[$fieldKey]);
}

function handle_single_upload(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > MAX_UPLOAD_BYTES) {
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_IMAGE_EXT, true)) {
        return null;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
        return null;
    }
    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }
    $name = bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOADS_DIR . '/' . $name)) {
        return null;
    }
    return UPLOADS_URL . '/' . $name;
}

/** Nahrá viacero súborov z input[name="field[]" multiple], vráti pole URL ciest. */
function handle_multi_upload(string $fieldKey): array
{
    if (empty($_FILES[$fieldKey]) || !is_array($_FILES[$fieldKey]['name'])) {
        return [];
    }
    $paths = [];
    $count = count($_FILES[$fieldKey]['name']);
    for ($i = 0; $i < $count; $i++) {
        $file = [
            'name' => $_FILES[$fieldKey]['name'][$i],
            'type' => $_FILES[$fieldKey]['type'][$i],
            'tmp_name' => $_FILES[$fieldKey]['tmp_name'][$i],
            'error' => $_FILES[$fieldKey]['error'][$i],
            'size' => $_FILES[$fieldKey]['size'][$i],
        ];
        $path = handle_single_upload($file);
        if ($path) {
            $paths[] = $path;
        }
    }
    return $paths;
}

/* ---------- data access ---------- */

function get_categories(): array
{
    return db()->query('SELECT * FROM categories ORDER BY sort_order ASC')->fetchAll(PDO::FETCH_ASSOC);
}

function get_category_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM categories WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function get_projects(?string $categorySlug = null, ?int $limit = null, bool $featuredOnly = false): array
{
    $sql = 'SELECT p.*, c.slug AS category_slug, c.name_sk AS category_name_sk, c.name_en AS category_name_en
            FROM projects p JOIN categories c ON c.id = p.category_id WHERE 1=1';
    $params = [];
    if ($categorySlug) {
        $sql .= ' AND c.slug = ?';
        $params[] = $categorySlug;
    }
    if ($featuredOnly) {
        $sql .= ' AND p.featured = 1';
    }
    $sql .= ' ORDER BY p.sort_order ASC, p.id DESC';
    if ($limit) {
        $sql .= ' LIMIT ' . (int)$limit;
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_project_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT p.*, c.slug AS category_slug, c.name_sk AS category_name_sk, c.name_en AS category_name_en
        FROM projects p JOIN categories c ON c.id = p.category_id WHERE p.slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function get_project_images(int $projectId): array
{
    $stmt = db()->prepare('SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC');
    $stmt->execute([$projectId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ---------- icons (inline SVG, stroke style) ---------- */

function icon(string $name, string $class = 'icon'): string
{
    $paths = [
        'code'        => '<polyline points="8 6 2 12 8 18"></polyline><polyline points="16 6 22 12 16 18"></polyline>',
        'sliders'     => '<line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><circle cx="4" cy="12" r="2"></circle><circle cx="12" cy="10" r="2"></circle><circle cx="20" cy="14" r="2"></circle>',
        'share'       => '<circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.6" y1="10.5" x2="15.4" y2="6.5"></line><line x1="8.6" y1="13.5" x2="15.4" y2="17.5"></line>',
        'palette'     => '<circle cx="12" cy="12" r="9"></circle><circle cx="8.5" cy="10.5" r="1.2" fill="currentColor" stroke="none"></circle><circle cx="12" cy="8" r="1.2" fill="currentColor" stroke="none"></circle><circle cx="15.5" cy="10.5" r="1.2" fill="currentColor" stroke="none"></circle><path d="M8 15c1 1.2 2.4 1.5 4 1.5 2.2 0 3-1 3-2.2 0-1-1-1.3-1-2.3 0-1.3 1.2-1.5 2.5-1.3"></path>',
        'camera'      => '<path d="M4 8h3l1.5-2h7L17 8h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"></path><circle cx="12" cy="14" r="3.5"></circle>',
        'drone'       => '<circle cx="5" cy="5" r="2.2"></circle><circle cx="19" cy="5" r="2.2"></circle><circle cx="5" cy="19" r="2.2"></circle><circle cx="19" cy="19" r="2.2"></circle><line x1="6.6" y1="6.6" x2="10.5" y2="10.5"></line><line x1="17.4" y1="6.6" x2="13.5" y2="10.5"></line><line x1="6.6" y1="17.4" x2="10.5" y2="13.5"></line><line x1="17.4" y1="17.4" x2="13.5" y2="13.5"></line><rect x="10" y="10" width="4" height="4" rx="1"></rect>',
        'arrow-right' => '<line x1="4" y1="12" x2="20" y2="12"></line><polyline points="14 6 20 12 14 18"></polyline>',
        'arrow-up-right' => '<line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline>',
        'check'       => '<polyline points="20 6 9 17 4 12"></polyline>',
        'menu'        => '<line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line>',
        'x'           => '<line x1="5" y1="5" x2="19" y2="19"></line><line x1="19" y1="5" x2="5" y2="19"></line>',
        'mail'        => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><polyline points="3 7 12 13 21 7"></polyline>',
        'phone'       => '<path d="M5 4h3.5l1.5 4-2 1.5a12 12 0 0 0 6.5 6.5l1.5-2 4 1.5V19a2 2 0 0 1-2 2C10.5 21 3 13.5 3 6a2 2 0 0 1 2-2z"></path>',
        'map-pin'     => '<path d="M12 21s-6.5-6.1-6.5-11A6.5 6.5 0 0 1 18.5 10c0 4.9-6.5 11-6.5 11z"></path><circle cx="12" cy="10" r="2.3"></circle>',
        'star'        => '<polygon points="12 2 15 9 22 9.5 16.5 14 18.5 21 12 17 5.5 21 7.5 14 2 9.5 9 9"></polygon>',
        'play'        => '<polygon points="6 4 20 12 6 20"></polygon>',
        'chevron-down'=> '<polyline points="6 9 12 15 18 9"></polyline>',
        'globe'       => '<circle cx="12" cy="12" r="9"></circle><line x1="3" y1="12" x2="21" y2="12"></line><path d="M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9z"></path>',
        'instagram'   => '<rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.3" cy="6.7" r="1" fill="currentColor" stroke="none"></circle>',
        'facebook'    => '<path d="M14 9h3V5h-3a4 4 0 0 0-4 4v2H7v4h3v6h4v-6h3l1-4h-4V9a1 1 0 0 1 1-1z"></path>',
        'linkedin'    => '<rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="7.5" y1="10" x2="7.5" y2="17"></line><circle cx="7.5" cy="6.8" r="1" fill="currentColor" stroke="none"></circle><path d="M11.5 17v-4.5a2.5 2.5 0 0 1 5 0V17"></path><line x1="11.5" y1="10" x2="11.5" y2="17"></line>',
        'youtube'     => '<rect x="2.5" y="6" width="19" height="12" rx="3"></rect><polygon points="10.5 9.5 15.5 12 10.5 14.5" fill="currentColor" stroke="none"></polygon>',
        'quote'       => '<path d="M9 7c-2.5 0-4.5 2-4.5 4.5S6.5 16 9 16" ></path><path d="M6 11c0-2.5 1.5-4.5 4-5.5"></path><path d="M18 7c-2.5 0-4.5 2-4.5 4.5S15.5 16 18 16"></path><path d="M15 11c0-2.5 1.5-4.5 4-5.5"></path>',
        'clock'       => '<circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 16 14"></polyline>',
        'layers'      => '<polygon points="12 3 21 8 12 13 3 8"></polygon><polyline points="3 13 12 18 21 13"></polyline><polyline points="3 17.5 12 22.5 21 17.5"></polyline>',
        'trending-up' => '<polyline points="3 17 10 10 14 14 21 6"></polyline><polyline points="15 6 21 6 21 12"></polyline>',
    ];
    $inner = $paths[$name] ?? $paths['star'];
    return '<svg class="' . h($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
}
