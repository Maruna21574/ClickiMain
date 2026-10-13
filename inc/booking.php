<?php
/**
 * Rezervácia 15-minútovej konzultácie (hovoru). Termíny: pracovné dni, každých 30 minút, bez obedňajšej prestávky.
 * Tabuľka `bookings` má unikátny index (dátum + čas) pre nezrušené rezervácie, takže jeden termín sa nedá obsadiť dvakrát.
 */

const BOOKING_DAYS_AHEAD = 42;       // dokedy dopredu sa dá rezervovať (6 týždňov)
const BOOKING_LEAD_MINUTES = 120;    // najbližší termín je najskôr o 2 hodiny
const BOOKING_FIRST = '09:00';
const BOOKING_LAST = '16:30';
const BOOKING_STEP_MIN = 30;
const BOOKING_SKIP = ['12:00', '12:30'];

/** Všetky časy v rámci dňa (HH:MM). */
function booking_times(): array
{
    $out = [];
    $t = strtotime('2000-01-01 ' . BOOKING_FIRST);
    $end = strtotime('2000-01-01 ' . BOOKING_LAST);
    while ($t <= $end) {
        $hm = date('H:i', $t);
        if (!in_array($hm, BOOKING_SKIP, true)) {
            $out[] = $hm;
        }
        $t += BOOKING_STEP_MIN * 60;
    }
    return $out;
}

/** Obsadené termíny od dnešného dňa: ['2026-10-12|09:00' => true]. */
function booking_taken(): array
{
    $stmt = db()->prepare("SELECT slot_date, slot_time FROM bookings WHERE status != 'cancelled' AND slot_date >= ?");
    $stmt->execute([date('Y-m-d')]);
    $taken = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $taken[$r['slot_date'] . '|' . $r['slot_time']] = true;
    }
    return $taken;
}

/** Voľné termíny po dňoch: [['date' => 'YYYY-MM-DD', 'times' => ['09:00', ...]], ...] (dni bez voľných termínov sa vynechajú). */
function booking_available(): array
{
    $taken = booking_taken();
    $now = time() + BOOKING_LEAD_MINUTES * 60;
    $days = [];
    for ($i = 0; $i <= BOOKING_DAYS_AHEAD; $i++) {
        $ts = strtotime("+$i day");
        if ((int)date('N', $ts) > 5) {
            continue; // víkend
        }
        $date = date('Y-m-d', $ts);
        $times = [];
        foreach (booking_times() as $hm) {
            if (strtotime("$date $hm") < $now || isset($taken["$date|$hm"])) {
                continue;
            }
            $times[] = $hm;
        }
        if ($times) {
            $days[] = ['date' => $date, 'times' => $times];
        }
    }
    return $days;
}

function booking_slot_is_free(string $date, string $time): bool
{
    foreach (booking_available() as $d) {
        if ($d['date'] === $date && in_array($time, $d['times'], true)) {
            return true;
        }
    }
    return false;
}

function booking_day_label(string $date, string $lang): array
{
    $ts = strtotime($date);
    $sk = ['', 'Po', 'Ut', 'St', 'Št', 'Pi', 'So', 'Ne'];
    $en = ['', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $n = (int)date('N', $ts);
    return [($lang === 'en' ? $en : $sk)[$n], date('j.n.', $ts)];
}
