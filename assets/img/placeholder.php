<?php
/**
 * Generuje branded SVG placeholder (chrome/pink) namiesto stock fotiek pre demo projekty.
 * Zámerne nezávisí od DB/session — je to ľahký obrázkový endpoint volaný ako <img src>.
 */

header('Content-Type: image/svg+xml; charset=utf-8');
header('Cache-Control: public, max-age=31536000, immutable');

$title = isset($_GET['title']) ? (string)$_GET['title'] : 'Clicki projekt';
$cat = isset($_GET['cat']) ? (string)$_GET['cat'] : 'web';
$seed = isset($_GET['seed']) ? (string)$_GET['seed'] : $title;

function ph_esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function ph_wrap(string $text, int $maxChars): array
{
    $words = preg_split('/\s+/u', trim($text)) ?: [];
    $lines = [];
    $line = '';
    foreach ($words as $w) {
        $candidate = $line === '' ? $w : $line . ' ' . $w;
        if (mb_strlen($candidate) > $maxChars && $line !== '') {
            $lines[] = $line;
            $line = $w;
        } else {
            $line = $candidate;
        }
    }
    if ($line !== '') {
        $lines[] = $line;
    }
    return array_slice($lines, 0, 3);
}

$icons = [
    'web'            => '<polyline points="-13 -4 -21 4 -13 12"></polyline><polyline points="13 -4 21 4 13 12"></polyline>',
    'konfiguratory'  => '<line x1="-14" y1="16" x2="-14" y2="2"></line><line x1="-14" y1="-4" x2="-14" y2="-16"></line><line x1="0" y1="16" x2="0" y2="4"></line><line x1="0" y1="-2" x2="0" y2="-16"></line><line x1="14" y1="16" x2="14" y2="8"></line><line x1="14" y1="2" x2="14" y2="-16"></line><circle cx="-14" cy="-1" r="3"></circle><circle cx="0" cy="1" r="3"></circle><circle cx="14" cy="5" r="3"></circle>',
    'socialne-siete' => '<circle cx="12" cy="-13" r="4"></circle><circle cx="-14" cy="0" r="4"></circle><circle cx="12" cy="13" r="4"></circle><line x1="-10.7" y1="-2" x2="8.7" y2="-11"></line><line x1="-10.7" y1="2" x2="8.7" y2="11"></line>',
    'grafika'        => '<circle cx="0" cy="0" r="17"></circle><circle cx="-6" cy="-5" r="2.3"></circle><circle cx="1" cy="-10" r="2.3"></circle><circle cx="8" cy="-5" r="2.3"></circle><path d="M-9 6c1.8 2.2 4.4 2.7 7.2 2.7 4 0 5.4-1.8 5.4-4 0-1.8-1.8-2.3-1.8-4.1 0-2.3 2.1-2.7 4.5-2.3"></path>',
    'foto'           => '<path d="M-16 -9h6l2.7-3.6h12.6L-16 -9h6l2.7-3.6h12.6L8 -9h8a1.8 1.8 0 0 1 1.8 1.8v16A1.8 1.8 0 0 1 16 10.6H-16A1.8 1.8 0 0 1 -17.8 8.8v-16A1.8 1.8 0 0 1 -16 -9z"></path><circle cx="0" cy="1" r="6.2"></circle>',
    'dron'           => '<circle cx="-14" cy="-14" r="4"></circle><circle cx="14" cy="-14" r="4"></circle><circle cx="-14" cy="14" r="4"></circle><circle cx="14" cy="14" r="4"></circle><line x1="-11" y1="-11" x2="-4" y2="-4"></line><line x1="11" y1="-11" x2="4" y2="-4"></line><line x1="-11" y1="11" x2="-4" y2="4"></line><line x1="11" y1="11" x2="4" y2="4"></line><rect x="-6" y="-6" width="12" height="12" rx="2.5"></rect>',
];
$iconMarkup = $icons[$cat] ?? $icons['web'];

$hash = crc32($seed);
$angle = 25 + ($hash % 40);
$hue = ($hash >> 3) % 30 - 15;
$lines = ph_wrap($title, 20);

$w = 1200;
$hgt = 900;
$cx = $w / 2;
$cy = $hgt / 2;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<svg xmlns="http://www.w3.org/2000/svg" width="<?= $w ?>" height="<?= $hgt ?>" viewBox="0 0 <?= $w ?> <?= $hgt ?>">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%" gradientTransform="rotate(<?= $angle ?>)">
      <stop offset="0%" stop-color="#0B0B0D"></stop>
      <stop offset="55%" stop-color="#141416"></stop>
      <stop offset="100%" stop-color="#1c1d20"></stop>
    </linearGradient>
    <radialGradient id="glow" cx="80%" cy="15%" r="65%">
      <stop offset="0%" stop-color="#F43182" stop-opacity="0.35"></stop>
      <stop offset="45%" stop-color="#F43182" stop-opacity="0.08"></stop>
      <stop offset="100%" stop-color="#F43182" stop-opacity="0"></stop>
    </radialGradient>
    <linearGradient id="chrome" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#3a3b3e"></stop>
      <stop offset="30%" stop-color="#d8d9db"></stop>
      <stop offset="55%" stop-color="#7c7e82"></stop>
      <stop offset="80%" stop-color="#e8e9ea"></stop>
      <stop offset="100%" stop-color="#45464a"></stop>
    </linearGradient>
  </defs>

  <rect width="<?= $w ?>" height="<?= $hgt ?>" fill="url(#bg)"></rect>
  <rect width="<?= $w ?>" height="<?= $hgt ?>" fill="url(#glow)"></rect>

  <g stroke="url(#chrome)" stroke-width="1" opacity="0.35">
    <line x1="0" y1="<?= $hgt * 0.18 ?>" x2="<?= $w ?>" y2="<?= $hgt * 0.05 ?>"></line>
    <line x1="0" y1="<?= $hgt * 0.32 ?>" x2="<?= $w ?>" y2="<?= $hgt * 0.19 ?>"></line>
  </g>

  <g transform="translate(<?= $cx ?>, <?= $cy - 40 ?>) scale(4.2)" stroke="url(#chrome)" stroke-width="1.1" fill="none" stroke-linecap="round" stroke-linejoin="round" opacity="0.9">
    <?= $iconMarkup ?>
  </g>

  <text x="90" y="<?= $hgt - 210 ?>" font-family="Arial, sans-serif" font-size="20" letter-spacing="4" fill="#F43182" font-weight="700"><?= strtoupper(ph_esc($cat)) ?></text>

  <text x="88" y="<?= $hgt - 150 ?>" font-family="Arial, sans-serif" font-size="46" font-weight="700" fill="#F5F5F6">
    <?php foreach ($lines as $i => $line): ?>
    <tspan x="90" dy="<?= $i === 0 ? 0 : 54 ?>"><?= ph_esc($line) ?></tspan>
    <?php endforeach; ?>
  </text>

  <text x="<?= $w - 90 ?>" y="<?= $hgt - 60 ?>" text-anchor="end" font-family="Georgia, serif" font-size="30" font-weight="700" fill="#F43182" opacity="0.85">Clicki</text>
</svg>
