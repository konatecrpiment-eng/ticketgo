<?php

declare(strict_types=1);

$user = require_login();
$service = new BookingService(Database::pdo());

render('tickets', [
    'title' => 'Mes billets',
    'bookings' => $service->userBookings($user['id']),
    'now' => date('Y-m-d H:i:s'),
]);
