<?php

declare(strict_types=1);

$user = require_login();
$service = new BookingService(Database::pdo());
$trip = $service->getTrip((int) ($_GET['trip'] ?? 0));

if ($trip === null || $trip['departure_at'] <= date('Y-m-d H:i:s')) {
    flash('Ce trajet n\'est plus disponible.', 'warning');
    redirect('home');
}

$error = null;
$names = [$user['full_name']];
$method = 'orange_money';
$phone = (string) $user['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $names = array_map('strval', (array) ($_POST['passengers'] ?? []));
    $method = (string) ($_POST['method'] ?? '');
    $phone = (string) ($_POST['phone'] ?? '');
    try {
        $reference = $service->createBooking($user['id'], (int) $trip['id'], $names, $method, $phone);
        flash('Paiement confirmé ! Votre billet est prêt.', 'success');
        redirect('ticket', ['ref' => $reference]);
    } catch (BookingException $e) {
        $error = $e->getMessage();
        $trip = $service->getTrip((int) $trip['id']);
    }
}

render('book', [
    'title' => "Réserver {$trip['origin']} → {$trip['destination']}",
    'trip' => $trip,
    'names' => $names,
    'method' => $method,
    'phone' => $phone,
    'error' => $error,
]);
