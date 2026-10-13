<?php /** Samotné video + tieň pre hero. Očakáva $heroVideoSrc a $heroPoster z templates/hero-video.php. */ ?>
<video class="page-hero__video" autoplay muted loop playsinline preload="auto"<?= !empty($heroPoster) ? ' poster="' . h($heroPoster) . '"' : '' ?> aria-hidden="true" tabindex="-1">
  <source src="<?= h($heroVideoSrc) ?>" type="video/mp4">
</video>
<span class="page-hero__shade" aria-hidden="true"></span>
