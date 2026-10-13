<?php
/**
 * Karta článku blogu. Očakáva $post (riadok z tabuľky posts).
 */
$postTitle = post_text($post, 'title');
$topicKey = 'blog.topic.' . $post['topic'];
$topicLabel = t($topicKey) !== $topicKey ? t($topicKey) : t('blog.topic.web');
?>
<a href="/clanok?slug=<?= urlencode($post['slug']) ?>" class="post-card">
  <div class="post-card__media">
    <img src="<?= h(post_cover($post)) ?>" alt="<?= h($postTitle) ?>" loading="lazy">
  </div>
  <div class="post-card__body">
    <div class="post-card__meta">
      <span class="post-card__topic"><?= h($topicLabel) ?></span>
      <span><?= h(format_date($post['published_at'])) ?></span>
    </div>
    <h3 class="post-card__title"><?= h($postTitle) ?></h3>
    <p class="post-card__text"><?= h(post_text($post, 'summary')) ?></p>
    <span class="post-card__more"><?= h(t('blog.read')) ?> <?= icon('arrow-right') ?></span>
  </div>
</a>
