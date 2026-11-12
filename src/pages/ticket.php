<?php

declare(strict_types=1);

$user = require_login();
$service = new BookingService(Database::pdo());
$limit = (int) env('CANCEL_LIMIT_HOURS', '2');
$booking = $service->findBooking((string) ($_GET['ref'] ?? ''), $user['id']);

if ($booking === null) {
    http_response_code(404);
    render('404', ['title' => 'Billet introuvable']);
    exit;
}

render('ticket', [
    'title' => 'Billet ' . $booking['reference'],
    'booking' => $booking,
    'cancellable' => $service->canCancel($booking, $limit),
    'limit' => $limit,
]);
