<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');
    if ($id && in_array($status, ['new', 'done', 'cancelled'], true)) {
        // zrušená rezervácia uvoľní termín (unikátny index platí len pre nezrušené)
        try {
            db()->prepare('UPDATE bookings SET status = ? WHERE id = ?')->execute([$status, $id]);
        } catch (PDOException $e) {
            // obnovenie zrušenej rezervácie, ak je termín medzitým obsadený, sa nepodarí
        }
    }
    redirect('/admin/bookings.php');
}

$today = date('Y-m-d');
$stmt = db()->prepare('SELECT * FROM bookings WHERE slot_date >= ? ORDER BY slot_date, slot_time');
$stmt->execute([$today]);
$upcoming = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = db()->prepare('SELECT * FROM bookings WHERE slot_date < ? ORDER BY slot_date DESC, slot_time DESC LIMIT 30');
$stmt->execute([$today]);
$past = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Rezervácie — Admin Clicki';
$adminActive = 'bookings';
require __DIR__ . '/../templates/admin-header.php';

$statusLabel = ['new' => ['Nová', 'tag-pill--pink'], 'done' => ['Vybavená', ''], 'cancelled' => ['Zrušená', 'tag-pill--muted']];
$actions = ['done' => 'Vybavená', 'cancelled' => 'Zrušiť', 'new' => 'Obnoviť'];
$renderRows = function (array $rows) use ($statusLabel, $actions) {
    foreach ($rows as $b) {
        [$lbl, $cls] = $statusLabel[$b['status']] ?? ['?', ''];
        ?>
        <tr>
          <td><strong><?= h(date('j.n.Y', strtotime($b['slot_date']))) ?></strong><br><?= h($b['slot_time']) ?></td>
          <td><?= h($b['name']) ?><?= $b['topic'] !== '' ? '<br><small style="color:var(--text-faint)">' . h($b['topic']) . '</small>' : '' ?></td>
          <td><a href="tel:<?= h($b['phone']) ?>"><?= h($b['phone']) ?></a><?= $b['email'] !== '' ? '<br><a href="mailto:' . h($b['email']) . '">' . h($b['email']) . '</a>' : '' ?></td>
          <td><span class="tag-pill <?= h($cls) ?>"><?= h($lbl) ?></span></td>
          <td class="row-actions">
            <?php foreach ($actions as $st => $label): if ($st === $b['status']) continue; ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <input type="hidden" name="status" value="<?= h($st) ?>">
              <button type="submit" class="btn btn--ghost btn--sm"><?= h($label) ?></button>
            </form>
            <?php endforeach; ?>
          </td>
        </tr>
        <?php
    }
};
?>

<div class="admin-topbar"><h1>Rezervácie hovorov</h1></div>

<h2 style="font-size:var(--fs-lg);margin-bottom:1rem;">Nadchádzajúce</h2>
<div class="admin-card" style="padding:0;overflow-x:auto;margin-bottom:2rem;">
  <table class="admin-table">
    <thead><tr><th>Termín</th><th>Meno / téma</th><th>Kontakt</th><th>Stav</th><th></th></tr></thead>
    <tbody>
      <?php $renderRows($upcoming); ?>
      <?php if (!$upcoming): ?><tr><td colspan="5" style="color:var(--text-faint);text-align:center;padding:2rem;">Zatiaľ žiadne rezervácie.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($past): ?>
<h2 style="font-size:var(--fs-lg);margin-bottom:1rem;">Minulé (posledných 30)</h2>
<div class="admin-card" style="padding:0;overflow-x:auto;">
  <table class="admin-table">
    <thead><tr><th>Termín</th><th>Meno / téma</th><th>Kontakt</th><th>Stav</th><th></th></tr></thead>
    <tbody><?php $renderRows($past); ?></tbody>
  </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../templates/admin-footer.php'; ?>
