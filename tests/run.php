<?php

declare(strict_types=1);

/**
 * Tests unitaires sans dépendance externe. Usage : php tests/run.php
 * Utilise une base SQLite en mémoire.
 */

require __DIR__ . '/../src/bootstrap.php';

$failures = 0;
$total = 0;

function check(string $label, bool $condition): void
{
    global $failures, $total;
    $total++;
    if ($condition) {
        echo "  ok   {$label}\n";
    } else {
        $failures++;
        echo "  FAIL {$label}\n";
    }
}

function throws(callable $fn): bool
{
    try {
        $fn();
    } catch (BookingException) {
        return true;
    }
    return false;
}

$pdo = Database::connect('sqlite::memory:');
$pdo->exec((string) file_get_contents(__DIR__ . '/../database/schema.sql'));

$users = new UserService($pdo);
$bookings = new BookingService($pdo);

echo "Utilisateurs\n";
$r = $users->register('Awa Koné', 'awa@test.ci', '0707070707', 'motdepasse1');
check('inscription valide', $r['id'] !== null);
check('e-mail déjà utilisé refusé', $users->register('Autre', 'awa@test.ci', '', 'motdepasse1')['errors'] !== []);
check('mot de passe court refusé', $users->register('Bob', 'bob@test.ci', '', 'abc')['errors'] !== []);
check('connexion correcte', $users->authenticate('awa@test.ci', 'motdepasse1') === $r['id']);
check('connexion mauvais mot de passe', $users->authenticate('awa@test.ci', 'faux') === null);
$uid = (int) $r['id'];

$pdo->prepare('INSERT INTO trips (company, origin, destination, departure_at, arrival_at, price, seats_total, bus_type)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
    ->execute(['Test Bus', 'Abidjan', 'Bouaké', date('Y-m-d H:i:s', strtotime('+3 days')),
        date('Y-m-d H:i:s', strtotime('+3 days 6 hours')), 7500, 3, 'Standard']);
$tripId = (int) $pdo->lastInsertId();

echo "Réservations\n";
check('recherche trouve le trajet', count($bookings->searchTrips('Abidjan', 'Bouaké', date('Y-m-d', strtotime('+3 days')))) === 1);
$ref = $bookings->createBooking($uid, $tripId, ['Awa Koné', 'Moussa Koné'], 'wave', '0707070707');
$booking = $bookings->findBooking($ref, $uid);
check('total = prix x passagers', (int) $booking['total'] === 15000);
check('sièges 1 et 2 attribués', array_column($booking['passengers'], 'seat_number') === [1, 2]);
check('places restantes = 1', (int) $bookings->getTrip($tripId)['seats_left'] === 1);
check('surréservation refusée', throws(fn () => $bookings->createBooking($uid, $tripId, ['A B', 'C D'], 'wave', '0707070707')));
check('moyen de paiement invalide refusé', throws(fn () => $bookings->createBooking($uid, $tripId, ['A B'], 'bitcoin', '0707070707')));
check('numéro invalide refusé', throws(fn () => $bookings->createBooking($uid, $tripId, ['A B'], 'wave', 'abc')));
check('plus de 5 passagers refusé', throws(fn () => $bookings->createBooking($uid, $tripId, ['a1', 'a2', 'a3', 'a4', 'a5', 'a6'], 'wave', '0707070707')));

echo "Annulation\n";
check('annulation possible', $bookings->canCancel($booking, 2));
$bookings->cancelBooking($ref, $uid, 2);
check('places libérées après annulation', (int) $bookings->getTrip($tripId)['seats_left'] === 3);
check('double annulation refusée', throws(fn () => $bookings->cancelBooking($ref, $uid, 2)));
$ref2 = $bookings->createBooking($uid, $tripId, ['Nouveau Passager'], 'wave', '0707070707');
check('siège libéré réattribué', $bookings->findBooking($ref2, $uid)['passengers'][0]['seat_number'] === 1);
check('billet d\'un autre utilisateur introuvable', $bookings->findBooking($ref2, $uid + 99) === null);

echo "\n{$total} tests, {$failures} échec(s)\n";
exit($failures > 0 ? 1 : 0);
