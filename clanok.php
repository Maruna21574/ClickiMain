<?php
require_once __DIR__ . '/inc/functions.php';

$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
// prihlásený admin vidí aj nezverejnený článok (náhľad)
$post = $slug !== '' ? get_post_by_slug($slug, is_logged_in()) : null;

if (!$post) {
    http_response_code(404);
    $pageTitle = t('blog.empty') . ' — Clicki';
    require __DIR__ . '/templates/header.php';
    ?>
    <section class="error-page">
      <div class="container text-center">
        <h1><?= h(t('blog.empty')) ?></h1>
        <p class="lead" style="margin-block:1.5rem;"><a href="/blog" class="btn btn--primary"><?= h(t('blog.back')) ?> <?= icon('arrow-right') ?></a></p>
      </div>
    </section>
    <?php
    require __DIR__ . '/templates/footer.php';
    exit;
}

$title = post_text($post, 'title');
$summary = post_text($post, 'summary');
$body = post_text($post, 'body');
$cover = post_cover($post);
$topicKey = 'blog.topic.' . $post['topic'];
$topicLabel = t($topicKey) !== $topicKey ? t($topicKey) : t('blog.topic.web');
$minutes = reading_minutes($body);
$bodyHtml = render_post_body($body, $toc);

$pageTitle = mb_strlen($title) > 50 ? $title : $title . ' | Clicki';
$pageDesc = $summary;
if (mb_strlen($pageDesc) < 110) {
    // krátky popis doplníme začiatkom článku, nech má vhodnú dĺžku do výsledkov Google
    $firstPara = trim(strip_tags(preg_split('~\R{2,}~u', $body)[0] ?? ''));
    $pageDesc = trim($pageDesc . ' ' . $firstPara);
}
$pageDesc = excerpt($pageDesc, 158);
$activeNav = '';
$ogType = 'article';
$ogImage = $cover;
$headExtra = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $title,
    'description' => $pageDesc,
    'image' => $cover,
    'datePublished' => $post['published_at'],
    'inLanguage' => current_lang(),
    'author' => ['@type' => 'Organization', 'name' => 'Clicki'],
    'publisher' => ['@type' => 'Organization', 'name' => 'Clicki'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";

// predchádzajúci / nasledujúci článok (zoznam je od najnovšieho)
$all = get_posts();
$idx = array_search((int)$post['id'], array_map('intval', array_column($all, 'id')), true);
$newer = ($idx !== false && $idx > 0) ? $all[$idx - 1] : null;
$older = ($idx !== false && isset($all[$idx + 1])) ? $all[$idx + 1] : null;
$more = get_posts(3, (int)$post['id']);
$postUrl = '/clanok?slug=' . urlencode($post['slug']);

require __DIR__ . '/templates/header.php';
?>

<div class="reading-progress" aria-hidden="true"><span></span></div>

<article>
  <header class="post-hero">
    <img class="post-hero__img" src="<?= h($cover) ?>" alt="">
    <span class="post-hero__shade" aria-hidden="true"></span>
    <div class="container container--narrow post-hero__inner">
      <a href="/blog" class="post-back"><?= icon('arrow-right') ?> <?= h(t('blog.back')) ?></a>
      <div class="post-card__meta">
        <span class="post-card__topic"><?= h($topicLabel) ?></span>
        <span><?= h(format_date($post['published_at'])) ?></span>
        <span><?= $minutes ?> <?= h(t('blog.minutes')) ?></span>
      </div>
      <h1><?= h($title) ?></h1>
      <?php if ($summary !== ''): ?><p class="post-hero__lead"><?= h($summary) ?></p><?php endif; ?>
      <div class="post-author">
        <img src="/assets/img/clicki_favicon_small.png" alt="">
        <span><strong><?= h(t('blog.author_name')) ?></strong></span>
      </div>
    </div>
  </header>

  <section class="section section--tight">
    <div class="container post-layout">
      <?php if (count($toc) >= 3): ?>
      <aside class="post-toc" data-toc aria-label="<?= h(t('blog.toc')) ?>">
        <p class="post-toc__title"><?= h(t('blog.toc')) ?></p>
        <ol>
          <?php foreach ($toc as $item): ?>
          <li><a href="#<?= h($item['id']) ?>" data-toc-link="<?= h($item['id']) ?>"><?= h($item['title']) ?></a></li>
          <?php endforeach; ?>
        </ol>
      </aside>
      <?php endif; ?>

      <div class="post-main">
        <div class="post-body" data-post-body><?= $bodyHtml ?></div>

        <div class="post-share">
          <span><?= h(t('blog.share')) ?></span>
          <button type="button" class="btn btn--ghost btn--sm" data-copy-link data-copied="<?= h(t('blog.copied')) ?>"><?= h(t('blog.copy_link')) ?></button>
        </div>

        <div class="post-authorbox">
          <img src="/assets/img/clicki_logo_white.png" alt="Clicki">
          <div>
            <strong><?= h(t('blog.author_name')) ?></strong>
            <p><?= h(t('blog.author_text')) ?></p>
          </div>
        </div>

        <?php if ($newer || $older): ?>
        <nav class="post-pager">
          <?php if ($older): ?>
          <a href="/clanok?slug=<?= urlencode($older['slug']) ?>" class="post-pager__item">
            <small><?= h(t('blog.prev')) ?></small>
            <span><?= h(post_text($older, 'title')) ?></span>
          </a>
          <?php else: ?><span></span><?php endif; ?>
          <?php if ($newer): ?>
          <a href="/clanok?slug=<?= urlencode($newer['slug']) ?>" class="post-pager__item post-pager__item--next">
            <small><?= h(t('blog.next')) ?></small>
            <span><?= h(post_text($newer, 'title')) ?></span>
          </a>
          <?php endif; ?>
        </nav>
        <?php endif; ?>
      </div>
    </div>
  </section>
</article>

<?php if ($more): ?>
<section class="section more-projects">
  <div class="container">
    <div class="more-projects__head">
      <h2><?= h(t('blog.more')) ?></h2>
      <a href="/blog" class="btn btn--ghost btn--sm"><?= h(t('blog.back')) ?> <?= icon('arrow-right') ?></a>
    </div>
    <div class="grid post-grid">
      <?php foreach ($more as $post): ?>
      <?php require __DIR__ . '/templates/post-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $ctaTitle = t('blog.cta_title'); $ctaText = t('blog.cta_text'); $ctaButton = t('blog.cta_button'); ?>
<?php require __DIR__ . '/templates/cta-band.php'; ?>

<?php $extraScripts = ['/assets/js/blog.js']; ?>
<?php require __DIR__ . '/templates/footer.php'; ?>
