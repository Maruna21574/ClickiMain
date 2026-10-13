<?php
/**
 * Video na pozadí hero sekcie podstránok.
 * Očakáva $heroVideoName ('services' | 'portfolio' | 'about' | 'contact'). Ak existuje assets/video/{name}.mp4, použije sa
 * to; inak sa použije spoločné services.mp4. Bez súboru sa nezobrazí nič (hero ostane s ružovou žiarou).
 * Vráti (cez premenné) $heroVideoSrc, $heroPoster; $heroHasVideo = true/false.
 */
$heroVideoName = $heroVideoName ?? 'services';
$heroVideoSrc = null;
foreach ([$heroVideoName, 'services'] as $n) {
    if (is_file(SITE_ROOT . '/assets/video/' . $n . '.mp4')) {
        $heroVideoSrc = '/assets/video/' . $n . '.mp4';
        $heroPoster = is_file(SITE_ROOT . '/assets/video/' . $n . '-poster.jpg') ? '/assets/video/' . $n . '-poster.jpg' : '';
        break;
    }
}
$heroHasVideo = $heroVideoSrc !== null;
