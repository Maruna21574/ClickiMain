<?php /** Samotné video + tieň pre hero. Očakáva $heroVideoSrc a $heroPoster z templates/hero-video.php. */ ?>
<?php if (!empty($heroPoster)): ?>
<img class="page-hero__poster" src="<?= h($heroPoster) ?>" alt="" aria-hidden="true" decoding="async">
<?php endif; ?>
<video class="page-hero__video" autoplay muted loop playsinline preload="auto" aria-hidden="true" tabindex="-1" onplaying="this.classList.add('is-playing')">
  <source src="<?= h($heroVideoSrc) ?>" type="video/mp4">
</video>
<span class="page-hero__shade" aria-hidden="true"></span>
